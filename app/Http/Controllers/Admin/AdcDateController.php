<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdcCentre;
use App\Models\AdcDate;
use Illuminate\Http\Request;

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
        $q = $request->query('q');
        $centreId = $request->query('centre_id');

        $query = AdcDate::with('centre')->orderBy('date', 'asc');

        if ($centreId) {
            $query->where('adc_centre_id', $centreId);
        }

        if ($q) {
            $query->whereHas('centre', function($q2) use ($q) {
                $q2->where('city', 'like', "%{$q}%");
            });
        }
        $dates = $query->paginate(10)->withQueryString();
        $AdcCentres = AdcCentre::orderBy('city')->get();

        return view('admin.adc_dates.index', compact('dates','AdcCentres','q','centreId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $centres = AdcCentre::orderBy('city')->get();
        $selectedCentre = $request->query('centre_id');
        return view('admin.adc_dates.create', compact('centres','selectedCentre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'adc_centre_id' => ['required','exists:adc_centres,id'],
            'date' => ['required','date','after_or_equal:today'],
            'capacity' => ['required','integer','min:1'],
            'eligible_levels' => ['nullable','array'],
            'eligible_levels.*' => ['string'],
        ]);

        $data['eligible_levels'] = $data['eligible_levels'] ?? [];
        $data['current_count'] = 0;

        AdcDate::create($data);

        return redirect()->route('admin.adc-dates.index')->with('success', 'ADC date created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdcDate $adcDate)
    {
        $adcDate->load('centre','bookings');
        return view('admin.adc_dates.show', ['date' => $adcDate]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdcDate $adcDate)
    {
        $centres = AdcCentre::orderBy('city')->get();
        return view('admin.adc_dates.edit', ['date' => $adcDate, 'centres' => $centres]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdcDate $adcDate)
    {
        $data = $request->validate([
            'adc_centre_id' => ['required','exists:adc_centres,id'],
            'date' => ['required','date','after_or_equal:today'],
            'capacity' => ['required','integer','min:0'],
            'eligible_levels' => ['nullable','array'],
            'eligible_levels.*' => ['string'],
        ]);

        // If reducing capacity below current_count, prevent accidental loss
        if ($data['capacity'] < $adcDate->current_count) {
            return back()->withErrors(['capacity' => 'Max capacity cannot be less than current allocated seats ('.$adcDate->current_count.').']);
        }

        $data['eligible_levels'] = $data['eligible_levels'] ?? [];
        $adcDate->update($data);

        return redirect()->route('admin.adc-dates.index')->with('success', 'ADC date updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdcDate $adcDate)
    {
        if ($adcDate->bookings()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete date with existing bookings. Cancel bookings first.']);
        }

        $adcDate->delete();

        return redirect()->route('admin.adc-dates.index')->with('success', 'ADC date deleted.');
    }
}
