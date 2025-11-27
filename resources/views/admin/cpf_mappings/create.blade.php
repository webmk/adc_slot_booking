@extends('admin.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Map CPF Numbers to a Centre</h1>
    <div class="bg-white p-6 shadow rounded">
        <form action="{{ route('admin.cpf-mappings.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="font-semibold">Select ADC Centre</label>
                <select name="adc_centre_id"
                        class="border p-2 rounded w-full" required>
                    <option value="">-- Select Centre --</option>
                    @foreach($centres as $c)
                        <option value="{{ $c->id }}">{{ $c->city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="font-semibold">Enter CPF Numbers</label>
                <textarea name="cpf_list" rows="6"
                          class="border p-2 rounded w-full"
                          placeholder="Enter multiple CPF numbers, separated by comma or new lines"
                          required></textarea>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">
                Save Mapping
            </button>
        </form>
    </div>
</div>
@endsection