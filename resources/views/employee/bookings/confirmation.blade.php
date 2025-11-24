@extends('employee.layouts.app')

@section('title', 'Booking Confirmed')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-2xl font-semibold mb-4 text-green-700">Booking Confirmed</h2>
    <p class="text-gray-600 mb-6">
        Your slot has been successfully booked.
    </p>
    <div class="space-y-2 text-gray-700">
        <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
        <p>
            <strong>Date:</strong>
            {{ $booking->adcDate->date->format('d M, Y') }}
        </p>
        <p>
            <strong>Centre:</strong>
            {{ $booking->adcDate->centre->city }}
        </p>
        <p>
            <strong>Level:</strong>
            {{ $booking->user->level }}
        </p>
    </div>
    <div class="mt-6">
        <a href="{{ route('employee.index') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Back to Home Page
        </a>
    </div>
</div>
@endsection