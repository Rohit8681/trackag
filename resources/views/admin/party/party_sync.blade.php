@extends('admin.layout.layout')
@section('title', 'Party Sync | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content-header py-3 bg-light border-bottom">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 text-primary"><i class="bi bi-arrow-repeat me-2"></i>Party Sync</h3>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Party Sync</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content py-4">
        <div class="container-fluid px-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-primary fw-semibold">Party Sync Details</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead class="table-light text-uppercase" style="font-size: 13px;">
                            <tr>
                                <th>Sr. No.</th>
                                <th>Party Name</th>
                                <th>party_code</th>
                                <th>phone 1</th>
                                <th>phone 2</th>
                                <th>contact_person_name</th>
                                <th>state</th>
                                <th>district</th>
                                <th>tehsil</th>
                                <th>Party Create Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Data implementation pending...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
