@extends('admin.layouts.app')

@section('title', 'Manage Bookings')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold">Employee Bookings</h2>
</div>

<form class="bg-white p-4 shadow rounded mb-6 flex flex-wrap gap-4">
    <div>
        <label>Name/Email</label>
        <input type="text" name="q" value="{{ $q }}" class="border p-2 rounded">
    </div>
    <div>
        <label>Date</label>
        <input type="date" name="date" value="{{ $date }}" class="border p-2 rounded">
    </div>
    <div>
        <label>Centre</label>
        <select name="centre_id" class="border p-2 rounded">
            <option value="">All</option>
            @foreach($centres as $c)
            <option value="{{ $c->id }}" {{ $centreId == $c->id ? 'selected' : '' }}>
                {{ $c->city }}
            </option>
            @endforeach
        </select>
    </div>
    <button class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
</form>
<div class="bg-white shadow rounded overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100 text-left text-sm uppercase">
                <th class="p-3">Employee</th>
                <th class="p-3">Email</th>
                <th class="p-3">Level</th>
                <th class="p-3">Date</th>
                <th class="p-3">Centre</th>
                <th class="p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $b->user->name }}</td>
                <td class="p-3">{{ $b->user->email }}</td>
                <td class="p-3">{{ $b->user->level->name ?? '-' }}</td>
                <td class="p-3">
                    {{ $b->adcDate->date->format('d M Y') }}
                </td>
                <td class="p-3">
                    {{ $b->adcDate->centre->city }}
                </td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.bookings.edit', $b->id) }}"
                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm">
                        Edit
                    </a>
                    <form method="POST"
                        action="{{ route('admin.bookings.destroy', $b->id) }}"
                        onsubmit="return confirm('Delete this booking?');">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">
    {{ $bookings->links() }}
</div>
@endsection