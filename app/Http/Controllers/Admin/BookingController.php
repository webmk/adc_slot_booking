<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdcCentre;
use App\Models\AdcDate;
use App\Models\Booking;
use App\Models\CapacityLevel;
use App\Models\User;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $date = $request->query('date');
        $centreId = $request->query('centre_id');

        $query = Booking::with(['user', 'adcDate.centre'])
            ->orderBy('created_at', 'desc');

        if ($q) {
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('cpf_no', 'like', "%$q%");
            });
        }

        if ($date) {
            $query->whereHas('adcDate', fn($d) => $d->where('date', $date));
        }

        if ($centreId) {
            $query->whereHas('adcDate', fn($d) => $d->where('adc_centre_id', $centreId));
        }

        $bookings = $query->paginate(15)->withQueryString();
        $centres = AdcCentre::orderBy('city')->get();

        return view('admin.bookings.index', compact('bookings', 'centres', 'q', 'date', 'centreId'));
    }
    public function create()
    {
        $employees = User::orderBy('name')->get();
        $centres = AdcCentre::orderBy('city')->get();

        return view('admin.bookings.create', compact('employees', 'centres'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'adc_date_id'   => 'required|exists:adc_dates,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $adcDate = AdcDate::with('centre')->findOrFail($request->adc_date_id);

        $capacity = CapacityLevel::where('adc_date_id', $adcDate->id)
            ->where('level', $user->level)
            ->first();

        if (!$capacity) {
            return back()
                ->withErrors(['adc_date_id' => 'This date is not available for employee level: ' . $user->level])
                ->withInput();
        }

        $currentCount = Booking::where('adc_date_id', $adcDate->id)->count();
        if ($currentCount >= $capacity->capacity) {
            return back()
                ->withErrors(['adc_date_id' => 'This date is already full for that level.'])
                ->withInput();
        }

        if (Booking::where('user_id', $user->id)->exists()) {
            return back()
                ->withErrors(['user_id' => 'This employee already has a booking.'])
                ->withInput();
        }

        DB::transaction(function () use ($user, $adcDate) {

            $booking = Booking::create([
                'user_id'     => $user->id,
                'adc_date_id' => $adcDate->id,
                'created_by'  => Auth::user()->id,
            ]);

            $user->notify(new BookingCreatedNotification($booking));
            \Log::info('ADMIN_BOOKING_CREATED', [
                'admin'   => Auth::user()->cpf_no,
                'user'    => $user->cpf_no,
                'date'    => $adcDate->date,
                'centre'  => $adcDate->centre->city,
                'ip'      => request()->ip(),
            ]);
        });

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking created successfully.');
    }


    public function edit(Booking $booking)
    {
        $userLevel = $booking->user->level;

        $dates = AdcDate::with(['centre', 'capacities' => function ($q) use ($userLevel) {
            $q->where('level', $userLevel);
        }])
            ->whereHas('capacities', function ($q) use ($userLevel) {
                $q->where('level', $userLevel);
            })
            ->orderBy('date')
            ->get();

        return view('admin.bookings.edit', compact('booking', 'dates'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'adc_date_id' => 'required|exists:adc_dates,id',
        ]);

        $user       = $booking->user;
        $newDateId  = $request->adc_date_id;

        /* $capacity = CapacityLevel::where('adc_date_id', $newDateId)
            ->where('level', $user->level)
            ->first();

         if (!$capacity) {
            return back()->withErrors([
                'adc_date_id' => 'Selected date is not available for this level.'
            ]);
        }

        $currentCount = Booking::where('adc_date_id', $newDateId)->count();

        if ($currentCount >= $capacity->capacity) {
            return back()->withErrors([
                'adc_date_id' => 'Selected date is FULL for this level.'
            ]);
        } */
        DB::transaction(function () use ($booking, $newDateId) {
            $booking->update([
                'adc_date_id' => $newDateId,
            ]);
        });

        $user->notify(new BookingUpdatedNotification($booking));
        \Log::info('BOOKING_UPDATED', [
            'cpf'      => $booking->user->cpf_no,
            'user'     => $booking->user->name,
            'old_date' => $booking->adc_date_id,
            'new_date' => $newDateId,
            'upated_by' => auth()->user()->cpf_no,
            'ip'       => request()->ip(),
        ]);

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function getDatesForCentre($centreId)
    {
        $dates = AdcDate::with(['capacities'])
            ->where('adc_centre_id', $centreId)
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($date) {
                $totalCapacity = $date->capacities->sum('capacity');
                $bookingsCount = Booking::where('adc_date_id', $date->id)->count();

                return [
                    'id' => $date->id,
                    'date' => $date->date->format('d M Y'),
                    'available_seats' => $totalCapacity - $bookingsCount . ' slots',
                ];
            });

        return response()->json($dates);
    }

    public function searchEmployee(Request $request)
    {
        $cpf = $request->query('cpf');

        if (!$cpf) {
            return response()->json([]);
        }

        $user = User::where('cpf_no', 'like', "%{$cpf}%")->first();

        if (!$user) {
            return response()->json(['not_found' => true]);
        }

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'cpf_no'=> $user->cpf_no,
            'level' => $user->level,
            'email' => $user->email,
        ]);
    }

    public function destroy(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            $booking->delete();
        });

        \Log::warning('BOOKING_SOFT_DELETED', [
            'booking_id' => $booking->id,
            'cpf'        => $booking->user->cpf_no,
            'user'       => $booking->user->name,
            'centre'     => $booking->adcDate->centre->city,
            'date'       => $booking->adcDate->date->format('Y-m-d'),
            'deleted_by' => auth()->user()->cpf_no,
            'ip'         => request()->ip(),
        ]);

        return back()->with('success', 'Booking deleted successfully.');
    }
}
