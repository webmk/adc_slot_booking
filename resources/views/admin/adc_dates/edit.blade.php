@extends('admin.layouts.app')

@section('title', 'Edit ADC Date')
@section('page-title', 'Edit ADC Date')
@section('page-subtitle', $date->date->toDateString())

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-2xl">
    <form action="{{ route('admin.adc-dates.update', $date) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700">Centre *</label>
            <select name="adc_centre_id" required class="mt-1 block w-full rounded-lg border px-3 py-2">
                <option value="">Select centre</option>
                @foreach($centres as $c)
                    <option value="{{ $c->id }}" @if($date->adc_centre_id == $c->id) selected @endif>{{ $c->name }} — {{ $c->city }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Date *</label>
            <input type="date" name="date" value="{{ old('date', $date->date->toDateString()) }}" required
                   class="mt-1 block w-full rounded-lg border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Max capacity *</label>
            <input type="number" name="capacity" value="{{ old('capacity', $date->capacity) }}" required min="0"
                   class="mt-1 block w-full rounded-lg border px-3 py-2">
            <p class="text-xs text-gray-500 mt-1">Currently allocated: {{ $date->current_count }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Eligible levels</label>
            <p class="text-xs text-gray-500 mb-1">Leave empty for all levels.</p>

            @php
                $allLevels = ['E5','E6','E7','E8'];
                $selected = old('eligible_levels', $date->eligible_levels ?? []);
            @endphp
            <div class="grid grid-cols-2 gap-2">
                @foreach($allLevels as $lvl)
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="eligible_levels[]" value="{{ $lvl }}"
                               @if( in_array($lvl, $selected) ) checked @endif
                               class="rounded border-gray-300">
                        <span class="text-sm">{{ $lvl }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.adc-dates.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Changes</button>
        </div>
    </form>
</div>
@endsection