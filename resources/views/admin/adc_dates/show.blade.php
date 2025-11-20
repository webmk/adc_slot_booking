@extends('admin.layouts.app')

@section('title', 'ADC Date')
@section('page-title', 'ADC Date Details')
@section('page-subtitle', $date->date->toDateString())

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-3">{{ $date->date->toDateString() }}</h3>
        <p class="text-sm text-gray-600 mb-2"><strong>Centre:</strong> {{ $date->centre->name }} ({{ $date->centre->city }})</p>
        <p class="text-sm text-gray-600 mb-2"><strong>Capacity:</strong> {{ $date->current_count }} / {{ $date->capacity }}</p>

        <p class="text-sm text-gray-600 mb-2"><strong>Eligible levels:</strong></p>
        @if(is_array($date->eligible_levels) && count($date->eligible_levels))
            <div class="flex gap-2 flex-wrap">
                @foreach($date->eligible_levels as $lvl)
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">{{ $lvl }}</span>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">All levels</p>
        @endif

        <div class="mt-4 flex gap-2">
            <a href="{{ route('admin.adc-dates.edit', $date) }}" class="px-3 py-2 bg-yellow-400 rounded text-white">Edit</a>
            <a href="{{ route('admin.adc-centres.show', $date->adc_centre_id) }}" class="px-3 py-2 bg-gray-200 rounded">View Centre</a>
        </div>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="font-semibold mb-3">Bookings ({{ $date->bookings->count() }})</h4>
        @if($date->bookings->count())
            <ul class="space-y-2">
                @foreach($date->bookings as $b)
                    <li class="border rounded p-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-medium">{{ $b->user->name ?? 'User #'.$b->user_id }}</div>
                                <div class="text-xs text-gray-500">Status: {{ $b->status }}</div>
                            </div>
                            <div class="text-sm text-gray-500">{{ optional($b->allocated_at)->toDateTimeString() }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-sm">No bookings yet for this date.</p>
        @endif
    </div>
</div>
@endsection