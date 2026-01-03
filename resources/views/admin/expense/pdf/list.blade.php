@extends('admin.layout.layout')
@section('title', 'Expense PDF List')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <h3>Expense PDF List</h3>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body table-responsive">

                    <table id="pdfs-table" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Month</th>
                                <th>PDF</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pdfs as $key => $pdf)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $pdf->user->name ?? '-' }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $pdf->month)->format('F Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ asset('storage/'.$pdf->pdf_path) }}"
                                           class="btn btn-sm btn-danger"
                                           target="_blank">
                                            <i class="fas fa-file-pdf"></i> Download
                                        </a>
                                    </td>

                                    <td>{{ $pdf->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        No PDF found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    var pdfs = @json($pdfs->count());
    if (pdfs > 0) {
        $('#pdfs-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } // Actions column not orderable
            ]
        });
    }
});
</script>
@endpush
