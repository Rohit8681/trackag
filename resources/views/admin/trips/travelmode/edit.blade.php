@extends('admin.layout.layout')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3>Edit Travel Mode</h3></div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('travelmode.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show">{{ $error }}</div>
                @endforeach
            @endif

            <div class="card">
                <form method="POST" action="{{ route('travelmode.update', $travelmode->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Travel Mode Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $travelmode->name }}" required>
                        </div>

                        @if(auth()->user()->user_level === 'master_admin')
                        <div class="col-md-6">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select name="company_id" class="form-select" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $travelmode->company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Update Travel Mode</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>
@endsection
