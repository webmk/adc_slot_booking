@extends('admin.layouts.app')

@section('title', 'Create ADC Date')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Create ADC Date</h2>
<form method="POST" action="{{ route('admin.adc-dates.store') }}"
      class="bg-white p-6 rounded-lg shadow w-full max-w-3xl">
    @csrf
    <div class="mb-4">
        <label class="block font-medium mb-1">Date</label>
        <input type="date" name="date" class="border px-3 py-2 rounded-lg w-full" required>
    </div>
    <div class="mb-4">
        <label class="block font-medium mb-1">Centre</label>
        <select name="adc_centre_id" class="border px-3 py-2 rounded-lg w-full" required>
            <option value="">Choose Centre</option>
            @foreach($centres as $centre)
                <option value="{{ $centre->id }}">
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
                       name="capacities[{{ $level->id }}]" placeholder="Capacity">
            </div>
        @endforeach
    </div>
    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">Create</button>
</form>
@endsection