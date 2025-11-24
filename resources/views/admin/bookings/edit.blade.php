@extends('admin.layouts.app')

@section('title', 'Edit Booking')

@section('content')
<h2 class="text-2xl font-semibold mb-6">Edit Booking</h2>
<div class="bg-white p-6 rounded shadow max-w-xl">
    <h3 class="text-lg font-semibold mb-4">Employee Info</h3>
    <p><strong>Name:</strong> {{ $booking->user->name }}</p>
    <p><strong>CPF:</strong> {{ $booking->user->cpf_no }}</p>
    <p><strong>Level:</strong> {{ $booking->user->level ?? '-' }}</p>
    <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}"
        class="mt-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
        <label class="font-medium mb-2 block">Select New Date</label>
        <select name="adc_date_id" class="border p-2 w-full rounded mb-4">
            @foreach($dates as $d)
            <option value="{{ $d->id }}" {{ $booking->adc_date_id == $d->id ? 'selected' : '' }}>
                {{ $d->date->format('d M Y') }} — {{ $d->centre->city }}
            </option>
            @endforeach
        </select>
        </div>
        <div class="flex gap-3 pt-4">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">
            Update Booking
        </button>
        <a href="{{ route('admin.bookings.index') }}"
            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Cancel
        </a>
        </div>
    </form>
</div>
@endsection