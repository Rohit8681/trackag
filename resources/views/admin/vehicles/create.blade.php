@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add Vehicle</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('vehicle.index') }}">Vehicle Master</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Create Vehicle</h3>
            </div>
            <div class="card-body">
                @include('admin.vehicles.form', [
                    'action' => route('vehicle.store'),
                    'method' => 'POST',
                    'vehicle' => null,
                    'users' => $users,
                ])
            </div>
        </div>
    </div>
</main>
@endsection
