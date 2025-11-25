@extends('admin.layouts.master')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-6">Booking Summary Report</h1>

    {{-- CHART 1: BOOKINGS PER CENTRE --}}
    <div class="bg-white p-5 rounded shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Bookings per ADC Centre</h2>
        <canvas id="centreChart" height="120"></canvas>
    </div>

    {{-- CHART 2: BOOKINGS PER LEVEL --}}
    <div class="bg-white p-5 rounded shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Bookings per Employee Level</h2>
        <canvas id="levelChart" height="120"></canvas>
    </div>

    {{-- CHART 3: BOOKINGS OVER TIME --}}
    <div class="bg-white p-5 rounded shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Bookings by Date</h2>
        <canvas id="dateChart" height="120"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// =============================
// CHART 1: BOOKINGS PER CENTRE
// =============================
new Chart(document.getElementById('centreChart'), {
    type: 'bar',
    data: {
        labels: @json($centresJson),
        datasets: [{
            label: 'Bookings',
            data: @json($centreCountsJson),
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});

// =============================
// CHART 2: BOOKINGS PER LEVEL
// =============================
new Chart(document.getElementById('levelChart'), {
    type: 'doughnut',
    data: {
        labels: @json($levelNamesJson),
        datasets: [{
            label: 'Bookings',
            data: @json($levelCountsJson),
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});

// =============================
// CHART 3: BOOKINGS OVER TIME
// =============================
new Chart(document.getElementById('dateChart'), {
    type: 'line',
    data: {
        labels: @json($dateLabelsJson),
        datasets: [{
            label: 'Bookings',
            data: @json($dateCountsJson),
            fill: false,
            tension: 0.3,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection