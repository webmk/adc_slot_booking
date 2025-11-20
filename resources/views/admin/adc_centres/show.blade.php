@extends('admin.layouts.app')

@section('title', $centre->name)
@section('page-title', $centre->name)
@section('page-subtitle', 'Centre details & associated ADC dates')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">{{ $centre->name }}</h3>
        <p class="mb-2">
            <span class="font-semibold">City:</span> {{ $centre->city ?? '—' }}
        </p>
        <p class="mb-2">
            <span class="font-semibold">Address:</span><br>
            <span class="text-gray-600 text-sm">{{ $centre->address ?? '—' }}</span>
        </p>
        <div class="mt-4 flex gap-3">
            <a href="{{ route('admin.adc-centres.edit', $centre) }}"
               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                Edit
            </a>
            <a href="{{ route('admin.adc-dates.index', ['centre_id' => $centre->id]) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                View ADC Dates
            </a>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="font-semibold text-lg mb-4">Upcoming ADC Dates</h3>
        @if($centre->dates->count())
            <ul class="space-y-3">
                @foreach($centre->dates as $d)
                    <li class="border p-3 rounded-lg hover:bg-gray-50">
                        <div class="font-medium">{{ $d->date->toDateString() }}</div>
                        <div class="text-gray-500 text-sm">
                            Capacity: {{ $d->current_count }} / {{ $d->max_capacity }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600 text-sm">No dates available for this centre.</p>
        @endif
    </div>
</div>
@endsection