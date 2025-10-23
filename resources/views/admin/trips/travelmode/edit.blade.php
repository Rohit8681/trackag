@extends('admin.layout.layout')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Edit Travel Mode</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('travelmode.index') }}">Travel Modes</a></li>
                            <li class="breadcrumb-item active">Travel Modes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="card card-primary card-outline">
                        <form method="POST" action="{{ route('travelmode.update', $travelmode->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body row g-3">

                                <div class="col-md-12">
                                    <label class="form-label">Travel Mode Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror"
                                        value="{{ $travelmode->name }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">Update Travel Mode</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
@endsection