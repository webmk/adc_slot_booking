@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
<!-- @section('page-subtitle', 'Overview & quick stats') -->

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white border rounded p-4">
      <div class="text-sm text-gray-500">Total ADC Dates</div>
      <div class="text-2xl font-bold">{{ $allDates ?? 0 }}</div>
    </div>

    <div class="bg-white border rounded p-4">
      <div class="text-sm text-gray-500">Total Centres</div>
      <div class="text-2xl font-bold">{{ $allCentres ?? 0 }}</div>
      </div>

      <div class="bg-white border rounded p-4">
      <div class="text-sm text-gray-500">Total Bookings</div>
      <div class="text-2xl font-bold">{{ $bookings ?? 0 }}</div>
      </div>
  </div>
@endsection