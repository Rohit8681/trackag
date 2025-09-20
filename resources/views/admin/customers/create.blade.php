@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Customer Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                            <li class="breadcrumb-item active">Add Customer</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">Add New Customer</div>
                            </div>

                            {{-- Flash & Error Messages --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                    <strong>Success:</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <strong>Error:</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                        <strong>Error:</strong> {{ $error }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endforeach
                            @endif

                            <form method="POST" action="{{ route('customers.store') }}">
                                @csrf
                                <div class="card-body row">

                                    {{-- Name --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name') }}" required>
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}">
                                    </div>

                                    {{-- Phone --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ old('phone') }}" required>
                                    </div>

                                    {{-- Address --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" name="address" id="address" class="form-control"
                                            value="{{ old('address') }}">
                                    </div>

                                    {{-- Company (Only for Master Admin) --}}
                                    @if (auth()->user()->hasRole('master_admin'))
                                        <div class="mb-3 col-md-6">
                                            <label for="company_id" class="form-label">Select Company</label>
                                            <select class="form-select" name="company_id" id="company_id" required>
                                                <option value="">-- Select Company --</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}"
                                                        {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    {{-- Executive --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="user_id" class="form-label">Assign Executive</label>
                                        <select class="form-select" name="user_id" id="user_id">
                                            <option value="">-- Select Executive --</option>
                                            @if (!auth()->user()->hasRole('master_admin'))
                                                @foreach ($executives as $executive)
                                                    <option value="{{ $executive->id }}"
                                                        {{ old('user_id') == $executive->id ? 'selected' : '' }}>
                                                        {{ $executive->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                    {{-- Active Status --}}
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Status</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" value="1"
                                                {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" value="0"
                                                {{ old('is_active') == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Inactive</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">Add Customer</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
