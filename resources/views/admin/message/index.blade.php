@extends('admin.layout.layout')
@section('title', 'List Messages | Trackag')
@push('styles')
<style>
    .msg-tooltip {
        cursor: pointer;
        text-decoration: underline dotted;
    }
</style>
@endpush

@section('content')
    <main class="app-main">
        <!-- Page Header -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Messages</h3>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-primary card-outline">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">Messages List</h3>
                                {{-- @can('create_states') --}}
                                <a href="{{ route('messages.create') }}" class="btn btn-primary ms-auto">
                                    <i class="fas fa-plus me-1"></i> Add Message
                                </a>
                                {{-- @endcan --}}
                            </div>

                            <div class="card-body">
                                {{-- States Table --}}
                                <div class="table-responsive" style="max-height: 600px;">
                                    <table id="messages-table"
                                           class="table table-bordered table-hover table-striped align-middle table-sm">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="width: 40px;">No</th>
                                                <th>Date</th>
                                                <th>State Name</th>
                                                <th>Employee Name</th>
                                                <th>Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($messages as $index => $message)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $message->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $message->state->name ?? '-' }}</td>
                                                <td>{{ $message->user->name ?? '-' }}</td>

                                                <td style="white-space: normal; max-width: 300px;">
                                                    <span
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $message->message }}"
                                                    >
                                                        {{ \Illuminate\Support\Str::limit($message->message, 150) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    No messages found.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var messages = @json($messages->count());
    if (messages > 0) {
        $('#messages-table').DataTable({
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

document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
