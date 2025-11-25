@extends('employee.layouts.app')

@section('title', 'Preview Booking')

@section('content')

<div class="max-w-2xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">Preview Your Booking</h2>
    <div class="space-y-3 text-gray-700">
        <p><strong>CPF No:</strong> {{ $user->cpf_no }}</p>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Date:</strong> {{ $date->date->format('d M, Y') }}</p>
        <p><strong>Centre:</strong> {{ $centre->city }}</p>
        <p>
            <strong>Level:</strong> {{ $capacity->level }}
        </p>
        <p>
            <strong>Capacity for your Level:</strong>
            {{ $capacity->current_count }} / {{ $capacity->capacity }}
        </p>
        <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-lg mt-4">
            <strong>Note:</strong><br>
            Once a date is selected and confirmed, it cannot be changed.
        </div>
        <form method="POST" action="{{ route('employee.bookings.store') }}" class="mt-6">
            @csrf
            <input type="hidden" name="capacity_id" value="{{ $capacity->id }}">
            <button class="px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Confirm Booking
            </button>
            <a href="{{ route('employee.index') }}" class="ml-4 text-gray-600">
                Cancel
            </a>
        </form>
    </div>
</div>
@endsection