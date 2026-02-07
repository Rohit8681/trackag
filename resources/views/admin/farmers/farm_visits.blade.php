@extends('admin.layout.layout')
@section('title', 'Farm Visits | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Farm Visits – {{ $farmer->farmer_name }}
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('farmers.index') }}" class="btn btn-sm btn-secondary">
                        ← Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Farm Visit List</h3>
                </div>

                <div class="card-body">

                    {{-- Table --}}
                    <div class="table-responsive" style="max-height:600px;">
                        <table id="farm-visit-table"
                               class="table table-bordered table-hover table-striped align-middle table-sm">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Date</th>
                                    <th>Crop Name</th>
                                    <th>Crop Days</th>
                                    <th>Sowing Area</th>
                                    <th>Condition</th>
                                    <th>Pest & Disease</th>
                                    <th>Product Suggested</th>
                                    <th>Image</th>
                                    <th>Video</th>
                                    <th>Remark</th>
                                    <th>Next Visit</th>
                                    <th width="220">Agronomist Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($visits as $index => $visit)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $visit->created_at?->format('d-m-Y') }}</td>
                                        <td>{{ $visit->crop->name ?? '-' }}</td>
                                        <td>{{ $visit->crop_days ?? '-' }}</td>
                                        <td>{{ $visit->crop_sowing_land_area ?? '-' }}</td>
                                        <td>{{ $visit->crop_condition ?? '-' }}</td>
                                        <td>{{ $visit->pest_disease ?? '-' }}</td>
                                        <td>{{ $visit->product_suggested ?? '-' }}</td>

                                        {{-- Image --}}
                                        <td class="text-center">
                                            @if($visit->images)
                                                <button class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#imageModal{{ $visit->id }}">
                                                    View
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- Video --}}
                                        <td class="text-center">
                                            @if($visit->video)
                                                <button class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#videoModal{{ $visit->id }}">
                                                    View
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>{{ $visit->remark ?? '-' }}</td>
                                        <td>{{ optional($visit->next_visit_date)->format('d-m-Y') }}</td>

                                        {{-- Agronomist Remark --}}
                                        <td>
                                            <form method="POST"
                                                  action="{{ route('farm-visits.agronomist-remark', $visit->id) }}">
                                                @csrf
                                                <textarea
                                                    name="agronomist_remark"
                                                    class="form-control form-control-sm"
                                                    rows="2"
                                                    placeholder="Enter remark">{{ $visit->agronomist_remark }}</textarea>
                                                <button class="btn btn-success btn-sm mt-1 w-100">
                                                    Save
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Image Modal --}}
                                    @if($visit->images)
                                    <div class="modal fade" id="imageModal{{ $visit->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Farm Visit Images</h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @foreach(json_decode($visit->images, true) as $img)
                                                            <div class="col-md-4 mb-2">
                                                                <img src="{{ asset('storage/'.$img) }}"
                                                                     class="img-fluid rounded border">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Video Modal --}}
                                    @if($visit->video)
                                    <div class="modal fade" id="videoModal{{ $visit->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Farm Visit Video</h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <video controls style="width:100%; max-height:400px;">
                                                        <source src="{{ asset('storage/'.$visit->video) }}">
                                                    </video>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">
                                            No farm visits found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let total = {{ $visits->count() }};
    if (total > 0) {
        $('#farm-visit-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50]
        });
    }
});
</script>
@endpush
