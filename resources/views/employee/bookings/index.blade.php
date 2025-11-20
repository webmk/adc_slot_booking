@extends('employee.layouts.app')

@section('title', 'Available ADC Dates')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Available ADC Dates</h2>

@if($capacities->isEmpty())
    <div class="p-4 bg-red-100 text-red-700 rounded">
        No available dates for your level.
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($capacities as $cap)
            @php
                $current = $cap->current_count;
                $max = $cap->capacity;
                $date = $cap->adcDate;
                $centre = $date->centre;
            @endphp

            <div class="p-6 rounded-lg shadow bg-white">
                <h3 class="text-lg font-semibold mb-2">
                    {{ $date->date->format('d M, Y') }}
                </h3>
                <p class="text-gray-600 text-sm">
                    <strong>Centre:</strong> {{ $centre->city }}
                </p>
                <p class="text-gray-600 text-sm">
                    <strong>Level Capacity:</strong> {{ $current }} / {{ $max }}
                </p>

                @if($current < $max)
                    <form action="{{ route('employee.bookings.preview') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="capacity_id" value="{{ $cap->id }}">

                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Select
                        </button>
                    </form>
                @else
                    <span class="inline-block mt-4 px-3 py-1 text-sm bg-red-200 text-red-700 rounded">
                        Full
                    </span>
                @endif
            </div>
        @endforeach
    </div>
@endif
@endsection