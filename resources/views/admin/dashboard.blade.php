@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
<!-- @section('page-subtitle', 'Overview & quick stats') -->

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white border rounded p-4">
      <div class="text-sm text-gray-500">Total ADC Dates</div>
      <div class="text-2xl font-bold">{{ $stats['total_dates'] ?? 0 }}</div>
    </div>

    <div class="bg-white border rounded p-4">
      <div class="text-sm text-gray-500">Upcoming ADCs</div>
      <div class="text-lg">
        <ul class="list-disc pl-5 text-sm text-gray-700">
          @foreach($stats['upcoming'] ?? [] as $d)
            <li>{{ $d->date->toDateString() }} — {{ $d->centre->city }} ({{ $d->current_count }}/{{ $d->capacity }})</li>
          @endforeach
        </ul>
      </div>
    </div>

    <div class="bg-white border rounded p-4">
      <div class="text-sm text-gray-500">Quick actions</div>
      <div class="mt-4 space-y-2">
        <a href="{{ route('admin.adc-dates.index') }}" class="inline-block px-3 py-2 bg-blue-600 text-white rounded">Manage ADC Dates</a>
        <a href="#" class="inline-block px-3 py-2 border rounded">Export report</a>
      </div>
    </div>
  </div>
@endsection