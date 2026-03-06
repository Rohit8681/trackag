@extends('admin.layout.layout')

@section('title', 'Live Tracking | Trackag')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">User Live Tracking</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row mb-3"> 
                        <div class="col-md-3"> 
                        <label>State</label> 
                        <select id="stateFilter" class="form-control"> 
                            <option value="">All State</option> 
                            @foreach($states as $state) 
                            <option value="{{ $state->id }}">{{ $state->name }}</option> 
                            @endforeach 
                        </select> 
                    </div> 
                    <div class="col-md-3"> 
                        <label>User</label> 
                        <select id="userFilter" class="form-control"> 
                            <option value="">All User</option> 
                            @foreach($users as $user) 
                            <option value="{{ $user->id }}">{{ $user->name }}</option> 
                            @endforeach 
                        </select> 
                    </div> 
                </div>
                    <div id="mapNew" style="height:600px; width:100%;" class="rounded border"></div>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection

@push('scripts')

<script>

var mapNew;
var markersNew = [];

function initMapNewSafe() {

    mapNew = new google.maps.Map(document.getElementById("mapNew"), {
        zoom: 7,
        center: { lat: 23.0225, lng: 72.5714 }
    });

    loadLiveLocationsNew();

    setInterval(loadLiveLocationsNew, 15000);
}


function loadLiveLocationsNew() {

    let state_id = document.getElementById('stateFilter').value;
    let user_id  = document.getElementById('userFilter').value;

    fetch("{{ url('admin/tracking/live-data') }}?state_id="+state_id+"&user_id="+user_id)
        .then(response => response.json())
        .then(data => {

            markersNew.forEach(marker => marker.setMap(null));
            markersNew = [];

            data.forEach(user => {

                if (!user.latitude || !user.longitude) return;

                let marker = new google.maps.Marker({

                    position: {
                        lat: parseFloat(user.latitude),
                        lng: parseFloat(user.longitude)
                    },

                    map: mapNew,
                    title: user.name,

                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                        scaledSize: new google.maps.Size(40,40)
                    }

                });


                let infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="min-width:150px">
                            <strong>${user.name}</strong><br>
                            <small>Time : ${user.time}</small>
                        </div>
                    `
                });


                marker.addListener("click", function () {
                    infoWindow.open(mapNew, marker);
                });

                markersNew.push(marker);

            });

        });
}


document.getElementById('stateFilter').addEventListener('change',function(){
    loadLiveLocationsNew();
});

document.getElementById('userFilter').addEventListener('change',function(){
    loadLiveLocationsNew();
});


window.addEventListener("load", function() {
    setTimeout(initMapNewSafe, 500);
});

</script>

@endpush