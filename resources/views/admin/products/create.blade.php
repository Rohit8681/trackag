@extends('admin.layout.layout')
@section('title', 'Create Product | Trackag')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Product</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('products.index') }}">Product Master</a>
                        </li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">Add New Product</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('products.store') }}" id="productForm">
                    @csrf

                    {{-- PRODUCT INFO --}}
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" class="form-control">
                            <span class="text-danger error-text product_name_error"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Technical Name <span class="text-danger">*</span></label>
                            <input type="text" name="technical_name" class="form-control">
                            <span class="text-danger error-text technical_name_error"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Product Category </label>
                            <select name="product_category_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text product_category_id_error"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Item Code </label>
                            <input type="text" name="item_code" class="form-control">
                            <span class="text-danger error-text item_code_error"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Shipper Gross Weight (KG) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="shipper_gross_weight" class="form-control">
                            <span class="text-danger error-text shipper_gross_weight_error"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Product States <span class="text-danger">*</span></label>
                            <select name="product_states[]" class="form-select select2" multiple required>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Master Packing </label>
                            <select name="master_packing" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            <span class="text-danger error-text master_packing_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">GST<span class="text-danger">*</span></label>
                            <select name="gst" class="form-select" required>
                                <option value="0">0%</option>
                                <option value="12">12%</option>
                                <option value="18">18%</option>
                                <option value="24">24%</option>
                            </select>
                            <span class="text-danger error-text gst_error"></span>
                        </div>

                    </div>

                    <hr>

                    {{-- PACKING SECTION --}}
                    <h6 class="fw-bold mb-2">Packing Details</h6>

                    <table class="table table-bordered table-sm" id="packingTable">
                        <thead class="table-light">
                        <tr>
                            <th>Packing Value</th>
                            <th>Size</th>
                            <th>Shipper Type</th>
                            <th>Shipper Size</th>
                            <th>No of Units</th>
                            <th>States</th>
                            <th>Status</th>
                            <th width="60">
                                <button type="button" class="btn btn-success" id="addRow">+</button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="packing_value[]" class="form-control form-control-sm packing_value">
                                <span class="text-danger error-text packing_value_error"></span>
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
                                <span class="text-danger error-text packing_size_error"></span>
                            </td>
                            <td>
                                <select name="shipper_type[]" class="form-select form-select-sm">
                                    <option value="">Select</option>
                                    <option>Bag</option>
                                    <option>Box</option>
                                    <option>Bucket</option>
                                    <option>Drum</option>
                                </select>
                                <span class="text-danger error-text shipper_type_error"></span>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="shipper_size[]" class="form-control form-control-sm shipper_size">
                                <span class="text-danger error-text shipper_size_error"></span>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="unit_in_shipper[]" class="form-control form-control-sm unit_in_shipper" readonly>
                            </td>
                            <td>
                                <select name="packing_states[0][]" class="form-select form-select-sm packing-states" multiple required>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" name="packing_status[]" value="1" checked>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger removeRow">×</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    {{-- Hidden template --}}
                    <table class="d-none">
                        <tbody>
                        <tr id="packingRowTemplate">
                            <td>
                                <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="packing_value[]" class="form-control form-control-sm packing_value">
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
                                <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="shipper_size[]" class="form-control form-control-sm shipper_size">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="unit_in_shipper[]" class="form-control form-control-sm unit_in_shipper" readonly>
                            </td>
                            <td>
                                <select class="form-select form-select-sm packing-states" multiple>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" name="packing_status[]" value="1" checked>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger removeRow">×</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <button class="btn btn-primary">SAVE</button>
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
let packingIndex = 1;

function initSelect2(scope){
    scope.find('.packing-states').select2({ width:'100%', placeholder:'Select States' });
}

$(document).ready(function(){
    $('.select2').select2({ width:'100%' });
    initSelect2($('#packingTable tbody tr:first'));
});

$('#addRow').click(function(){
    let row = $('#packingRowTemplate').clone().removeAttr('id');
    row.find('.packing-states').attr('name', `packing_states[${packingIndex}][]`);
    $('#packingTable tbody').append(row);
    initSelect2(row);
    packingIndex++;
});

$(document).on('click','.removeRow',function(){
    if($('#packingTable tbody tr').length > 1){
        $(this).closest('tr').remove();
    }
});

$(document).on('input change', '.packing_value, .shipper_size, select[name="packing_size[]"]', function () {

    let row = $(this).closest('tr');

    let packingValue = parseFloat(row.find('.packing_value').val()) || 0;
    let shipperSize  = parseFloat(row.find('.shipper_size').val()) || 0;
    let packingSize  = row.find('select[name="packing_size[]"]').val();

    let units = '';

    if (packingValue > 0 && shipperSize > 0) {

        // 👉 KG / LTR logic
        if (packingSize === 'KG' || packingSize === 'LTR') {
            units = shipperSize / packingValue;
        }
        // 👉 GM / ML / UNIT logic (old logic)
        else {
            units = (shipperSize * 1000) / packingValue;
        }

        row.find('.unit_in_shipper').val(Math.floor(units));
    } else {
        row.find('.unit_in_shipper').val('');
    }
});

$('#productForm').submit(function(e){
    let valid = true;
    $('.error-text').text(''); // clear previous errors

    // Product info
    if(!$('input[name="product_name"]').val().trim()) {
        $('.product_name_error').text('Product Name is required');
        valid = false;
    }
    if(!$('input[name="technical_name"]').val().trim()) {
        $('.technical_name_error').text('Technical Name is required');
        valid = false;
    }
    if(!$('input[name="shipper_gross_weight"]').val().trim()) {
        $('.shipper_gross_weight_error').text('Shipper Gross Weight is required');
        valid = false;
    }
    // if(!$('input[name="item_code"]').val().trim()) {
    //     $('.item_code_error').text('Item Code is required');
    //     valid = false;
    // }
    // if(!$('select[name="product_category_id"]').val()) {
    //     $('.product_category_id_error').text('Select Product Category');
    //     valid = false;
    // }
    // if(!$('select[name="master_packing"]').val()) {
    //     $('.master_packing_error').text('Select Master Packing');
    //     valid = false;
    // }

    // Packing rows
    $('#packingTable tbody tr').each(function(index, row){
        let $row = $(row);
        if(!$row.find('.packing_value').val()) {
            $row.find('.packing_value_error').text('Packing Value required');
            valid = false;
        }
        if(!$row.find('select[name="packing_size[]"]').val()) {
            $row.find('.packing_size_error').text('Select Size');
            valid = false;
        }
        if(!$row.find('select[name="shipper_type[]"]').val()) {
            $row.find('.shipper_type_error').text('Select Shipper Type');
            valid = false;
        }
        if(!$row.find('.shipper_size').val()) {
            $row.find('.shipper_size_error').text('Shipper Size required');
            valid = false;
        }
    });

    if(!valid) e.preventDefault(); // stop submit
});
</script>
@endpush
