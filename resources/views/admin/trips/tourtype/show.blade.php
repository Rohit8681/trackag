@extends('admin.layout.layout')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3>Travel Mode Details</h3></div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('travelmode.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        <strong>Name:</strong>
                        <p>{{ $travelMode->name }}</p>
                    </div>

                    @if(auth()->user()->user_level === 'master_admin')
                    <div class="mb-3">
                        <strong>Company:</strong>
                        <p>{{ $travelMode->company->name ?? '-' }}</p>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Created At:</strong>
                        <p>{{ $travelMode->created_at }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Updated At:</strong>
                        <p>{{ $travelMode->updated_at }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>
@endsection
