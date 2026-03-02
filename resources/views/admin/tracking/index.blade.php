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
                    <div id="mapNew" style="height:600px; width:100%;" class="rounded border"></div>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection

@push('scripts')
var map;
var markers = [];

function initMapNew() {

    map = new google.maps.Map(document.getElementById("mapNew"), {
        zoom: 10,
        center: { lat: 23.0225, lng: 72.5714 } // Default Ahmedabad
    });

    loadLiveLocations();
}

function loadLiveLocations() {
    console.log("loadLiveLocations");

    fetch("{{ route('tracking.liveData') }}")
        .then(response => response.json())
        .then(data => {
            console.log(data);
            console.log('testttt');
            // Remove old markers
            markers.forEach(marker => marker.setMap(null));
            markers = [];

            data.forEach(user => {

                let marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(user.latitude),
                        lng: parseFloat(user.longitude)
                    },
                    map: map,
                    title: user.name,
                    icon: {
                        url: user.mobile_status == 1
                            ? "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
                            : "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                    }
                });

                markers.push(marker);
            });

        });
}

// Start map
initMapNew();

// Auto refresh every 15 seconds
setInterval(loadLiveLocations, 15000);

</script>
@endpush