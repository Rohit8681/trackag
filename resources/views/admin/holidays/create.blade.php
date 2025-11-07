@extends('admin.layout.layout')
@section('title', 'Create Holiday | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add Holiday</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('holidays.index') }}">Holiday Master</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Create Holiday</h3>
            </div>
            <div class="card-body row g-3">
                @include('admin.holidays.form', [
                    'action' => route('holidays.store'),
                    'method' => 'POST',
                    'holiday' => null,
                    'states' => $states,
                ])
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#state_ids').select2({
            placeholder: "Select one or more states",
            width: '100%'
        });
    });
</script>
@endpush