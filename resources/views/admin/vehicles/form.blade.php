<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="col-md-6">
        <label for="vehicle_name" class="form-label">Vehicle Name</label>
        <input type="text" name="vehicle_name" id="vehicle_name" class="form-control @error('vehicle_name') is-invalid @enderror" value="{{ old('vehicle_name', $vehicle?->vehicle_name) }}">
        @error('vehicle_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="vehicle_number" class="form-label">Vehicle Number</label>
        <input type="text" name="vehicle_number" id="vehicle_number" class="form-control @error('vehicle_number') is-invalid @enderror" value="{{ old('vehicle_number', $vehicle?->vehicle_number) }}">
        @error('vehicle_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="vehicle_type" class="form-label">Vehicle Type</label>
        <select name="vehicle_type" id="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
            @foreach(['Petrol','Diesel','CNG','EV','LPG'] as $type)
                <option value="{{ $type }}" {{ old('vehicle_type', $vehicle?->vehicle_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        @error('vehicle_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="assigned_person" class="form-label">Assigned Person</label>
        <select name="assigned_person" id="assigned_person" class="form-select @error('assigned_person') is-invalid @enderror">
            <option value="">-- Select User --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('assigned_person', $vehicle?->assigned_person) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
        @error('assigned_person')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="milage" class="form-label">Milage (Per Ltr/KG)</label>
        <input type="number" step="0.01" name="milage" id="milage" class="form-control @error('milage') is-invalid @enderror" value="{{ old('milage', $vehicle?->milage) }}">
        @error('milage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="assign_date" class="form-label">Assign Date</label>
        <input type="date" name="assign_date" id="assign_date" class="form-control @error('assign_date') is-invalid @enderror" value="{{ old('assign_date', $vehicle?->assign_date) }}">
        @error('assign_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="active" {{ old('status', $vehicle?->status) == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $vehicle?->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">{{ $vehicle ? 'Update' : 'Submit' }}</button>
        <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
