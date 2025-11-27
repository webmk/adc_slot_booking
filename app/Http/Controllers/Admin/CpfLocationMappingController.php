<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdcCentre;
use App\Models\CpfLocationMapping;
use Illuminate\Http\Request;

class CpfLocationMappingController extends Controller
{
    public function index(Request $request)
    {
        $searchCpf = $request->input('cpf_no');

        $mappings = CpfLocationMapping::with('centre')
            ->when($searchCpf, function ($q) use ($searchCpf) {
                $q->where('cpf_no', 'like', "%{$searchCpf}%");
            })
            ->orderBy('cpf_no')
            ->paginate(10)
            ->withQueryString();

        return view('admin.cpf_mappings.index', compact('mappings', 'searchCpf'));
    }

    public function create()
    {
        $centres = AdcCentre::orderBy('city')->get();
        return view('admin.cpf_mappings.create', compact('centres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'adc_centre_id' => 'required|exists:adc_centres,id',
            'cpf_list' => 'required|string'
        ]);

        $centreId = $request->adc_centre_id;

        $cpfs = preg_split('/[\s,]+/', trim($request->cpf_list));

        foreach ($cpfs as $cpf) {
            if ($cpf !== '') {
                CpfLocationMapping::updateOrCreate(
                    ['cpf_no' => $cpf],
                    ['adc_centre_id' => $centreId]
                );
            }
        }

        return redirect()->route('admin.cpf-mappings.index')
            ->with('success', 'CPF numbers mapped successfully.');
    }

    public function edit(CpfLocationMapping $cpfMapping)
    {
        $centres = AdcCentre::orderBy('city')->get();
        return view('admin.cpf_mappings.edit', compact('cpfMapping', 'centres'));
    }

    public function update(Request $request, CpfLocationMapping $cpfMapping)
    {
        $request->validate([
            'cpf_no' => 'required|string',
            'adc_centre_id' => 'required|exists:adc_centres,id',
        ]);

        $cpfMapping->update([
            'cpf_no' => $request->cpf_no,
            'adc_centre_id' => $request->adc_centre_id,
        ]);

        return redirect()->route('admin.cpf-mappings.index')
            ->with('success', 'Mapping updated successfully.');
    }

    public function destroy(CpfLocationMapping $cpfMapping)
    {
        $cpfMapping->delete();

        return redirect()->back()->with('success', 'Mapping deleted.');
    }
}
