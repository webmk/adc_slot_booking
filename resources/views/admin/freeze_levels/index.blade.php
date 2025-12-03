@extends('admin.layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Freeze / Unfreeze Levels</h2>

<form method="POST" action="{{ route('admin.levels.freeze.update') }}"
      class="bg-white p-5 rounded shadow">
    @csrf

    <p class="mb-3 text-gray-600">Tick the levels you want to freeze (block from booking)</p>

    <div class="grid grid-cols-2 gap-4">
        @foreach($levels as $lv)
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="levels[]" value="{{ $lv }}" 
                       {{ isset($frozen[$lv]) && $frozen[$lv] ? 'checked' : '' }}>
                <span class="{{ $frozen[$lv] ?? false ? 'text-red-600 font-semibold' : '' }}">
                    {{ $lv }}
                </span>
            </label>
        @endforeach
    </div>

    <button class="mt-5 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Save Changes
    </button>
</form>
@endsection