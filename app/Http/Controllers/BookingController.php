<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\AdcDate;
use App\Models\CapacityLevel;
use App\Models\Booking;

class BookingController extends Controller
{

    /* public function employeeInfo()
    {
        $user = Auth::user();
        $levelId = $user->level_id;

        /* $capacities = CapacityLevel::with(['adcDate.centre', 'level'])
            ->where('level_id', $levelId)
            ->orderBy('adc_date_id', 'asc')
            ->get(); */

    /*$capacities = CapacityLevel::with(['adcDate.centre', 'level'])
            ->where('level_id', $levelId)
            ->get()
            ->filter(function ($capacity) {
                $current_count = Booking::where('adc_date_id', $capacity->adc_date_id)->count();
                $capacity->current_count = $current_count;

                return $current_count < $capacity->capacity;
            });

        return view('employee.index', ['user' => $user, 'capacities'  => $capacities]);
    } */

    public function employeeInfo()
    {
        $user = Auth::user();

        // Check if employee already booked
        $booking = Booking::with('adcDate.centre')
            ->where('user_id', $user->id)
            ->first();

        if ($booking) {
            return view('employee.index', [
                'user'    => $user,
                'booking' => $booking,
                'capacities' => collect(),
                'alreadyBooked' => true
            ]);
        }

        // If no booking yet → show available dates
        $levelId = $user->level_id;

        $capacities = CapacityLevel::with('adcDate.centre')
            ->where('level_id', $levelId)
            ->get()
            ->map(function ($cap) {
                $cap->current_count = Booking::where('adc_date_id', $cap->adc_date_id)->count();
                return $cap;
            })
            ->filter(function ($cap) {
                return $cap->current_count < $cap->capacity;
            });

        return view('employee.index', [
            'user' => $user,
            'capacities' => $capacities,
            'alreadyBooked' => false,
            'booking' => null
        ]);
    }

    /**
     * Show available dates based on employee level.
     */
    /* public function index()
    {
        $user = Auth::user();
        $levelId = $user->level_id;

        $capacities = CapacityLevel::with('adcDate.centre')
            ->where('level_id', $levelId)
            ->get()
            ->filter(function ($capacity) {
                $current_count = Booking::where('adc_date_id', $capacity->adc_date_id)
                    ->whereHas('user', function ($q) use ($capacity) {
                        $q->where('level_id', $capacity->level_id);
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

        $capacity = CapacityLevel::with('adcDate.centre', 'level')
            ->findOrFail($request->capacity_id);
        $date = $capacity->adcDate;
        $current_count = Booking::where('adc_date_id', $capacity->adc_date_id)->count();
        $capacity->current_count = $current_count;

        return view('employee.bookings.preview', [
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

            $user->adc_centre_id = $capacity->adcDate->adc_centre_id;
            $user->save();

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
