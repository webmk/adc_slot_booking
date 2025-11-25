@extends('admin.layouts.app')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    table.dataTable th,
    table.dataTable td {
        padding: 8px 12px !important;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">ADC Report</h1>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

        <div>
            <label class="font-medium">From Date</label>
            <input type="date" id="from" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-medium">To Date</label>
            <input type="date" id="to" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-medium">Centre</label>
            <select id="centre_id" class="w-full border p-2 rounded">
                <option value="">All</option>
                @foreach($centres as $c)
                <option value="{{ $c->id }}">{{ $c->city }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-medium">Level</label>
            <select id="level" class="w-full border p-2 rounded">
                <option value="">All</option>
                @foreach($levels as $lv)
                <option value="{{ $lv }}">{{ $lv }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-medium">Status</label>
            <select id="status" class="w-full border p-2 rounded">
                <option value="booked">Booked Only</option>
                <option value="not_booked">Not Booked</option>
            </select>
        </div>
    </div>
    <div class="bg-white p-4 shadow rounded">
        <table id="reportTable" class="display nowrap stripe" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>CPF</th>
                    <th>Level</th>
                    <th>Location</th>
                    <th>Centre</th>
                    <th>ADC Date</th>
                    <th>Booked At</th>
                </tr>
            </thead>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    console.log("Report JS Loaded");

    let table = $('#reportTable').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        dom: 'Bfrtip',
        buttons: ['csv', 'excel', 'print'],
        ajax: {
            url: "{{ route('admin.reports.data') }}",
            type: "GET",
            data: function(d) {
                d.from = $('#from').val();
                d.to = $('#to').val();
                d.centre_id = $('#centre_id').val();
                d.level = $('#level').val();
                d.status = $('#status').val();
            }
        },
        columns: [{
                data: 'name'
            },
            {
                data: 'cpf_no'
            },
            {
                data: 'level'
            },
            {
                data: 'location'
            },
            {
                data: 'centre'
            },
            {
                data: 'date'
            },
            {
                data: 'booked_at'
            },
        ]
    });

    $('#from, #to, #centre_id, #level, #status').on('change', function() {
        console.log("Filter changed");
        table.ajax.reload();
    });
</script>
@endpush