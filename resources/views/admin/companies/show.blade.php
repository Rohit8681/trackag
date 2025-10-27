@extends('admin.layout.layout')
@section('title', 'View Company | Trackag')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">View Company</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Company</a></li>
                            <li class="breadcrumb-item active">View Company</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-body">
                                <p><strong>Name:</strong> {{ $company->name }}</p>
                                <p><strong>Code:</strong> {{ $company->code ?? '-' }}</p>
                                <p><strong>Email:</strong> {{ $company->email ?? '-' }}</p>
                                <p><strong>Address:</strong> {{ $company->address ?? '-' }}</p>
                                <p><strong>Created At:</strong> {{ $company->created_at->format('Y-m-d') }}</p>
                                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection