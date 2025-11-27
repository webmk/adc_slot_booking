@extends('admin.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit CPF Mapping</h1>
    <div class="bg-white p-6 shadow rounded">
        <form action="{{ route('admin.cpf-mappings.update', $cpfMapping->id) }}"
              method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="font-semibold">CPF Number</label>
                <input type="text" name="cpf_no"
                       value="{{ $cpfMapping->cpf_no }}"
                       class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label class="font-semibold">ADC Centre</label>
                <select name="adc_centre_id"
                        class="border p-2 rounded w-full" required>
                    @foreach($centres as $c)
                        <option value="{{ $c->id }}"
                            @if($cpfMapping->adc_centre_id == $c->id) selected @endif>
                            {{ $c->city }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update
                </button>
                <a href="{{ route('admin.cpf-mappings.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection