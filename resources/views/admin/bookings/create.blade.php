@extends('admin.layouts.app')

@section('title', 'Create Booking')

@section('content')

<h2 class="text-2xl font-bold mb-6">Create Booking</h2>

@if($errors->any())
    <div class="p-4 bg-red-100 border border-red-300 text-red-700 mb-4 rounded">
        @foreach($errors->all() as $err)
            <p>{{ $err }}</p>
        @endforeach
    </div>
@endif
<div class="bg-white p-6 rounded shadow max-w-xl">
<form action="{{ route('admin.bookings.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="font-medium">Search Employee by CPF</label>
        <input type="text" id="cpf_search" 
            class="w-full border p-2 rounded" 
            placeholder="Enter CPF Number" required>

        <div id="employee_result" class="mt-2"></div>
        <input type="hidden" name="user_id" id="user_id">
    </div>
    <div class="mb-4">
        <label class="font-medium">Select Centre</label>
        <select id="centre_id" class="w-full border p-2 rounded">
            <option value="">-- Select Centre --</option>
            @foreach($centres as $c)
                <option value="{{ $c->id }}">{{ $c->city }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="font-medium">Select Date</label>
        <select name="adc_date_id" id="adc_date_id" class="w-full border p-2 rounded" required>
            <option value="">-- Select Date --</option>
        </select>
    </div>
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Create Booking
    </button>
</form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    //Employee Search
    $('#cpf_search').on('keyup', function() {
        let cpf = $(this).val().trim();

        if (cpf.length < 3) {
            $('#employee_result').html('');
            $('#user_id').val('');
            return;
        }

        $('#employee_result').html('<p class="text-gray-500">Searching...</p>');

        $.ajax({
            url: "{{ route('admin.employees.search') }}",
            method: "GET",
            data: { cpf: cpf },
            success: function(res) {

                if (res.not_found) {
                    $('#employee_result').html('<p class="text-red-600">No employee found.</p>');
                    $('#user_id').val('');
                    return;
                }

                $('#employee_result').html(
                    `<div class="p-3 border rounded bg-gray-50">
                        <p><strong>Name:</strong> ${res.name}</p>
                        <p><strong>CPF:</strong> ${res.cpf_no}</p>
                        <p><strong>Level:</strong> ${res.level}</p>

                        <button type="button" id="selectUserBtn" 
                            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">
                            Select This Employee
                        </button>
                    </div>`
                );

                $('#selectUserBtn').on('click', function() {
                    $('#user_id').val(res.id);
                    $('#cpf_search').val(res.cpf_no);
                    $('#employee_result').html(
                        `<p class="text-green-600">Employee Selected ✔</p>`
                    );
                });
            }
        });
    });

//Get Dates by Selecting Centre
$('#centre_id').on('change', function() {
    let centreId = $(this).val();

    $('#adc_date_id').html('<option>Loading...</option>');

    $.get("{{ url('/admin/centres/dates') }}/" + centreId, function(data) {
        $('#adc_date_id').empty().append('<option value="">-- Select Date --</option>');
        data.forEach(function(d) {
            $('#adc_date_id').append(
                `<option value="${d.id}">${d.date} (${d.available_seats})</option>`
            );
        });
    });
});
});
</script>
@endpush