@extends('admin.layout.layout')
@section('title', 'Create Product | Trackag')
<style>
    .state-box {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: 0.3s ease;
        overflow: hidden;
    }

    .state-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .state-header {
        padding: 14px 16px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
    }

    .state-name {
        font-weight: 600;
    }

    .state-body {
        padding: 15px;
        background: #f9fafb;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    /* Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 45px;
        height: 22px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background: white;
        transition: .4s;
        border-radius: 50%;
    }

    .switch input:checked+.slider {
        background: #28a745;
    }

    .switch input:checked+.slider:before {
        transform: translateX(22px);
    }
</style>
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
                                <input type="text" name="product_name" class="form-control" required>
                                <span class="text-danger error-text product_name_error"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Technical Name <span class="text-danger">*</span></label>
                                <input type="text" name="technical_name" class="form-control" required>
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
                                <label class="form-label">Shipper Gross Weight (KG) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="shipper_gross_weight" class="form-control" required>
                                <span class="text-danger error-text shipper_gross_weight_error"></span>
                            </div>

                            {{-- <div class="col-md-3">
                                <label class="form-label">Product States <span class="text-danger">*</span></label>
                                <select name="product_states[]" class="form-select select2" multiple required>
                                    @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

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
                        <h5 class="fw-bold mb-3">State Wise Configuration</h5>

                        <div class="row">
                            @foreach($states as $state)
                                <div class="col-lg-4 col-md-6 mb-4">

                                    <div class="state-box">

                                        <div class="state-header d-flex justify-content-between align-items-center">
                                            <span class="state-name">{{ $state->name }}</span>

                                            <label class="switch">
                                                <input type="checkbox" class="state-toggle"
                                                    name="state_config[{{ $state->id }}][enabled]">
                                                <span class="slider"></span>
                                            </label>
                                        </div>

                                        <div class="state-body d-none">

                                            <div class="option-item">
                                                <input type="checkbox" name="state_config[{{ $state->id }}][rpl]">
                                                <span>Debit</span>
                                            </div>

                                            <div class="option-item">
                                                <input type="checkbox" name="state_config[{{ $state->id }}][ncr]">
                                                <span>Cash</span>
                                            </div>

                                            {{-- <div class="option-item">
                                                <input type="checkbox" name="state_config[{{ $state->id }}][advance]">
                                                <span>Advance Available</span>
                                            </div> --}}

                                        </div>

                                    </div>

                                </div>
                            @endforeach
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
                                        <input type="number" step="0.01" min="0" step="0.01"
                                            oninput="this.value = this.value < 0 ? 0 : this.value" name="packing_value[]"
                                            class="form-control form-control-sm packing_value">
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
                                        <input type="number" step="0.01" min="0" step="0.01"
                                            oninput="this.value = this.value < 0 ? 0 : this.value" name="shipper_size[]"
                                            class="form-control form-control-sm shipper_size">
                                        <span class="text-danger error-text shipper_size_error"></span>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" step="0.01"
                                            oninput="this.value = this.value < 0 ? 0 : this.value" name="unit_in_shipper[]"
                                            class="form-control form-control-sm unit_in_shipper" readonly>
                                    </td>
                                    <td>
                                        <select name="packing_states[0][]"
                                            class="form-select form-select-sm packing-states packing-state-dropdown"
                                            multiple required>
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
                                        <input type="number" step="0.01" min="0" step="0.01"
                                            oninput="this.value = this.value < 0 ? 0 : this.value" name="packing_value[]"
                                            class="form-control form-control-sm packing_value">
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
                                        <input type="number" step="0.01" min="0" step="0.01"
                                            oninput="this.value = this.value < 0 ? 0 : this.value" name="shipper_size[]"
                                            class="form-control form-control-sm shipper_size">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" step="0.01"
                                            oninput="this.value = this.value < 0 ? 0 : this.value" name="unit_in_shipper[]"
                                            class="form-control form-control-sm unit_in_shipper" readonly>
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

        /* -----------------------------
           SELECT2 INIT
        --------------------------------*/
        function initSelect2(scope) {
            scope.find('.packing-states').select2({
                width: '100%',
                placeholder: 'Select States'
            });
        }

        $(document).ready(function () {
            initSelect2($('#packingTable tbody tr:first'));
            filterPackingStates(); // initial filter
        });

        /* -----------------------------
           ADD ROW
        --------------------------------*/
        $('#addRow').click(function () {

            let row = $('#packingRowTemplate').clone().removeAttr('id');

            row.find('.packing-states')
                .attr('name', `packing_states[${packingIndex}][]`)
                .addClass('packing-state-dropdown');

            $('#packingTable tbody').append(row);

            initSelect2(row);
            filterPackingStates(); // apply filter on new row

            packingIndex++;
        });

        /* -----------------------------
           REMOVE ROW
        --------------------------------*/
        $(document).on('click', '.removeRow', function () {
            if ($('#packingTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });

        /* -----------------------------
           UNIT CALCULATION
        --------------------------------*/
        $(document).on('input change',
            '.packing_value, .shipper_size, select[name="packing_size[]"]',
            function () {

                let row = $(this).closest('tr');

                let packingValue = parseFloat(row.find('.packing_value').val()) || 0;
                let shipperSize = parseFloat(row.find('.shipper_size').val()) || 0;
                let packingSize = row.find('select[name="packing_size[]"]').val();

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
        function filterPackingStates() {

            let enabledStates = [];

            $('.state-toggle:checked').each(function () {
                let stateId = $(this).attr('name').match(/\d+/)[0];
                enabledStates.push(stateId);
            });

            $('.packing-states').each(function () {

                let select = $(this);

                select.find('option').each(function () {

                    let optionStateId = $(this).val();

                    if (enabledStates.includes(optionStateId)) {
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
        $(document).on('change', '.state-toggle', function () {

            let box = $(this).closest('.state-box');

            if ($(this).is(':checked')) {
                box.find('.state-body').removeClass('d-none');
            } else {
                box.find('.state-body').addClass('d-none');
                box.find('.state-body input').prop('checked', false);
            }

            filterPackingStates(); // important
        });

        /* --------------------------------------------------
           FORM VALIDATION
        ---------------------------------------------------*/
        $('#productForm').submit(function (e) {

            let valid = true;
            $('.error-text').text('');

            if (!$('input[name="product_name"]').val().trim()) {
                $('.product_name_error').text('Product Name is required');
                valid = false;
            }

            if (!$('input[name="technical_name"]').val().trim()) {
                $('.technical_name_error').text('Technical Name is required');
                valid = false;
            }

            if (!$('input[name="shipper_gross_weight"]').val().trim()) {
                $('.shipper_gross_weight_error').text('Shipper Gross Weight is required');
                valid = false;
            }

            $('#packingTable tbody tr').each(function () {
                let $row = $(this);

                if (!$row.find('.packing_value').val()) {
                    $row.find('.packing_value_error').text('Required');
                    valid = false;
                }

                if (!$row.find('select[name="packing_size[]"]').val()) {
                    $row.find('.packing_size_error').text('Required');
                    valid = false;
                }

                if (!$row.find('select[name="shipper_type[]"]').val()) {
                    $row.find('.shipper_type_error').text('Required');
                    valid = false;
                }

                if (!$row.find('.shipper_size').val()) {
                    $row.find('.shipper_size_error').text('Required');
                    valid = false;
                }
            });

            if (!valid) e.preventDefault();
        });
    </script>
@endpush