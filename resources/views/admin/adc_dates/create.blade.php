@extends('admin.layouts.app')

@section('title', 'Create ADC Date')
@section('page-title', 'Create ADC Date')
@section('page-subtitle', 'Add a date and capacity')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-2xl">
    <form action="{{ route('admin.adc-dates.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Centre *</label>
            <select name="adc_centre_id" required class="mt-1 block w-full rounded-lg border px-3 py-2">
                <option value="">Select centre</option>
                @foreach($centres as $c)
                    <option value="{{ $c->id }}" @if((string)($selectedCentre ?? '') === (string)$c->id) selected @endif>{{ $c->name }} — {{ $c->city }}</option>
                @endforeach
            </select>
            @error('adc_centre_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Date *</label>
            <input type="date" name="date" value="{{ old('date') }}" required
                   class="mt-1 block w-full rounded-lg border px-3 py-2">
            @error('date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Max capacity *</label>
            <input type="number" name="capacity" value="{{ old('capacity', 50) }}" required min="1"
                   class="mt-1 block w-full rounded-lg border px-3 py-2">
            @error('capacity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Eligible levels</label>
            <p class="text-xs text-gray-500 mb-1">Select which employee levels can attend (leave empty for all levels).</p>
            <div class="grid grid-cols-2 gap-2">
                @php
                    $allLevels = ['E5','E6','E7','E8']; // if you have a Levels table you can pass it from controller
                @endphp

                @foreach($allLevels as $lvl)
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="eligible_levels[]" value="{{ $lvl }}"
                               @if( in_array($lvl, old('eligible_levels', [])) ) checked @endif
                               class="rounded border-gray-300">
                        <span class="text-sm">{{ $lvl }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.adc-dates.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Create Date</button>
        </div>
    </form>
</div>
@endsection