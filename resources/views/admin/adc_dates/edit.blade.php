@extends('admin.layouts.app')

@section('title', 'Edit ADC Date')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Edit ADC Date</h2>

<form method="POST" action="{{ route('admin.adc-dates.update', $adcDate->id) }}"
    class="bg-white p-6 rounded-lg shadow w-full max-w-3xl">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block font-medium mb-1">Date</label>
        <input type="date" name="date"
            value="{{ \Carbon\Carbon::parse($adcDate->date)->format('Y-m-d') }}"
            class="border px-3 py-2 rounded-lg w-full" required>
    </div>
    <div class="mb-4">
        <label class="block font-medium mb-1">Centre</label>
        <select name="adc_centre_id" class="border px-3 py-2 rounded-lg w-full" required>
            @foreach($centres as $centre)
            <option value="{{ $centre->id }}" {{ $centre->id == $adcDate->adc_centre_id ? 'selected' : '' }}>
                {{ $centre->city }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="mb-6">
        <label class="block font-medium mb-2">Level-wise Capacity</label>
        @foreach($levels as $level)
        <div class="flex items-center gap-4 mb-2">
            <span class="w-20 font-medium">{{ $level->name }}</span>

            <input type="number" min="0" class="border px-3 py-2 rounded w-32"
                name="capacities[{{ $level->id }}]"
                value="{{ $capacityMap[$level->id] ?? '' }}"
                placeholder="Capacity">
        </div>
        @endforeach
    </div>
    <div class="flex gap-3 pt-4">
        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">Update</button>
        <a href="{{ route('admin.adc-dates.index') }}"
            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Cancel
        </a>
    </div>
</form>
@endsection