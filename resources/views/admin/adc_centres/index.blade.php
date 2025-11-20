@extends('admin.layouts.app')

@section('title', 'ADC Centres')
@section('page-title', 'ADC Centres')
@section('page-subtitle', 'List of all ADC Centres')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex items-center gap-2">
        <input type="text" name="q" placeholder="Search centres..." value="{{ $q ?? '' }}"
               class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 w-64">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Search</button>
    </form>
    <a href="{{ route('admin.adc-centres.create') }}"
       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        + Add Centre
    </a>
</div>
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full text-sm">
        <thead>
        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-xs">
            <th class="px-6 py-3">Name</th>
            <th class="px-6 py-3">City</th>
            <th class="px-6 py-3">Address</th>
            <th class="px-6 py-3 text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($adcCentres as $c)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">
                    <a href="{{ route('admin.adc-centres.show', $c) }}"
                       class="text-blue-600 hover:underline">
                        {{ $c->name }}
                    </a>
                </td>
                <td class="px-6 py-3">{{ $c->city }}</td>
                <td class="px-6 py-3 text-gray-600">
                    {{ Str::limit($c->address, 50) }}
                </td>
                <td class="px-6 py-3 text-right flex justify-end gap-2">
                    <a href="{{ route('admin.adc-centres.edit', $c) }}"
                       class="px-3 py-1.5 text-sm bg-yellow-500 text-white
                              rounded-lg hover:bg-yellow-600">
                        Edit
                    </a>
                    <form action="{{ route('admin.adc-centres.destroy', $c) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this centre?');">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1.5 text-sm bg-red-600 text-white
                                       rounded-lg hover:bg-red-700">
                            Delete
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-3 text-center text-gray-500">
                    No centres found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $adcCentres->links('pagination::tailwind') }}
    </div>
</div>
@endsection