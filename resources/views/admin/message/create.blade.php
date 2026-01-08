@extends('admin.layout.layout')
@section('title', 'Create Message | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Message</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('messages.index') }}">Messages</a></li>
                        <li class="breadcrumb-item active">Send Message</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Send Message</h3>
            </div>

            <div class="card-body">

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('messages.store') }}">
                    @csrf

                    <div class="row g-3">

                        {{-- Type --}}
                        <div class="col-md-4">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">-- Select Type --</option>
                                <option value="all">Send to All</option>
                                <option value="individual">Individual</option>
                            </select>
                        </div>

                        {{-- State --}}
                        <div class="col-md-4">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <select name="state_id" class="form-select" required>
                                <option value="">-- Select State --</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Employee --}}
                        <div class="col-md-4 d-none" id="employee-box">
                            <label class="form-label">Employee</label>
                            <select name="user_id" class="form-select">
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Message --}}
                        <div class="col-md-12">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="4" required></textarea>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Send
                        </button>
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.getElementById('type').addEventListener('change', function () {
        let empBox = document.getElementById('employee-box');
        if (this.value === 'individual') {
            empBox.classList.remove('d-none');
        } else {
            empBox.classList.add('d-none');
        }
    });
</script>
@endpush
