@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="container-fluid py-4">
        <h3>Edit Company</h3>
        <form action="{{ route('companies.update', $company) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Company Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $company->name }}" required>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Company Code</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ $company->code }}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Company Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $company->email }}">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Company Address</label>
                <textarea name="address" id="address" class="form-control" rows="3">{{ $company->address }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Company</button>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</main>
@endsection
