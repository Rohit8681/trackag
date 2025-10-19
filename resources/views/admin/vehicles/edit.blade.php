@extends('admin.layout.layout')
@section('title', 'Edit Vehicle | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Vehicle</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('vehicle.index') }}">Vehicle Master</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Vehicle</h3>
            </div>
            <div class="card-body">
                @include('admin.vehicles.form', [
                    'action' => route('vehicle.update', $vehicle->id),
                    'method' => 'PUT',
                    'vehicle' => $vehicle,
                    'users' => $users,
                ])
            </div>
        </div>
    </div>
</main>
@endsection
