@extends('admin.layouts.app')

@section('title', 'ADC Dates')
@section('page-title', 'ADC Dates')
@section('page-subtitle', 'Manage ADC scheduling')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex items-center gap-2">
        <select name="centre_id" class="px-3 py-2 border rounded-lg">
            <option value="">All centres</option>
            @foreach($AdcCentres as $c)
                <option value="{{ $c->id }}" @if((string)($centreId ?? '') === (string)$c->id) selected @endif>{{ $c->name }}</option>
            @endforeach
        </select>

        <input type="text" name="q" placeholder="Search centre..." value="{{ $q ?? '' }}"
               class="px-3 py-2 rounded-lg border border-gray-300 w-64">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
    </form>

    <a href="{{ route('admin.adc-dates.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg">+ Add Date</a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-left text-xs text-gray-600 uppercase">
            <tr>
                <th class="px-6 py-3">Date</th>
                <th class="px-6 py-3">Centre</th>
                <th class="px-6 py-3">Levels</th>
                <th class="px-6 py-3">Capacity</th>
                <th class="px-6 py-3">Allocated</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($dates as $d)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-3 font-medium">{{ $d->date->toDateString() }}</td>
                <td class="px-6 py-3">{{ $d->centre->name }}</td>
                <td class="px-6 py-3">
                    @if(is_array($d->eligible_levels) && count($d->eligible_levels))
                        <div class="flex flex-wrap gap-2">
                            @foreach($d->eligible_levels as $lvl)
                                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">{{ $lvl }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-gray-500 text-sm">All Levels</span>
                    @endif
                </td>
                <td class="px-6 py-3">{{ $d->capacity }}</td>
                <td class="px-6 py-3">{{ $d->current_count }}</td>
                <td class="px-6 py-3 text-right flex justify-end gap-2">
                    <a href="{{ route('admin.adc-dates.show', $d) }}" class="px-3 py-1.5 bg-gray-200 rounded text-sm">View</a>
                    <a href="{{ route('admin.adc-dates.edit', $d) }}" class="px-3 py-1.5 bg-yellow-400 text-white rounded text-sm">Edit</a>

                    <form action="{{ route('admin.adc-dates.destroy', $d) }}" method="POST" onsubmit="return confirm('Delete this ADC date?');">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1.5 bg-red-600 text-white rounded text-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No ADC dates found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $dates->links('pagination::tailwind') }}
    </div>
</div>
@endsection