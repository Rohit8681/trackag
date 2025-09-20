@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="container-fluid py-4">
        <h3>Company Details</h3>
        <div class="card">
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
</main>
@endsection
