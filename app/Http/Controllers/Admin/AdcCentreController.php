<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdcCentre;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdcCentreController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request()->query('q');
        $adcCentres = AdcCentre::when($q, function ($query, $q) {
            return $query->where('city', 'like', "%$q%")
                ->orWhere('state', 'like', "%$q%");
        })->paginate(10)->withQueryString();

        return view('admin.adc_centres.index', compact('adcCentres', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.adc_centres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        AdcCentre::create($data);

        \Log::info('ADC_CENTRE_CREATED', [
            'admin'     => Auth::user()->cpf_no,
            'centre' => $data,
            'ip'        => request()->ip(),
        ]);

        return redirect()->route('admin.adc-centres.index')->with('success', 'ADC Center created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdcCentre $adcCentre)
    {
        $adcCentre->load([
            'dates' => function ($q) {
                $q->orderBy('date');
            },
            'dates.capacities.level'
        ]);

        return view('admin.adc_centres.show', ['centre' => $adcCentre]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdcCentre $adcCentre)
    {
        return view('admin.adc_centres.edit', ['centre' => $adcCentre]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdcCentre $adcCentre)
    {
        $data = $request->validate([
            'city' => ['required', 'string', 'max:255', Rule::unique('adc_centres', 'city')->ignore($adcCentre->id)],
            'state' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $adcCentre->update($data);

        \Log::info('ADC_CENTRE_UPDATED', [
            'admin' => Auth::user()->cpf_no,
            'old'   => $adcCentre->getOriginal(),
            'new'   => $adcCentre->getDirty(),
        ]);

        return redirect()->route('admin.adc-centres.index')
            ->with('success', 'ADC Centre updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdcCentre $adcCentre)
    {
        $adcCentre->delete();
        \Log::warning('ADC_CENTRE_DELETED', [
            'admin' => Auth::user()->cpf_no,
            'centre' => $adcCentre->city,
        ]);

        return redirect()->route('admin.adc-centres.index')
            ->with('success', 'ADC Centre deleted successfully.');
    }
}
