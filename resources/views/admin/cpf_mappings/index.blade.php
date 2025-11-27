@extends('admin.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">CPF → Centre Mappings</h1>
        <a href="{{ route('admin.cpf-mappings.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded">
            Add Mapping
        </a>
    </div>
    <form method="GET" action="{{ route('admin.cpf-mappings.index') }}" class="mb-4">
        <div class="flex gap-3">
            <input type="text"
                name="cpf_no"
                value="{{ $searchCpf }}"
                placeholder="Search CPF..."
                class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 w-64">

            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Search
            </button>

            @if($searchCpf)
            <a href="{{ route('admin.cpf-mappings.index') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Reset
            </a>
            @endif
        </div>
    </form>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100 text-left text-gray-600 uppercase text-xs">
                    <th class="px-3 py-2">CPF Number</th>
                    <th class="px-3 py-2">Centre</th>
                    <th class="px-3 py-2 w-32">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mappings as $m)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $m->cpf_no }}</td>
                    <td class="px-3 py-2">{{ $m->centre->city }}</td>
                    <td class="px-3 py-2 flex gap-2">

                        <a href="{{ route('admin.cpf-mappings.edit', $m->id) }}"
                            class="px-3 py-1 bg-green-600 text-white rounded">
                            Edit
                        </a>

                        <form action="{{ route('admin.cpf-mappings.destroy', $m->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 text-white rounded"
                                onclick="return confirm('Delete this mapping?')">
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
</div>
@endsection