@extends('admin.layouts.app')

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-semibold">Location → ADC Centre Mapping</h2>
    <a href="{{ route('admin.location-mappings.create') }}" 
       class="bg-green-600 text-white px-4 py-2 rounded">
       + Add Mapping
    </a>
</div>
<div class="bg-white shadow-md rounded-lg overflow-hidden">    
<table class="min-w-full text-sm">
    <thead>
        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-xs">
            <th class="px-3 py-2">Employee Location</th>
            <th class="px-3 py-2">ADC Centre</th>
            <th class="px-3 py-2 w-32">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mappings as $mapping)
        <tr class="border-b">
            <td class="px-3 py-2">{{ $mapping->employee_location }}</td>
            <td class="px-3 py-2">{{ $mapping->centre->city }}</td>
            <td class="px-3 py-2">
                <a href="{{ route('admin.location-mappings.edit', $mapping->id) }}"
                   class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                    Edit
                </a>

                <form action="{{ route('admin.location-mappings.destroy', $mapping->id) }}"
                      method="POST" class="inline"
                      onsubmit="return confirm('Delete this mapping?')">
                    @csrf @method('DELETE')
                    <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $mappings->links() }}
</div>
</div>
@endsection