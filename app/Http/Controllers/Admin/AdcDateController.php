<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdcCentre;
use App\Models\AdcDate;
use App\Models\CapacityLevel;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdcDateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $centreId = $request->query('centre_id');
        $levelId = $request->query('level_id');

        $query = AdcDate::with(['centre', 'capacities.level'])
            ->orderBy('date', 'asc');

        if ($from) {
            $query->whereDate('date', '>=', $from);
        }

        if ($to) {
            $query->whereDate('date', '<=', $to);
        }

        if ($centreId) {
            $query->where('adc_centre_id', $centreId);
        }

        if ($levelId) {
            $query->whereHas('capacities', function ($q) use ($levelId) {
                $q->where('level_id', $levelId);
            });
        }
        $dates = $query->paginate(10)->withQueryString();
        $AdcCentres = AdcCentre::orderBy('city')->get();
        $levels = Level::orderBy('name')->get();

        return view('admin.adc_dates.index', compact('dates', 'AdcCentres', 'levels', 'from', 'to', 'centreId', 'from', 'to', 'levelId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $centres = AdcCentre::orderBy('city')->get();
        //$selectedCentre = $request->query('centre_id');
        $levels = Level::orderBy('name')->get();
        return view('admin.adc_dates.create', compact('centres', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date'            => 'required|date',
            'adc_centre_id'   => 'required|exists:adc_centres,id',
            'capacities'      => 'required|array',
            'capacities.*'    => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $adcDate = AdcDate::create([
                'date'          => $request->date,
                'adc_centre_id' => $request->adc_centre_id,
            ]);

            foreach ($request->capacities as $levelId => $capacity) {
                if (!empty($capacity) && intval($capacity) > 0) {
                    CapacityLevel::create([
                        'adc_date_id'  => $adcDate->id,
                        'level_id'     => $levelId,
                        'capacity' => intval($capacity),
                    ]);
                }
            }
        });

        return redirect()->route('admin.adc-dates.index')
            ->with('success', 'ADC Date created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(AdcDate $adcDate)
    {
        $adcDate->load('centre', 'bookings');
        return view('admin.adc_dates.show', ['date' => $adcDate]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $adcDate = AdcDate::with('capacities')->findOrFail($id);
        $centres = AdcCentre::orderBy('city')->get();
        $levels  = Level::orderBy('name')->get();
        $capacityMap = $adcDate->capacities->pluck('capacity', 'level_id');

        return view('admin.adc_dates.edit', compact('adcDate', 'centres', 'levels', 'capacityMap'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date'            => 'required|date',
            'adc_centre_id'   => 'required|exists:adc_centres,id',
            'capacities'      => 'required|array',
            'capacities.*'    => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {
            $adcDate = AdcDate::findOrFail($id);
            $adcDate->update([
                'date'          => $request->date,
                'adc_centre_id' => $request->adc_centre_id,
            ]);

            CapacityLevel::where('adc_date_id', $id)->delete();
            foreach ($request->capacities as $levelId => $capacity) {
                if ($capacity > 0) {
                    CapacityLevel::create([
                        'adc_date_id'  => $id,
                        'level_id'     => $levelId,
                        'capacity' => $capacity,
                    ]);
                }
            }
        });

        return redirect()->route('admin.adc-dates.index')
            ->with('success', 'ADC Date updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            CapacityLevel::where('adc_date_id', $id)->delete();
            AdcDate::where('id', $id)->delete();
        });

        return redirect()->route('admin.adc-dates.index')
            ->with('success', 'ADC Date deleted successfully.');
    }
}
