<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\AdcDate;
use App\Models\CapacityLevel;
use App\Models\Booking;
use App\Models\CpfLocationMapping;
use App\Models\EmployeeLocationMapping;
use App\Models\FrozenLevel;
use App\Notifications\BookingCreatedNotification;

class BookingController extends Controller
{

    public function employeeInfo()
    {
        $user = Auth::user();

        $booking = Booking::with('adcDate.centre')
            ->where('user_id', $user->id)
            ->first();

        if ($booking) {
            return view('employee.index', [
                'user' => $user,
                'booking' => $booking,
                'adcDates' => collect(),
                'alreadyBooked' => true
            ]);
        }

        $centreId = CpfLocationMapping::where('cpf_no', $user->cpf_no)
            ->value('adc_centre_id');
        if (!$centreId) {
            return view('employee.index', [
                'user' => $user,
                'adcDates' => collect(),
                'alreadyBooked' => false,
                'booking' => null,
                'error' => 'Your CPF is not mapped to any ADC Centre. Please contact admin.'
            ]);
        }

        $level = Auth::user()->level;
        $isFrozen = FrozenLevel::where('level', $level)
            ->where('is_frozen', true)
            ->exists();

        if ($isFrozen) {
            return view('employee.index', [
                'user' => Auth::user(),
                'adcDates' => collect(),
                'alreadyBooked' => false,
                'frozen' => true,
                'capacities' => collect(),
                'booking' => null
            ]);
        }

        $levelName = $user->level;
        $adcDates = AdcDate::with(['centre', 'capacities' => function ($q) use ($levelName) {
            $q->where('level', $levelName);
        }])
            ->where('adc_centre_id', $centreId)
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($date) {
                $cap = $date->capacities->first();
                if (!$cap) return null;
                $cap->current_count = Booking::where('adc_date_id', $cap->adc_date_id)->count();
                $date->capacity_for_level = $cap;
                return $date;
            })
            ->filter(fn($date) => $date && $date->capacity_for_level->current_count < $date->capacity_for_level->capacity);
        return view('employee.index', [
            'user' => $user,
            'adcDates' => $adcDates,
            'alreadyBooked' => false,
            'booking' => null,
            'allDatesFull' => $adcDates->isEmpty() && !$isFrozen
        ]);
    }


    /**
     * Show available dates based on employee level.
     */
    /* public function index()
    {
        $user = Auth::user();
        $level = $user->level;

        $capacities = CapacityLevel::with('adcDate.centre')
            ->where('level', $level)
            ->get()
            ->filter(function ($capacity) {
                $current_count = Booking::where('adc_date_id', $capacity->adc_date_id)
                    ->whereHas('user', function ($q) use ($capacity) {
                        $q->where('level', $capacity->level);
                    })
                    ->count();
                $capacity->current_count = $current_count;

                return $current_count < $capacity->capacity;
            });

        return view('employee.bookings.index', ['capacities' => $capacities]);
    } */


    /**
     * Show preview page before final booking.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'capacity_id' => 'required|exists:capacity_levels,id',
        ]);

        $user = Auth::user();
        $capacity = CapacityLevel::with('adcDate.centre', 'level')
            ->findOrFail($request->capacity_id);
        $date = $capacity->adcDate;
        $current_count = Booking::where('adc_date_id', $capacity->adc_date_id)->count();
        $capacity->current_count = $current_count;

        return view('employee.bookings.preview', [
            'user'     => $user,
            'capacity' => $capacity,
            'date'     => $date,
            'centre'   => $date->centre
        ]);
    }


    /**
     * Final booking + assign centre + update level capacity.
     */
    public function store(Request $request)
    {
        $request->validate([
            'capacity_id' => 'required|exists:capacity_levels,id'
        ]);

        $user = Auth::user();
        $capacityId = $request->capacity_id;

        DB::transaction(function () use ($user, $capacityId) {

            $capacity = CapacityLevel::where('id', $capacityId)
                ->lockForUpdate()
                ->first();

            $current_count = Booking::where('adc_date_id', $capacity->adc_date_id)->count();

            $alreadyBooked = Booking::where('user_id', $user->id)->exists();
            if ($alreadyBooked) {
                throw new \Exception("You have already booked a slot.");
            }

            if ($current_count >= $capacity->capacity) {
                throw new \Exception("This date is full for your level.");
            }

            $booking = Booking::create([
                'user_id'     => $user->id,
                'adc_date_id' => $capacity->adc_date_id,
                'created_by'  => $user->id,
            ]);

            $user->notify(new BookingCreatedNotification($booking));
            session()->put('booking_id', $booking->id);
        });

        return redirect()->route('employee.bookings.confirmation', session('booking_id'));
    }


    /**
     * Show final confirmation.
     */
    public function confirmation($bookingId)
    {
        $booking = Booking::with('adcDate.centre')->findOrFail($bookingId);

        return view('employee.bookings.confirmation', [
            'booking' => $booking
        ]);
    }
}
