@extends('admin.layouts.app')

@section('content')
<h2 class="text-xl font-semibold mb-4">Edit Mapping</h2>

<form method="POST" action="{{ route('admin.location-mappings.update', $locationMapping->id) }}"
      class="bg-white p-5 rounded shadow">
    @csrf @method('PUT')

    <div class="mb-4">
        <label class="block mb-1 font-medium">Employee Location</label>
        <input type="text" name="employee_location" 
               value="{{ $locationMapping->employee_location }}"
               class="border px-3 py-2 rounded w-full" required>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">ADC Centre</label>
        <select name="adc_centre_id" class="border px-3 py-2 rounded w-full" required>
            @foreach($centres as $centre)
                <option value="{{ $centre->id }}"
                        @if($centre->id == $locationMapping->adc_centre_id) selected @endif>
                    {{ $centre->city }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
</form>
@endsection