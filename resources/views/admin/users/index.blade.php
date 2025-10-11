@extends('admin.layout.layout')

@section('content')
<style>
    td small strong {
        color: #444;
        min-width: 80px;
        display: inline-block;
    }
    td small {
        color: #555;
    }
    .table td {
        vertical-align: middle;
    }
</style>
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Users</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">User Management</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">User Control Panel</h3>
                            <a href="{{ route('users.create') }}" style="float: right;" class="btn btn-sm btn-primary">
                                <i class="fas fa-user-plus me-1"></i> Add New User
                            </a>
                        </div>

                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="mb-3">
                            <form method="GET" action="{{ route('users.index') }}" id="filterForm">
                                <div class="row g-2 align-items-end">

                                    <!-- State -->
                                    <div class="col-md-2">
                                        <label class="form-label">State</label>
                                        <select name="state_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Employee Name -->
                                    <div class="col-md-2">
                                        <label class="form-label">Employee Name</label>
                                        <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Enter name">
                                    </div>

                                    <!-- Designation -->
                                    <div class="col-md-2">
                                        <label class="form-label">Designation</label>
                                        <select name="designation_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($designations as $desig)
                                                <option value="{{ $desig->id }}" {{ request('designation_id') == $desig->id ? 'selected' : '' }}>
                                                    {{ $desig->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Mobile No -->
                                    <div class="col-md-2">
                                        <label class="form-label">Mobile No</label>
                                        <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control" placeholder="Enter mobile">
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-2">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2 px-3">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary px-3">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    </div>

                                </div>
                            </form>
                        </div>


                            <div class="table-responsive" style="max-height: 600px;">
                                <table id="users-table" class="table table-hover align-middle mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Employee Name</th>
                                            <th>Address</th>
                                            <th>Other Info</th>
                                            <th>It Self Sale</th>
                                            <th>Salary</th>
                                            <th>TA/DA Info</th>
                                            <th>Depo Access</th>
                                            <th>State Access</th>
                                            <th>Status</th>
                                            <th>Reset Password</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            @php
                                                $loggedInUserId = Auth::id();
                                                $isOnline = $user->last_seen && \Carbon\Carbon::parse($user->last_seen)->gt(now()->subMinutes(5));
                                                $rowClass = $user->id === $loggedInUserId ? 'table-primary' : ($isOnline ? 'table-success' : (!$user->is_active ? 'table-secondary' : ''));
                                                $gender = strtolower($user->gender ?? '');
                                                $defaultImage = $gender === 'female' ? asset('admin/images/avatar-female.png') : asset('admin/images/avatar-male.png');
                                                $userImage = $user->image ? asset('storage/' . $user->image) : $defaultImage;
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $userImage }}" alt="avatar" class="rounded-circle me-2" width="45" height="45">
                                                        <div style="line-height: 1.3;">
                                                            <strong>{{ $user->name }}</strong><br>
                                                            <small><strong>Mobile:</strong> {{ $user->mobile ?? '-' }}</small><br>
                                                            <small><strong>Designation:</strong> {{ $user->designation->name ?? '-' }}</small>
                                                            @if ($user->id === $loggedInUserId)
                                                                <span class="badge bg-success ms-1">You</span>
                                                            @endif
                                                            <br>
                                                            <small><strong>Reporting To:</strong> {{ $user->reportingManager->name ?? '-' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="line-height: 1.3;">
                                                    <small><strong>State:</strong> {{ $user->state->name ?? '-' }}</small><br>
                                                    <small><strong>District:</strong> {{ $user->district->name ?? '-' }}</small><br>
                                                    <small><strong>Tehsil:</strong> {{ $user->tehsil->name ?? '-' }}</small><br>
                                                    <small><strong>Village:</strong> {{ $user->village ?? '-' }}</small>
                                                </td>

                                                <td style="line-height: 1.3;">
                                                    <small><strong>Joining Date:</strong> {{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('d-m-Y') : '-' }}</small><br>
                                                    <small><strong>Headquarter:</strong> {{ $user->headquarter ?? '-' }}</small><br>
                                                    
                                                    <small><strong>Roles:</strong>
                                                        @if ($user->roles && count($user->roles))
                                                            @foreach ($user->getRoleNames() as $role)
                                                                <span class="badge bg-info text-dark">{{ $role }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No Role</span>
                                                        @endif
                                                    </small><br>
                                                    
                                                    <small><strong>Depo:</strong> {{ $user->depos?->depo_name ?? '-' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $user->is_self_sale ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $user->is_self_sale ? 'Yes' : 'No' }}
                                                    </span>
                                                </td>
                                                <td class="text-center"><i class="fas fa-cog text-muted"></i></td>
                                               <td class="text-center">
                                                <i class="fas fa-cog text-muted slab_access" style="cursor:pointer;" data-user-id="{{ $user->id }}" data-user-slab="{{ $user->slab }}"></i>
                                                </td>
                                                <td class="text-center"><i class="fas fa-cog text-muted depo_access" style="cursor:pointer;" data-user-id="{{ $user->id }}"></i></td>
                                                <td class="text-center"><i class="fas fa-cog text-muted state_access" style="cursor:pointer;" data-user-id="{{ $user->id }}"></i></td>

                                                
                                                <td class="text-center">
                                                    @if ($user->is_active)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i> Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-times-circle me-1"></i> Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <i class="fas fa-cog text-primary reset-password" style="cursor:pointer;" data-user-id="{{ $user->id }}"></i>
                                                </td>   
                                                <td class="text-center">
                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('users.toggle', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center text-muted">No users found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="resetPasswordModalLabel">Reset User Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="resetPasswordForm">
              @csrf
              <input type="hidden" name="user_id" id="modalUserId">
              <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="password" required>
              </div>
            </form>
            <div id="resetPasswordMessage"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="resetPasswordBtn">Reset Password</button>
          </div>
        </div>
      </div>
    </div>

    

{{-- Depo Access Modal --}}
<div class="modal fade" id="depoAccessModal" tabindex="-1" aria-labelledby="depoAccessModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="depoAccessModalLabel">Depo Access</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="depoAccessForm">
          @csrf
          <input type="hidden" name="user_id" id="depoModalUserId">

          {{-- State Dropdown --}}
          <div class="mb-3">
            <label for="stateId" class="form-label">State Name</label>
            <select class="form-select" name="state_id" id="stateId" required>
                <option value="">-- Select State --</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
            </select>
          </div>

          {{-- Depo Multiple Select --}}
          <div class="mb-3">
            <label for="depoId" class="form-label">Depo Name</label>
            <select class="form-select" name="depo_id[]" id="depoId" multiple="multiple" required></select>
          </div>

        </form>
        <div id="depoAccessMessage"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveDepoAccessBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="stateAccessModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assign States</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="stateAccessForm">
          @csrf
          <input type="hidden" name="user_id" id="stateModalUserId">

          <div class="mb-3">
            <label class="form-label">Select States</label>
            <select name="state_ids[]" id="stateIds" class="form-select" multiple required>
              @foreach($states as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
              @endforeach
            </select>
          </div>
        </form>
        <div id="stateAccessMessage"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" id="saveStateBtn">Save</button>
      </div>
    </div>
  </div>
</div>
{{-- TA/DA Slab Modal --}}
<div class="modal fade" id="slabAccessModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Assign TA/DA Slab</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="slabAccessForm">
          @csrf
          <input type="hidden" name="user_id" id="slabModalUserId">

          <!-- Slab Selection -->
          <div class="mb-4">
            <label class="form-label fw-bold">Select Slab</label>
            <select name="slab" id="slabSelect" class="form-select" required>
              <option value="">-- Select Slab --</option>
              <option value="Individual">TA/DA Slab - Individual</option>
              <option value="Slab Wise">TA/DA - Slab Wise</option>
            </select>
          </div>

          <!-- Slab Details -->
          <div id="slabTables" class="d-none">
            <div class="row g-3 mb-4">
              <!-- Max Monthly Travel -->
              <div class="col-md-6">
                <label class="form-label fw-bold">Max Monthly Travel K.M.</label>
                <select name="max_monthly_travel" class="form-select">
                  <option value="">-- Select --</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>

              <!-- KM -->
              <div class="col-md-6">
                <label class="form-label fw-bold">KM</label>
                <input type="number" name="km" class="form-control" placeholder="Enter KM">
              </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Approved Bills in DA</label>
                    <select name="approved_bills_in_da[]" id="approvedBills" class="form-select" multiple>
                        <option value="Petrol">Petrol</option>
                        <option value="Food">Food</option>
                        <option value="Accommodation">Accommodation</option>
                        <option value="Travel">Travel</option>
                        <option value="Courier">Courier</option>
                        <option value="Hotel">Hotel</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="col-md-6" id="designation_div">
                    <label class="form-label fw-bold">Designation</label>
                    <select name="designation_id" class="form-select">
                        @foreach($designations as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Vehicle & Tour Slabs -->
            <div class="row g-3">
              <!-- Vehicle Type Table -->
              <div class="col-md-6">
                <div class="card shadow-sm">
                  <div class="card-header bg-secondary text-white fw-bold" >Vehicle Type <span id="vehicle_slab_title_name"></span></div>
                  <div class="card-body p-0">
                    <table class="table table-bordered mb-0 text-center">
                      <thead class="table-light">
                        <tr>
                          <th>Vehicle Type</th>
                          <th>Travelling Allow per KM</th>
                        </tr>
                      </thead>
                      <tbody id="vehicleSlabBody"></tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Tour Type Table -->
              <div class="col-md-6">
                <div class="card shadow-sm">
                  <div class="card-header bg-secondary text-white fw-bold">Tour Type <span id="tour_slab_title_name"></span></div>
                  <div class="card-body p-0">
                    <table class="table table-bordered mb-0 text-center">
                      <thead class="table-light">
                        <tr>
                          <th>Tour Type</th>
                          <th>D.A. Amount</th>
                        </tr>
                      </thead>
                      <tbody id="tourSlabBody"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>

        <div id="slabAccessMessage" class="mt-3"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveSlabBtn">Save</button>
      </div>
    </div>
  </div>
</div>




</main>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    $('#slabAccessModal').on('shown.bs.modal', function () {
        $('#approvedBills').select2({
            placeholder: "Select Approved Bills",
            width: '100%',
            dropdownParent: $('#slabAccessModal') // important for Bootstrap modal
        });
    });


    $('#depoAccessModal').on('shown.bs.modal', function () {
        $('#depoId').select2({
            placeholder: "Select Depos",
            width: '100%',
            dropdownParent: $('#depoAccessModal') // VERY IMPORTANT for Bootstrap modal
        });
    });
    $('#stateAccessModal').on('shown.bs.modal', function () {
        $('#stateIds').select2({
            placeholder: "Select State",
            width: '100%',
            dropdownParent: $('#stateAccessModal') 
        });
    });
    $('.slab_access').click(function() {
        let userId = $(this).data('user-id');
        let userSlab = $(this).data('user-slab');
        $('#slabModalUserId').val(userId);
        $('#slabSelect').val(userSlab).trigger('change');
        $('#slabAccessMessage').html('');
        $('#slabAccessModal').modal('show');
    });

    $('#slabSelect').change(function() {
        var slabType = $(this).val();
        var userId = $('#slabModalUserId').val();
        
        $('#slabTables').addClass('d-none');
        $('#vehicleSlabBody, #tourSlabBody').empty();

        if (!slabType) return;

        if(slabType == "Slab Wise"){
            $('#vehicle_slab_title_name').html('(Slab Wise)');
            $('#tour_slab_title_name').html('(Slab Wise)');
            $('#designation_div').show();
        }else{
            $('#vehicle_slab_title_name').html('(Individual)');
            $('#tour_slab_title_name').html('(Individual)');
            $('#designation_div').hide();
        }

        $.ajax({
            url: '/admin/get-user-slab',
            type: 'GET',
            data: { user_id: userId, slab: slabType },
            success: function(res) {
                $('#slabTables').removeClass('d-none');

                let readOnly = (slabType === "Slab Wise") ? 'readonly' : '';

                // ---------- VEHICLE SLABS ----------
                $.each(res.vehicle_types, function(i, vt) {
                    let slabData = res.vehicle_slabs.find(s => s.vehicle_type_id === vt.id);
                    
                    let amount = slabData ? slabData.travelling_allow_per_km : '';
                    $('#vehicleSlabBody').append(`
                        <tr>
                            <td>${vt.vehicle_type}</td>
                            <td>
                                <input type="hidden" name="vehicle_type_id[]" value="${vt.id}">
                                <input type="number" step="0.01" class="form-control text-center" 
                                    name="travelling_allow_per_km[]" value="${amount}" ${readOnly}>
                            </td>
                        </tr>
                    `);
                });

                // ---------- TOUR SLABS ----------
                $.each(res.tour_types, function(i, tt) {
                    let slabData = res.tour_slabs.find(s => s.tour_type_id === tt.id);
                    let amount = slabData ? slabData.da_amount : '';
                    $('#tourSlabBody').append(`
                        <tr>
                            <td>${tt.name}</td>
                            <td>
                                <input type="hidden" name="tour_type_id[]" value="${tt.id}">
                                <input type="number" step="0.01" class="form-control text-center" 
                                    name="da_amount[]" value="${amount}" ${readOnly}>
                            </td>
                        </tr>
                    `);
                });

                // Optional: populate general slab fields
                if(res.ta_da_slab) {
                    $('select[name="max_monthly_travel"]').val(res.ta_da_slab.max_monthly_travel).trigger('change');
                    $('input[name="km"]').val(res.ta_da_slab.km);
                    if(res.ta_da_slab.approved_bills_in_da){
                        let bills = res.ta_da_slab.approved_bills_in_da; // array of values
                        let select = $('#slabAccessModal select[name="approved_bills_in_da[]"]');

                        // Add missing options
                        bills.forEach(function(val){
                            if(select.find('option[value="'+val+'"]').length === 0){
                                select.append(new Option(val, val, true, true));
                            }
                        });

                        // Set selected values
                        select.val(bills).trigger('change');
                    }

                    $('select[name="designation_id"]').val(res.ta_da_slab.designation);
                }

                if (slabType === "Slab Wise") {
                    $('select[name="max_monthly_travel"]').prop('disabled', true);
                    $('input[name="km"]').prop('readonly', true);
                    $('select[name="approved_bills_in_da[]"]').prop('disabled', true).trigger('change.select2');
                    $('select[name="designation_id"]').prop('disabled', true);
                } else {
                    $('select[name="max_monthly_travel"]').prop('disabled', false);
                    $('input[name="km"]').prop('readonly', false);
                    $('select[name="approved_bills_in_da[]"]').prop('disabled', false).trigger('change.select2');
                    $('select[name="designation_id"]').prop('disabled', false);
                }
            }
        });
    });




    $('#saveSlabBtn').click(function(){
        let formData = $('#slabAccessForm').serialize();

        $.ajax({
            url: '/admin/save-user-slab',
            type: 'POST',
            data: formData,
            success: function(res){
                $('#slabAccessMessage').html('<div class="alert alert-success">Slab saved successfully!</div>');
                setTimeout(() => { $('#slabAccessModal').modal('hide'); }, 1500);
            },
            error: function(xhr){
                let errors = xhr.responseJSON.errors;
                let msg = '';
                $.each(errors, function(k,v){ msg += v[0]+'<br>'; });
                $('#slabAccessMessage').html('<div class="alert alert-danger">'+msg+'</div>');
            }
        });
    });
    // Open modal and set user id
    $('.reset-password').click(function() {
        let userId = $(this).data('user-id');
        $('#modalUserId').val(userId);
        $('#newPassword').val('');
        $('#resetPasswordMessage').html('');
        $('#resetPasswordModal').modal('show');
    });

    $('.state_access').click(function() {
        let userId = $(this).data('user-id');
        $('#stateModalUserId').val(userId);

        // reset select
        $('#stateIds').val(null).trigger('change');
        $('#stateAccessMessage').html('');

        $('#stateAccessModal').modal('show');

        // load existing user state access
        $.ajax({
            url: '/admin/get-user-state-access',
            type: 'GET',
            data: { user_id: userId },
            success: function(res){
                if(res.state_ids){
                    $('#stateIds').val(res.state_ids).trigger('change');
                }
            }
        });
    });

    $('#saveStateBtn').click(function(){
        let formData = $('#stateAccessForm').serialize();
        $.ajax({
            url: '/admin/save-user-state-access',
            type: 'POST',
            data: formData,
            success: function(res){
                $('#stateAccessMessage').html('<div class="alert alert-success">States saved successfully!</div>');
                setTimeout(() => { $('#stateAccessModal').modal('hide'); }, 1500);
            },
            error: function(){
                $('#stateAccessMessage').html('<div class="alert alert-danger">Error saving states.</div>');
            }
        });
    });

    $('.depo_access').click(function() {
        $('#depoAccessMessage').html('');
        let userId = $(this).data('user-id');
        $('#depoModalUserId').val(userId);

        // Reset form & depo dropdown
        $('#depoAccessForm')[0].reset();
        $('#depoId').empty().trigger('change');

        // Show modal
        $('#depoAccessModal').modal('show');

        // Optional: if you want to preselect state + depos
        $.ajax({
            url: 'get-user-depo-access', // New route
            type: 'GET',
            data: { user_id: userId },
            success: function(res) {
                if(res.userAccess) {
                    // Set state
                    $('#stateId').val(res.userAccess.state_id).trigger('change');

                    // Wait a bit for depos to load dynamically after state change
                    setTimeout(function() {
                        let selectedDepos = res.userAccess.depo_ids; // array
                        $('#depoId').val(selectedDepos).trigger('change');
                    }, 300); // 300ms delay for AJAX depos load
                }
            }
        });
    });


    $('#stateId').change(function() {
        let stateId = $(this).val();
        if(stateId){
            $.ajax({
                url: '{{ route("admin.get.depos") }}',
                type: 'GET',
                data: { state_id: stateId },
                success: function(data){
                    console.log(data); // check data is coming
                    // Clear old options
                    $('#depoId').empty();

                    // Add default placeholder
                    $('#depoId').append(new Option('-- Select Depos --', '', false, false));

                    // Append new options
                    data.forEach(function(depo){
                        let option = new Option(depo.depo_name, depo.id, false, false);
                        $('#depoId').append(option);
                    });

                    // Update Select2
                    $('#depoId').val(null).trigger('change');
                },
                error: function(err){
                    console.log(err);
                }
            });
        } else {
            $('#depoId').empty().val(null).trigger('change');
        }
    });

    $('#saveDepoAccessBtn').click(function() {
        let formData = $('#depoAccessForm').serialize();
        $.ajax({
            url: '{{ route("admin.save.depo.access") }}',
            type: 'POST',
            data: formData,
            success: function(res){
                $('#depoAccessMessage').html('<div class="alert alert-success">'+res.message+'</div>');
                setTimeout(function(){
                    $('#depoAccessModal').modal('hide');
                    $('#depoAccessMessage').html('');
                }, 1500);
            },
            error: function(xhr){
                let errors = xhr.responseJSON.errors;
                let msg = '';
                $.each(errors, function(k,v){ msg += v[0]+'<br>'; });
                $('#depoAccessMessage').html('<div class="alert alert-danger">'+msg+'</div>');
            }
        });
    });


    // Handle reset password AJAX
    $('#resetPasswordBtn').click(function() {
        let formData = $('#resetPasswordForm').serialize();

        $.ajax({
            url: "{{ route('users.reset-password') }}",
            method: "POST",
            data: formData,
            success: function(res) {
                $('#resetPasswordMessage').html('<div class="alert alert-success">Password reset successfully!</div>');
                setTimeout(function() {
                    $('#resetPasswordModal').modal('hide');
                }, 1500);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger">';
                $.each(errors, function(key, value) {
                    errorHtml += value + '<br>';
                });
                errorHtml += '</div>';
                $('#resetPasswordMessage').html(errorHtml);
            }
        });
    });
});

</script>
@endpush

