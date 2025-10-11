@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">TA-DA Bill Master</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Master</a></li>
                        <li class="breadcrumb-item active">TA-DA Bill Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">TA-DA Bill Master</h3>
                        </div>

                        <form action="{{ route('ta-da-bill-master.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                {{-- ✅ Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Designation</th>
                                                <th>Day Limit</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($designations as $index => $designation)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $designation->name }}</td>
                                                    <td>
                                                        <input type="number" name="designations[{{ $designation->id }}][day_limit]" class="form-control" value="{{ old('designations.'.$designation->id.'.day_limit', $designation->taDaBillMaster->day_limit ?? '') }}">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status" type="checkbox"
                                                            data-id="{{ $designation->taDaBillMaster->id ?? "" }}"
                                                            {{ $designation->taDaBillMaster->status ? 'checked' : '' }}>
                                                    </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script>

$('.toggle-status').change(function () {
    console.log("Checkbox clicked", $(this).data('id'), $(this).prop('checked'));
    let status = $(this).prop('checked') ? 1 : 0;
    let id = $(this).data('id');

    $.ajax({
        url: "{{ route('ta-da-bill-master.toggle-status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            status: status
        },
        success: function (response) {
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: response.message,
              showConfirmButton: false,
              timer: 1500
            })
        },
        error: function () {
            Swal.fire('Error', 'Something went wrong!', 'error');
        }
    });
});
</script>
@endpush
