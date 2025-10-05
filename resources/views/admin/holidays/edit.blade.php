@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Holiday</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('holidays.index') }}">Holiday Master</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Holiday</h3>
            </div>
            <div class="card-body">
                @include('admin.holidays.form', [
                    'action' => route('holidays.update', $holiday->id),
                    'method' => 'PUT',
                    'holiday' => $holiday,
                    'states' => $states,
                ])
            </div>
        </div>
    </div>
</main>
@endsection