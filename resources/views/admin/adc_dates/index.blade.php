@extends('admin.layouts.app')

@section('title', 'ADC Dates')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold">ADC Dates</h2>
    <a href="{{ route('admin.adc-dates.create') }}"
        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        + Add Date
    </a>
</div>
<form method="GET" action="{{ route('admin.adc-dates.index') }}"
    class="mb-6 bg-white p-4 shadow rounded-lg">
    <div class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">From</label>
            <input type="date" name="from" value="{{ $from }}"
                class="border px-3 py-2 rounded-lg w-40">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">To</label>
            <input type="date" name="to" value="{{ $to }}"
                class="border px-3 py-2 rounded-lg w-40">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Centre</label>
            <select name="centre_id" class="border px-3 py-2 rounded-lg w-48">
                <option value="">All Centres</option>
                @foreach($AdcCentres as $centre)
                <option value="{{ $centre->id }}" {{ $centreId == $centre->id ? 'selected' : '' }}>
                    {{ $centre->city }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Level</label>
            <select name="level" class="border px-3 py-2 rounded-lg w-32">
                <option value="">All</option>
                @foreach($levels as $level)
                <option value="{{ $level }}" {{ $levelName == $level ? 'selected' : '' }}>
                    {{ $level }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Apply
            </button>
        </div>
    </div>
</form>
<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left text-gray-700 uppercase text-sm">
                <th class="px-4 py-3 border-b">Date</th>
                <th class="px-4 py-3 border-b">Centre</th>
                <th class="px-4 py-3 border-b">Level Capacities</th>
                <th class="px-4 py-3 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dates as $date)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-4">
                    <div class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($date->date)->format('d M Y') }}
                    </div>
                </td>

                <td class="px-4 py-4">
                    <div class="text-gray-700">{{ $date->centre->name }}</div>
                    <div class="text-sm text-gray-500">{{ $date->centre->city }}</div>
                </td>

                <td class="px-4 py-4">
                    @foreach($date->capacities as $cap)
                    @php
                    $current = $cap->current_count;
                    $max = $cap->capacity;
                    $remaining = $max - $current;

                    if ($current >= $max) {
                    $status = 'Full';
                    $color = 'bg-red-100 text-red-700';
                    } elseif ($remaining <= 3) {
                        $status='Almost Full' ;
                        $color='bg-yellow-100 text-yellow-700' ;
                        } else {
                        $status='Available' ;
                        $color='bg-green-100 text-green-700' ;
                        }
                        @endphp
                        <div class="mb-2 p-2 border rounded-lg bg-gray-50">
                        <div class="flex justify-between mb-1">
                            <div>
                                <span class="font-semibold text-gray-800">
                                    Level: {{ $cap->level }}
                                </span>
                                <span class="ml-2 text-sm text-gray-600">
                                    ({{ $current }} / {{ $max }})
                                </span>
                            </div>

                            <span class="px-2 py-1 text-xs rounded {{ $color }}">
                                {{ $status }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-500">
                            Remaining Seats: {{ $remaining }}
                        </div>
</div>
@endforeach
</td>
<td class="px-4 py-4 flex gap-2">
    <a href="{{ route('admin.adc-dates.edit', $date->id) }}"
        class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
        Edit
    </a>
    <form action="{{ route('admin.adc-dates.destroy', $date->id) }}"
        method="POST"
        onsubmit="return confirm('Are you sure you want to delete this date and all its level capacities?');">
        @csrf
        @method('DELETE')

        <button class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">
            Delete
        </button>
    </form>
</td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center py-6 text-gray-500">
        No dates found.
    </td>
</tr>
@endforelse
</tbody>
</table>
</div>
<div class="mt-6">
    {{ $dates->links() }}
</div>
@endsection