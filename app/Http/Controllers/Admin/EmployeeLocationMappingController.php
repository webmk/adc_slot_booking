<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdcCentre;
use App\Models\EmployeeLocationMapping;
use Illuminate\Http\Request;

class EmployeeLocationMappingController extends Controller
{
    public function index()
    {
        $mappings = EmployeeLocationMapping::with('centre')
            ->orderBy('employee_location')
            ->paginate(20);

        return view('admin.location_mappings.index', compact('mappings'));
    }

    public function create()
    {
        $centres = AdcCentre::orderBy('city')->get();
        return view('admin.location_mappings.create', compact('centres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_location' => 'required|string',
            'adc_centre_id'     => 'required|exists:adc_centres,id'
        ]);

        EmployeeLocationMapping::create($request->all());

        return redirect()->route('admin.location-mappings.index')
            ->with('success', 'Location Mapping created.');
    }

    public function edit(EmployeeLocationMapping $locationMapping)
    {
        $centres = AdcCentre::orderBy('city')->get();
        return view('admin.location_mappings.edit', compact('locationMapping', 'centres'));
    }

    public function update(Request $request, EmployeeLocationMapping $locationMapping)
    {
        $request->validate([
            'employee_location' => 'required|string',
            'adc_centre_id'     => 'required|exists:adc_centres,id',
        ]);

        $locationMapping->update($request->all());

        return redirect()->route('admin.location-mappings.index')
            ->with('success', 'Location Mapping updated.');
    }

    public function destroy(EmployeeLocationMapping $locationMapping)
    {
        $locationMapping->delete();

        return back()->with('success', 'Mapping deleted.');
    }
}