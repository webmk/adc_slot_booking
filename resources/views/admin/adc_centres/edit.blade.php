@extends('admin.layouts.app')

@section('title', 'Edit Centre')
@section('page-title', 'Edit ADC Centre')
@section('page-subtitle', $centre->name)

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
    <form action="{{ route('admin.adc-centres.update', $centre) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-medium text-gray-700 mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name', $centre->name) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500
                          @error('name') border-red-500 @enderror" required>
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">City</label>
            <input type="text" name="city" value="{{ old('city', $centre->city) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="3"
                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $centre->address) }}</textarea>
        </div>
        <div class="flex gap-3 pt-4">
            <a href="{{ route('admin.adc-centres.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Cancel
            </a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection