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

        //$user->notify(new BookingUpdatedNotification($booking));
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
