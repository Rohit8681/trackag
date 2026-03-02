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

<script>

var mapNew;
var markersNew = [];

function initMapNewSafe() {

    if (typeof google === "undefined") {
        console.error("Google not loaded yet");
        return;
    }

    mapNew = new google.maps.Map(document.getElementById("mapNew"), {
        zoom: 10,
        center: { lat: 23.0225, lng: 72.5714 }
    });

    loadLiveLocationsNew();
    setInterval(loadLiveLocationsNew, 15000);
}

function loadLiveLocationsNew() {
    fetch("{{ url('admin/tracking/live-data') }}")
        .then(response => response.json())
        .then(data => {

            markersNew.forEach(marker => marker.setMap(null));
            markersNew = [];

            data.forEach(user => {

                // ❗ Skip if invalid coordinates
                if (!user.latitude || !user.longitude) return;

                let marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(user.latitude),
                        lng: parseFloat(user.longitude)
                    },
                    map: mapNew,
                    title: user.name,

                    // 🔥 Custom Icon
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                        scaledSize: new google.maps.Size(40, 40)
                    }
                });

                // 🔥 InfoWindow (Name Popup)
                let infoWindow = new google.maps.InfoWindow({
                    content: `<strong>${user.name}</strong>`
                });

                marker.addListener("click", function () {
                    infoWindow.open(mapNew, marker);
                });

                markersNew.push(marker);
            });

        });
}

// Wait until page fully loads
window.addEventListener("load", function() {
    setTimeout(initMapNewSafe, 500);
});

</script>

@endpush