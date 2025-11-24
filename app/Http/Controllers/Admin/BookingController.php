<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdcCentre;
use App\Models\AdcDate;
use App\Models\Booking;
use App\Models\CapacityLevel;
use App\Notifications\BookingUpdatedNotification;
use Illuminate\Http\Request;
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
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
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

        $user = $booking->user;
        $newDateId = $request->adc_date_id;
        $capacity = CapacityLevel::where('adc_date_id', $newDateId)
            ->where('level', $user->level)
            ->first();

        if (!$capacity) {
            return back()->withErrors(['adc_date_id' => 'Selected date is not available for this level.']);
        }
        if ($capacity->current_count >= $capacity->max_capacity) {
            return back()->withErrors(['adc_date_id' => 'Selected date is FULL for this level.']);
        }

        DB::transaction(function () use ($booking, $newDateId, $capacity, $user) {
            $booking->update([
                'adc_date_id' => $newDateId,
            ]);

            $user->adc_centre_id = $capacity->adcDate->adc_centre_id;
            $user->save();

            //$user->notify(new BookingUpdatedNotification($booking));
        });

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }
    public function destroy(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            $booking->delete();
            $booking->user->update(['adc_centre_id' => null]);
        });

        return back()->with('success', 'Booking deleted successfully.');
    }
}
