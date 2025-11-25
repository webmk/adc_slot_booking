<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\AdcCentre;
use App\Models\AdcDate;
use App\Models\CapacityLevel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dynamicReport()
    {
        $centres = AdcCentre::orderBy('city')->get();
        $levels  = User::levels();

        return view('admin.reports.index', compact('centres', 'levels'));
    }
    public function fetchData(Request $request)
    {
        $query = Booking::with(['user', 'adcDate.centre']);

        if ($request->from) {
            $query->whereHas('adcDate', function ($q) use ($request) {
                $q->whereDate('date', '>=', $request->from);
            });
        }

        if ($request->to) {
            $query->whereHas('adcDate', function ($q) use ($request) {
                $q->whereDate('date', '<=', $request->to);
            });
        }

        if ($request->centre_id) {
            $query->whereHas(
                'adcDate',
                fn($q) =>
                $q->where('adc_centre_id', $request->centre_id)
            );
        }

        if ($request->level) {
            $query->whereHas(
                'user',
                fn($q) =>
                $q->where('level', $request->level)
            );
        }

        if ($request->status === 'not_booked') {

            $bookedIds = Booking::pluck('user_id');

            $notBooked = User::whereNotIn('id', $bookedIds)
                ->when($request->level, fn($q) => $q->where('level', $request->level))
                ->when($request->centre_id, fn($q) => $q->where('adc_centre_id', $request->centre_id))
                ->get()
                ->map(function ($u) {
                    return [
                        'name'     => $u->name,
                        'cpf_no'   => $u->cpf_no ?? '-',
                        'level'    => $u->level ?? '-',
                        'location' => $u->location ?? '-',
                        'centre'   => optional($u->centre)->city ?? '-',
                        'date'     => '-',
                        'booked_at' => '-'
                    ];
                });

            return response()->json(['data' => $notBooked]);
        }

        $data = $query->get()->map(function ($b) {
            return [
                'name'     => $b->user->name,
                'cpf_no'   => $b->user->cpf_no ?? '-',
                'level'    => $b->user->level ?? '-',
                'location' => $b->user->location ?? '-',
                'centre'   => $b->adcDate->centre->city,
                'date'     => $b->adcDate->date->format('d M Y'),
                'booked_at' => $b->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
