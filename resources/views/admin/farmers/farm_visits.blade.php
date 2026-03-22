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
                                        {{-- <td>{{ $visit->crop_sowing_land_area ?? '-' }}</td> --}}
                                        <td>{{ $visit->land_area_size . ' ' . $visit->crop_sowing_land_area ?? '-' }}</td>
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
                                            @php
                                                $daysDiff = \Carbon\Carbon::parse($visit->created_at)->diffInDays(now());
                                            @endphp

                                            @if(!empty($visit->videos) && $daysDiff < 7)
                                                <button class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#videoModal{{ $visit->id }}">
                                                    View
                                                </button>
                                            @elseif(!empty($visit->videos) && $daysDiff >= 7)
                                                <span class="text-danger">Expired</span>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>{{ $visit->remark ?? '-' }}</td>
                                        <td>{{ optional($visit->next_visit_date)->format('d-m-Y') }}</td>

                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#remarkModal{{ $visit->id }}">
                                                {{ $visit->agronomist_remark ? 'Edit' : 'Add' }}
                                            </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="remarkModal{{ $visit->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-md modal-dialog-centered">
                                            <div class="modal-content">

                                                <form method="POST"
                                                    action="{{ route('farm-visits.agronomist-remark', $visit->id) }}">
                                                    @csrf

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Agronomist Remark - {{ $visit->farmer->farmer_name }}
                                                        </h5>
                                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <textarea name="agronomist_remark"
                                                            class="form-control"
                                                            rows="4">{{ $visit->agronomist_remark }}</textarea>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>

                                                        <button type="submit" class="btn btn-success">
                                                            Save
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>

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
                                                        @foreach($visit->images as $img)
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
                                    @if(!empty($visit->videos))
                                    <div class="modal fade" id="videoModal{{ $visit->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Farm Visit Video</h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    @php
                                                        $daysDiff = \Carbon\Carbon::parse($visit->created_at)->diffInDays(now());
                                                    @endphp

                                                    @if($daysDiff >= 7)
                                                        <div class="alert alert-danger text-center">
                                                            ⚠️ This video will be deleted after 7 days from the upload date.
                                                            Please download it if you need to keep a copy.
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning text-center">
                                                            ⏳ This video will be deleted in {{ 7 - $daysDiff }} day(s).
                                                            Please download it if needed.
                                                        </div>
                                                    @endif
                                                    <div class="row">
                                                    @foreach($visit->videos as $video)
                                                        <div class="col-md-6 mb-3">
                                                            <video controls style="width:100%; max-height:250px;">
                                                                <source src="{{ asset('storage/'.$video) }}">
                                                            </video>
                                                        </div>
                                                    @endforeach
                                                </div>
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
