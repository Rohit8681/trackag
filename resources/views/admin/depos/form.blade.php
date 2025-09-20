<form action="{{ $action }}" method="POST" id="depoForm">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    {{-- Depo Code --}}
    <div class="mb-3">
        <label for="depo_code" class="form-label">Depo Code <span class="text-danger">*</span></label>
        <input type="text" name="depo_code" id="depo_code"
               class="form-control @error('depo_code') is-invalid @enderror"
               value="{{ old('depo_code', $depo->depo_code ?? '') }}" required>
        @error('depo_code') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- Depo Name --}}
    <div class="mb-3">
        <label for="depo_name" class="form-label">Depo Name <span class="text-danger">*</span></label>
        <input type="text" name="depo_name" id="depo_name"
               class="form-control @error('depo_name') is-invalid @enderror"
               value="{{ old('depo_name', $depo->depo_name ?? '') }}" required>
        @error('depo_name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- Manage By --}}
    <div class="mb-3">
        <label for="manage_by" class="form-label">Manage By</label>
        <input type="text" name="manage_by" id="manage_by"
               class="form-control"
               value="{{ old('manage_by', $depo->manage_by ?? '') }}">
    </div>

    {{-- State --}}
    <div class="mb-3">
        <label for="state_id" class="form-label">State</label>
        <select name="state_id" id="state_id" class="form-select">
            <option value="">-- Select State --</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}"
                    {{ old('state_id', $depo->state_id ?? '') == $state->id ? 'selected' : '' }}>
                    {{ $state->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- District --}}
    <div class="mb-3">
        <label for="district_id" class="form-label">District</label>
        <select name="district_id" id="district_id" class="form-select">
            <option value="">-- Select District --</option>
            @if(!empty($districts))
                @foreach($districts as $district)
                    <option value="{{ $district->id }}"
                        {{ old('district_id', $depo->district_id ?? '') == $district->id ? 'selected' : '' }}>
                        {{ $district->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    {{-- Tehsil --}}
    <div class="mb-3">
        <label for="tehsil_id" class="form-label">Tehsil (Taluka)</label>
        <select name="tehsil_id" id="tehsil_id" class="form-select">
            <option value="">-- Select Tehsil --</option>
            @if(!empty($tehsils))
                @foreach($tehsils as $tehsil)
                    <option value="{{ $tehsil->id }}"
                        {{ old('tehsil_id', $depo->tehsil_id ?? '') == $tehsil->id ? 'selected' : '' }}>
                        {{ $tehsil->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    {{-- City --}}
    <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" name="city" id="city" class="form-control"
               value="{{ old('city', $depo->city ?? '') }}">
    </div>

    {{-- Status --}}
    <div class="form-check mb-3">
        <input type="checkbox" name="status" value="1" class="form-check-input"
               {{ old('status', $depo->status ?? 1) ? 'checked' : '' }}>
        <label for="status" class="form-check-label">Active</label>
    </div>

    {{-- Buttons --}}
    <button type="submit" class="btn btn-primary">{{ $method === 'PUT' ? 'Update' : 'Save' }}</button>
    <a href="{{ route('depos.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@push('scripts')
<script>
$(document).ready(function() {
    $('#state_id').on('change', function() {
        var stateId = $(this).val();
        $('#district_id').html('<option value="">Loading...</option>');
        $('#tehsil_id').html('<option value="">-- Select Tehsil --</option>');

        if(!stateId) {
            $('#district_id').html('<option value="">-- Select District --</option>');
            return;
        }

        $.get("{{ route('depos.get-districts') }}", { state_id: stateId }, function(data){
            var html = '<option value="">-- Select District --</option>';
            $.each(data, function(i, d){
                html += '<option value="'+ d.id +'">'+ d.name +'</option>';
            });
            $('#district_id').html(html);
        });
    });

    $('#district_id').on('change', function() {
        var districtId = $(this).val();
        $('#tehsil_id').html('<option value="">Loading...</option>');

        if(!districtId) {
            $('#tehsil_id').html('<option value="">-- Select Tehsil --</option>');
            return;
        }

        $.get("{{ route('depos.get-tehsils') }}", { district_id: districtId }, function(data){
            var html = '<option value="">-- Select Tehsil --</option>';
            $.each(data, function(i, t){
                html += '<option value="'+ t.id +'">'+ t.name +'</option>';
            });
            $('#tehsil_id').html(html);
        });
    });
});
</script>
@endpush
