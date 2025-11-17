@extends('admin.layout.layout')
@section('title', 'Edit Expense | Trackag')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <h3>Edit Expense</h3>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">Edit Expense</h5>
                </div>

                <form method="POST" action="{{ route('expense.update', $expense->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body row">

                        {{-- Bill Date --}}
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Bill Date</label>
                            <input type="date" name="bill_date" class="form-control"
                                value="{{ old('bill_date', $expense->bill_date) }}" required>
                        </div>

                        {{-- Bill Type --}}
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Bill Type</label>
                            <select name="bill_type[]" class="form-select select2" multiple required>
                                @php $selected = $expense->bill_type ?? []; @endphp

                                @foreach(['Petrol','Food','Accommodation','Travel','Courier','Others'] as $type)
                                    <option value="{{ $type }}" 
                                        {{ in_array($type, $selected) ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">You can select multiple types</small>
                        </div>

                        {{-- Bill Title --}}
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Bill Title</label>
                            <input type="text" name="bill_title" class="form-control"
                                value="{{ old('bill_title', $expense->bill_title) }}">
                        </div>

                        {{-- Bill Description --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Bill Details</label>
                            <textarea name="bill_details_description" class="form-control" rows="3">{{ old('bill_details_description', $expense->bill_details_description) }}</textarea>
                        </div>

                        {{-- Travel Mode --}}
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Travel Mode</label>
                            <input type="text" name="travel_mode" class="form-control" value="{{ old('travel_mode', $expense->travel_mode) }}">
                        </div>

                        {{-- Amount --}}
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                value="{{ old('amount', $expense->amount) }}" required>
                        </div>

                        {{-- Bill Image --}}
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Bill Image</label>
                            <input type="file" name="image" class="form-control">

                            @if($expense->image)
                                <img src="{{ asset('storage/expenses/'.$expense->image) }}"
                                    class="img-thumbnail mt-2"
                                    width="120">
                            @endif
                        </div>

                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Update Expense</button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</main>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Approved Bills",
        width: '100%'
    });
});
</script>
@endpush
