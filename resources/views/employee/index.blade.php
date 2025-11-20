@extends('employee.layouts.app')

@section('title', 'Employee Information')

@section('content')

<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">Employee Information</h2>

    {{-- Information Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Name</label>
            <input type="text"
                value="{{ $user->name }}"
                class="w-full bg-gray-100 text-gray-700 px-3 py-2 rounded border"
                readonly>
        </div>

        <!-- <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">CPF No.</label>
            <input type="text"
                   value="{{ $user->cpf_no }}"
                   class="w-full bg-gray-100 text-gray-700 px-3 py-2 rounded border"
                   readonly>
        </div> -->

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Level</label>
            <input type="text"
                value="{{ $user->level->name ?? 'N/A' }}"
                class="w-full bg-gray-100 text-gray-700 px-3 py-2 rounded border"
                readonly>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
            <input type="text"
                value="{{ $user->email ?? 'N/A' }}"
                class="w-full bg-gray-100 text-gray-700 px-3 py-2 rounded border"
                readonly>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Mobile</label>
            <input type="text"
                value="{{ $user->mobile ?? 'N/A' }}"
                class="w-full bg-gray-100 text-gray-700 px-3 py-2 rounded border"
                readonly>
        </div>
        <!-- <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Place of Posting</label>
            <input type="text"
                   value="{{ $user->posting_location }}"
                   class="w-full bg-gray-100 text-gray-700 px-3 py-2 rounded border"
                   readonly>
        </div> -->

        <!-- <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-600 mb-1">ADC Location (Assigned)</label>
            <input type="text"
                value="{{ $user->adcCentre->city ?? 'Not Assigned' }}"
                class="w-full bg-gray-200 text-gray-700 px-3 py-2 rounded border cursor-not-allowed"
                readonly>
        </div> -->
    </div>

    {{-- ADC Date Selection --}}
    <div class="mt-8">
        @if(count($capacities))
        <h3 class="text-xl font-semibold mb-3">Select ADC Date</h3>
        <form method="POST" action="{{ route('employee.bookings.preview') }}">
            @csrf
            @method('POST')
            <select name="capacity_id"
                class="w-full px-3 py-2 border rounded-lg bg-white">
                @foreach($capacities as $cap)

                @php
                $isFull = $cap->current_count >= $cap->capacity;
                @endphp

                <option value="{{ $cap->id }}" @if($isFull) disabled @endif>
                    {{ $cap->AdcDate->date->toFormattedDateString() }}
                    - {{ $cap->AdcDate->centre->city }}

                    @if($isFull)
                    (Full)
                    @else
                    ({{ $cap->current_count }} / {{ $cap->capacity }} booked)
                    @endif
                </option>

                @endforeach
            </select>
            <button
                class="mt-4 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Continue
            </button>
        </form>
        @else
        @if($alreadyBooked)
        <div class="mt-6">
            <p><strong>ADC Centre:</strong> {{ $booking->adcDate->centre->city }}</p>
            <p><strong>Date:</strong> {{ $booking->adcDate->date->format('d M Y') }}</p>
        </div>

        <div class="mt-6 p-4 bg-gray-100 rounded text-gray-600">
            Date selection is disabled because you have already booked a slot.
        </div>

        @else
        <p class="text-red-600 text-sm">No ADC dates available for your level.</p>
        @endif
        @endif
    </div>
</div>
@endsection