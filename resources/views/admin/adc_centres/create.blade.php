@extends('admin.layouts.app')

@section('title', 'Create Centre')
@section('page-title', 'Create ADC Centre')
@section('page-subtitle', 'Add a new testing location')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
    <form action="{{ route('admin.adc-centres.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block font-medium text-gray-700 mb-1">City *</label>
            <input type="text" name="city" value="{{ old('city') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500
                          @error('city') border-red-500 @enderror" required>
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">State</label>
            <input type="text" name="state" value="{{ old('state') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="3"
                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address') }}</textarea>
        </div>
        <div class="flex gap-3 pt-4">
            <a href="{{ route('admin.adc-centres.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Cancel
            </a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Create Centre
            </button>
        </div>
    </form>
</div>
@endsection