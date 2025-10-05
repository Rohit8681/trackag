@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Leave</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Master</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Leave</h3>
            </div>
            <div class="card-body">
                @include('admin.leaves.form', [
                    'action' => route('leaves.update', $leaf->id),
                    'method' => 'PUT',
                    'leaf' => $leaf,
                ])
            </div>
        </div>
    </div>
</main>
@endsection
