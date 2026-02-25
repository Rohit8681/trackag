@extends('admin.layout.layout')
@section('title', 'Edit Product | Trackag')
<style>
.state-box{
    border-radius:12px;
    border:1px solid #e5e7eb;
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}
.state-header{
    padding:14px;
    background:linear-gradient(135deg,#4e73df,#224abe);
    color:#fff;
}
.state-body{
    padding:15px;
    background:#f9fafb;
}
.option-item{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:10px;
}
.switch{
    position:relative;
    display:inline-block;
    width:45px;
    height:22px;
}
.switch input{opacity:0;}
.slider{
    position:absolute;
    top:0;left:0;right:0;bottom:0;
    background:#ccc;
    border-radius:34px;
}
.slider:before{
    content:"";
    position:absolute;
    height:16px;width:16px;
    left:3px;bottom:3px;
    background:#fff;
    border-radius:50%;
    transition:.4s;
}
.switch input:checked + .slider{
    background:#28a745;
}
.switch input:checked + .slider:before{
    transform:translateX(22px);
}
</style>
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
                        <div class="col-md-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="product_name"
                                   class="form-control"
                                   value="{{ old('product_name', $product->product_name) }}">
                            <span class="text-danger error-text product_name_error"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Technical Name <span class="text-danger">*</span></label>
                            <input type="text" name="technical_name"
                                   class="form-control"
                                   value="{{ old('technical_name', $product->technical_name) }}">
                            <span class="text-danger error-text technical_name_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Product Category </label>
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
                            <label class="form-label">Item Code</label>
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
                        {{-- <div class="col-md-3">
                            <label class="form-label">Product States <span class="text-danger">*</span></label>
                            <select name="product_states[]" class="form-select select2" multiple required>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ $product->productStates->pluck('state_id')->contains($state->id) ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-md-3">
                            <label class="form-label">Master Packing </label>
                            <select name="master_packing" class="form-select" required>
                                <option value="">-- Select Master Packing --</option>
                                <option value="Yes" {{ $product->master_packing == "Yes" ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $product->master_packing == "No" ? 'selected' : '' }}>No</option>
                            </select>
                            <span class="text-danger error-text master_packing_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">GST<span class="text-danger">*</span></label>
                            <select name="gst" class="form-select" required>
                                <option value="0" {{ $product->gst == "0" ? 'selected' : '' }}>0%</option>
                                <option value="12" {{ $product->gst == "12" ? 'selected' : '' }}>12%</option>
                                <option value="18" {{ $product->gst == "18" ? 'selected' : '' }}>18%</option>
                                <option value="24" {{ $product->gst == "24" ? 'selected' : '' }}>24%</option>
                            </select>
                            <span class="text-danger error-text gst_error"></span>
                        </div>
                        
                    </div>

                    <hr>
                    <h5 class="fw-bold mb-3">State Wise Configuration</h5>

                    <div class="row">
                    @foreach($states as $state)

                    @php
                        $existingState = $product->productStates->firstWhere('state_id', $state->id);
                    @endphp

                    <div class="col-lg-4 col-md-6 mb-4">

                        <div class="state-box">

                            <div class="state-header d-flex justify-content-between align-items-center">
                                <span class="state-name">{{ $state->name }}</span>

                                <label class="switch">
                                    <input type="checkbox"
                                        class="state-toggle"
                                        name="state_config[{{ $state->id }}][enabled]"
                                        {{ $existingState ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="state-body {{ $existingState ? '' : 'd-none' }}">

                                <div class="option-item">
                                    <input type="checkbox"
                                        name="state_config[{{ $state->id }}][rpl]"
                                        {{ $existingState && $existingState->is_rpl ? 'checked' : '' }}>
                                    <span>Debit</span>
                                </div>

                                <div class="option-item">
                                    <input type="checkbox"
                                        name="state_config[{{ $state->id }}][ncr]"
                                        {{ $existingState && $existingState->is_ncr ? 'checked' : '' }}>
                                    <span>Cash</span>
                                </div>

                                {{-- <div class="option-item">
                                    <input type="checkbox"
                                        name="state_config[{{ $state->id }}][advance]"
                                        {{ $existingState && $existingState->is_advance ? 'checked' : '' }}>
                                    <span>Advance Available</span>
                                </div> --}}

                            </div>

                        </div>

                    </div>
                    @endforeach
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
                                        <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="packing_value[]"
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
                                        <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="shipper_size[]"
                                               value="{{ $pack->shipper_size }}"
                                               class="form-control form-control-sm shipper_size">
                                        <span class="text-danger error-text shipper_size_error"></span>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value" name="unit_in_shipper[]"
                                               value="{{ $pack->unit_in_shipper }}"
                                               class="form-control form-control-sm unit_in_shipper"
                                               readonly>
                                    </td>
                                    <td>
                                        <select name="packing_states[{{ $index }}][]" class="form-select form-select-sm packing-states" multiple>
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
                                        <select name="packing_states[__INDEX__][]" class="form-select form-select-sm packing-states" multiple>
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

let packingIndex = {{ $product->packings->count() }};

/* -----------------------------
   SELECT2 INIT
--------------------------------*/
function initSelect2(scope){
    scope.find('.packing-states').select2({
        width:'100%',
        placeholder:'Select States'
    });
}

/* -----------------------------
   INITIAL LOAD
--------------------------------*/
$(document).ready(function(){

    // Init select2 for all existing rows
    $('#packingTable tbody tr').each(function(){
        initSelect2($(this));
    });

    filterPackingStates(); // 🔥 apply filter on page load
});

/* -----------------------------
   ADD ROW
--------------------------------*/
$('#addRow').click(function(){

    let row = $('#packingRowTemplate').clone().removeAttr('id').removeClass('d-none');

    row.find('input').val('');
    row.find('select').val(null);

    // Replace index in name
    row.find('.packing-states')
       .attr('name', `packing_states[${packingIndex}][]`);

    row.find('.packing_status_toggle').prop('checked', true);

    $('#packingTable tbody').append(row);

    initSelect2(row);
    filterPackingStates(); // 🔥 apply filter to new row

    packingIndex++;
});

/* -----------------------------
   REMOVE ROW
--------------------------------*/
$(document).on('click','.removeRow',function(){
    if($('#packingTable tbody tr').length > 1){
        $(this).closest('tr').remove();
    }
});

/* -----------------------------
   UNIT CALCULATION
--------------------------------*/
$(document).on(
'input change',
'.packing_value, .shipper_size, select[name="packing_size[]"]',
function () {

    let row = $(this).closest('tr');

    let packingValue = parseFloat(row.find('.packing_value').val()) || 0;
    let shipperSize  = parseFloat(row.find('.shipper_size').val()) || 0;
    let packingSize  = row.find('select[name="packing_size[]"]').val();

    let units = '';

    if (packingValue > 0 && shipperSize > 0) {

        if (packingSize === 'KG' || packingSize === 'LTR') {
            units = shipperSize / packingValue;
        } else {
            units = (shipperSize * 1000) / packingValue;
        }

        row.find('.unit_in_shipper').val(Math.floor(units));
    } else {
        row.find('.unit_in_shipper').val('');
    }
});

/* --------------------------------------------------
   🔥 STATE WISE FILTER LOGIC
---------------------------------------------------*/
function filterPackingStates(){

    let enabledStates = [];

    $('.state-toggle:checked').each(function(){
        let stateId = $(this).attr('name').match(/\d+/)[0];
        enabledStates.push(stateId);
    });

    $('.packing-states').each(function(){

        let select = $(this);

        select.find('option').each(function(){

            let optionStateId = $(this).val();

            if(enabledStates.includes(optionStateId)){
                $(this).prop('disabled', false).show();
            } else {
                $(this).prop('selected', false);
                $(this).prop('disabled', true).hide();
            }

        });

        select.trigger('change.select2');
    });
}

/* --------------------------------------------------
   STATE TOGGLE CHANGE
---------------------------------------------------*/
$(document).on('change','.state-toggle',function(){

    let box = $(this).closest('.state-box');

    if($(this).is(':checked')){
        box.find('.state-body').removeClass('d-none');
    } else {
        box.find('.state-body').addClass('d-none');
        box.find('.state-body input').prop('checked', false);
    }

    filterPackingStates(); // 🔥 important
});

/* --------------------------------------------------
   FORM VALIDATION
---------------------------------------------------*/
$('#productForm').submit(function(e){

    let valid = true;
    $('.error-text').text('');

    if(!$('input[name="product_name"]').val().trim()){
        $('.product_name_error').text('Product Name is required');
        valid = false;
    }

    if(!$('input[name="technical_name"]').val().trim()){
        $('.technical_name_error').text('Technical Name is required');
        valid = false;
    }

    if(!$('input[name="shipper_gross_weight"]').val().trim()){
        $('.shipper_gross_weight_error').text('Shipper Gross Weight is required');
        valid = false;
    }

    $('#packingTable tbody tr').each(function(){
        let $row = $(this);

        if(!$row.find('.packing_value').val()){
            $row.find('.packing_value_error').text('Required');
            valid = false;
        }

        if(!$row.find('select[name="packing_size[]"]').val()){
            $row.find('.packing_size_error').text('Required');
            valid = false;
        }

        if(!$row.find('select[name="shipper_type[]"]').val()){
            $row.find('.shipper_type_error').text('Required');
            valid = false;
        }

        if(!$row.find('.shipper_size').val()){
            $row.find('.shipper_size_error').text('Required');
            valid = false;
        }
    });

    if(!valid) e.preventDefault();
});

</script>
@endpush
