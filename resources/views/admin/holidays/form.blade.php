<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="col-md-6">
        <label for="holiday_name" class="form-label">Holiday Name</label>
        <input type="text" name="holiday_name" id="holiday_name" class="form-control @error('holiday_name') is-invalid @enderror" value="{{ old('holiday_name', $holiday?->holiday_name) }}" >
        @error('holiday_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="holiday_date" class="form-label">Holiday Date</label>
        <input type="date" name="holiday_date" id="holiday_date" class="form-control @error('holiday_date') is-invalid @enderror" value="{{ old('holiday_date', $holiday?->holiday_date) }}" >
        @error('holiday_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="holiday_type" class="form-label">Holiday Type</label>
        <select name="holiday_type" id="holiday_type" class="form-select @error('holiday_type') is-invalid @enderror" >
            <option value="Public" {{ old('holiday_type', $holiday?->holiday_type) == 'Public' ? 'selected' : '' }}>Public</option>
            <option value="National" {{ old('holiday_type', $holiday?->holiday_type) == 'National' ? 'selected' : '' }}>National</option>
            <option value="State" {{ old('holiday_type', $holiday?->holiday_type) == 'State' ? 'selected' : '' }}>State</option>
            <option value="Festival" {{ old('holiday_type', $holiday?->holiday_type) == 'Festival' ? 'selected' : '' }}>Festival</option>
        </select>
        @error('holiday_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- <div class="col-md-6">
        <label for="state_id" class="form-label">State</label>
        <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror">
            <option value="">All States</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" {{ old('state_id', $holiday?->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
            @endforeach
        </select>
        @error('state_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div> --}}
    <div class="col-md-6">
        <label for="state_ids" class="form-label">Select States</label>
        <select name="state_ids[]" id="state_ids" class="form-select @error('state_ids') is-invalid @enderror" multiple>
            @foreach($states as $state)
                <option value="{{ $state->id }}"
                    @if(in_array($state->id, old('state_ids', $holiday?->state_ids ?? []))) selected @endif>
                    {{ $state->name }}
                </option>
            @endforeach
        </select>
        @error('state_ids')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="is_paid" class="form-label">Is Paid</label>
        <select name="is_paid" id="is_paid" class="form-select @error('is_paid') is-invalid @enderror" >
            <option value="Yes" {{ old('is_paid', $holiday?->is_paid) == 'Yes' ? 'selected' : '' }}>Yes</option>
            <option value="No" {{ old('is_paid', $holiday?->is_paid) == 'No' ? 'selected' : '' }}>No</option>
        </select>
        @error('is_paid')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if($holiday)
    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" >
            <option value="1" {{ old('status', $holiday?->status) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $holiday?->status) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @endif

    <div class="col-12">
        <button type="submit" class="btn btn-primary">{{ $holiday ? 'Update' : 'Submit' }}</button>
        <a href="{{ route('holidays.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>