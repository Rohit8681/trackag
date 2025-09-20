@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Depo</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('depos.index') }}">Depo Master</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Depo</h3>
            </div>
            <div class="card-body">
                @include('admin.depos.form', [
                    'action' => route('depos.update', $depo->id),
                    'method' => 'PUT',
                    'depo' => $depo,
                    'states' => $states,
                    'districts' => $districts,
                    'tehsils' => $tehsils
                ])
            </div>
        </div>
    </div>
</main>
@endsection
