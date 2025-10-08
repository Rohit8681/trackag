<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="col-md-6">
        <label for="leave_name" class="form-label">Leave Name</label>
        <input type="text" name="leave_name" id="leave_name" class="form-control @error('leave_name') is-invalid @enderror" value="{{ old('leave_name', $leaf?->leave_name) }}" required>
        @error('leave_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="leave_code" class="form-label">Leave Code</label>
        <input type="text" name="leave_code" id="leave_code" class="form-control @error('leave_code') is-invalid @enderror" value="{{ old('leave_code', $leaf?->leave_code) }}" required>
        @error('leave_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="is_paid" class="form-label">Is Paid</label>
        <select name="is_paid" id="is_paid" class="form-select @error('is_paid') is-invalid @enderror" required>
            <option value="Yes" {{ old('is_paid', $leaf?->is_paid) == 'Yes' ? 'selected' : '' }}>Yes</option>
            <option value="No" {{ old('is_paid', $leaf?->is_paid) == 'No' ? 'selected' : '' }}>No</option>
        </select>
        @error('is_paid')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if(isset($leaf))
    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="1" {{ old('status', $leaf?->status) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $leaf?->status) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @endif

    <div class="col-12">
        <button type="submit" class="btn btn-primary">{{ isset($leaf) ? 'Update' : 'Submit' }}</button>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
