@extends('admin.layout.layout')
@section('title', 'Edit Product | Trackag')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>


<main class="app-main">

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Product</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('products.update', $product->id) }}" id="productForm">
                    @csrf
                    @method('PUT')

                    {{-- PRODUCT DETAILS --}}
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="product_name"
                                   class="form-control"
                                   value="{{ old('product_name', $product->product_name) }}">
                            <span class="text-danger error-text product_name_error"></span>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Technical Name <span class="text-danger">*</span></label>
                            <input type="text" name="technical_name"
                                   class="form-control"
                                   value="{{ old('technical_name', $product->technical_name) }}">
                            <span class="text-danger error-text technical_name_error"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Product Category <span class="text-danger">*</span></label>
                            <select name="product_category_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $product->product_category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text product_category_id_error"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Item Code <span class="text-danger">*</span></label>
                            <input type="text" name="item_code"
                                   class="form-control"
                                   value="{{ old('item_code', $product->item_code) }}">
                            <span class="text-danger error-text item_code_error"></span>
                        </div>

                        

                        <div class="col-md-3">
                            <label class="form-label">Shipper Gross Weight (KG) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01"
                                   name="shipper_gross_weight"
                                   class="form-control"
                                   value="{{ $product->shipper_gross_weight }}">
                            <span class="text-danger error-text shipper_gross_weight_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Product States <span class="text-danger">*</span></label>
                            <select name="product_states[]" class="form-select select2" multiple required>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ $product->productStates->pluck('state_id')->contains($state->id) ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Master Packing <span class="text-danger">*</span></label>
                            <select name="master_packing" class="form-select" required>
                                <option value="">-- Select Master Packing --</option>
                                <option value="Yes" {{ $product->master_packing == "Yes" ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $product->master_packing == "No" ? 'selected' : '' }}>No</option>
                            </select>
                            <span class="text-danger error-text master_packing_error"></span>
                        </div>
                        
                    </div>

                    <hr>

                    {{-- PACKING DETAILS --}}
                    <h6 class="fw-bold mb-2">Packing Details</h6>

                    <table class="table table-bordered table-sm" id="packingTable">
                        <thead class="table-light">
                            <tr>
                                <th>Packing Value <span class="text-danger">*</span></th>
                                <th>Size <span class="text-danger">*</span></th>
                                <th>Shipper Type <span class="text-danger">*</span></th>
                                <th>Shipper Size <span class="text-danger">*</span></th>
                                <th>No of Units<span class="text-danger">*</span></th>
                                <th>States <span class="text-danger">*</span></th>
                                <th>Status <span class="text-danger">*</span></th>
                                <th width="60">
                                    <button type="button" class="btn btn-success" id="addRow">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->packings as $index => $pack)
                                <tr>
                                    <td>
                                        <input type="number" step="0.01" name="packing_value[]"
                                               value="{{ $pack->packing_value }}"
                                               class="form-control form-control-sm packing_value">
                                        <span class="text-danger error-text packing_value_error"></span>
                                    </td>
                                    <td>
                                        <select name="packing_size[]" class="form-select form-select-sm">
                                            @foreach(['GM','KG','ML','LTR','UNIT'] as $size)
                                                <option value="{{ $size }}"
                                                    {{ $pack->packing_size == $size ? 'selected' : '' }}>
                                                    {{ $size }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text packing_size_error"></span>
                                    </td>
                                    <td>
                                        <select name="shipper_type[]" class="form-select form-select-sm">
                                            @foreach(['Bag','Box','Bucket','Drum'] as $type)
                                                <option value="{{ $type }}"
                                                    {{ $pack->shipper_type == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text shipper_type_error"></span>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="shipper_size[]"
                                               value="{{ $pack->shipper_size }}"
                                               class="form-control form-control-sm shipper_size">
                                        <span class="text-danger error-text shipper_size_error"></span>
                                    </td>
                                    <td>
                                        <input type="number" name="unit_in_shipper[]"
                                               value="{{ $pack->unit_in_shipper }}"
                                               class="form-control form-control-sm unit_in_shipper"
                                               readonly>
                                    </td>
                                    <td>
                                        <select name="packing_states[{{ $index }}][]" class="form-select select2" multiple>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ $pack->packingStates->pluck('state_id')->contains($state->id) ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input packing_status_toggle"
                                                   type="checkbox" name="packing_status[]"
                                                   value="1" {{ $pack->status ? 'checked' : '' }}>
                                            <span class="text-danger error-text packing_status_error"></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{-- <button type="button" class="btn btn-danger removeRow">×</button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <table class="d-none">
                            <tbody>
                                <tr id="packingRowTemplate">
                                    <td>
                                        <input type="number" step="0.01" name="packing_value[]" class="form-control form-control-sm packing_value">
                                    </td>
                                    <td>
                                        <select name="packing_size[]" class="form-select form-select-sm">
                                            <option value="">Select</option>
                                            <option>GM</option>
                                            <option>KG</option>
                                            <option>ML</option>
                                            <option>LTR</option>
                                            <option>UNIT</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="shipper_type[]" class="form-select form-select-sm">
                                            <option value="">Select</option>
                                            <option>Bag</option>
                                            <option>Box</option>
                                            <option>Bucket</option>
                                            <option>Drum</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="shipper_size[]" class="form-control form-control-sm shipper_size">
                                    </td>
                                    <td>
                                        <input type="number" name="unit_in_shipper[]" class="form-control form-control-sm unit_in_shipper" readonly>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm packing-states" multiple>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="packing_status[]" class="packing_status_toggle" value="1" checked>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger removeRow">×</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </table>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">UPDATE</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">CANCEL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).on('input', '.packing_value, .shipper_size', function () {
    let row = $(this).closest('tr');
    let packingValue = parseFloat(row.find('.packing_value').val()) || 0;
    let shipperSize = parseFloat(row.find('.shipper_size').val()) || 0;
    row.find('.unit_in_shipper').val(packingValue > 0 && shipperSize > 0 ? Math.floor((shipperSize * 1000) / packingValue) : '');
});
$('.select2').select2({ width: '100%' });
// Add row
// $('#addRow').click(function () {
//     let row = $('#packingTable tbody tr:first').clone();
//     row.find('input').val('');
//     row.find('select').val('');
//     row.find('.packing_status_toggle').prop('checked', true);
//     $('#packingTable tbody').append(row);
// });
let packingIndex = {{ $product->packings->count() }};

$('#addRow').click(function(){
    // Clone hidden template
    let row = $('#packingRowTemplate').clone().removeAttr('id').removeClass('d-none');

    // Reset input/select values
    row.find('input').val('');
    row.find('select').val(null);

    // Set packing_states name with new index
    row.find('.packing-states').attr('name', `packing_states[${packingIndex}][]`);

    // Reset checkbox
    row.find('.packing_status_toggle').prop('checked', true);

    // Append row
    $('#packingTable tbody').append(row);

    // Initialize Select2
    initSelect2(row);

    packingIndex++;
});

// Function to init select2
function initSelect2(scope){
    scope.find('.packing-states').select2({ width:'100%', placeholder:'Select States' });
}

// Initialize Select2 for existing rows
$('#packingTable tbody tr').each(function(){
    initSelect2($(this));
});

// Remove row
$(document).on('click', '.removeRow', function () {
    if ($('#packingTable tbody tr').length > 1) {
        $(this).closest('tr').remove();
    }
});

// Frontend validation
$('#productForm').submit(function(e){
    let valid = true;
    $('.error-text').text('');

    // Product fields
    if(!$('input[name="product_name"]').val().trim()) { $('.product_name_error').text('Product Name is required'); valid=false; }
    if(!$('input[name="technical_name"]').val().trim()) { $('.technical_name_error').text('Technical Name is required'); valid=false; }
    if(!$('input[name="item_code"]').val().trim()) { $('.item_code_error').text('Item Code is required'); valid=false; }
    if(!$('select[name="product_category_id"]').val()) { $('.product_category_id_error').text('Select Category'); valid=false; }
    if(!$('select[name="master_packing"]').val()) { $('.master_packing_error').text('Select master packing'); valid=false; }
    if(!$('input[name="shipper_gross_weight"]').val().trim()) { $('.shipper_gross_weight_error').text('Shipper Gross Weight is required'); valid=false; }

    // Packing rows
    $('#packingTable tbody tr').each(function(){
        let $row = $(this);
        if(!$row.find('.packing_value').val()) { $row.find('.packing_value_error').text('Required'); valid=false; }
        if(!$row.find('select[name="packing_size[]"]').val()) { $row.find('.packing_size_error').text('Required'); valid=false; }
        if(!$row.find('select[name="shipper_type[]"]').val()) { $row.find('.shipper_type_error').text('Required'); valid=false; }
        if(!$row.find('.shipper_size').val()) { $row.find('.shipper_size_error').text('Required'); valid=false; }
        if(!$row.find('.packing_status_toggle').is(':checked')) { /* optional: require at least one status active */ }
    });

    if(!valid) e.preventDefault();
});
</script>
@endpush
