<!-- jQuery (one version only, 3.7.0 latest) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap 5 + Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

<!-- Datatables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<!-- OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>

<!-- AdminLTE JS -->
<script src="{{ asset('admin/js/adminlte.js') }}"></script>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" crossorigin="anonymous"></script>

<!-- jsvectormap -->
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" crossorigin="anonymous"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Google Maps API -->
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_-uOyQimLqBkDW_Vr8d88GX6Qk0lyksI&libraries=places"> --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Custom JS -->
<script src="{{ url('admin/js/custom.js') }}"></script>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $("#users-table, #permissions-table,#states-table,#tehsils-table,#districts-table")
            .DataTable();
    });
</script>

<!-- OverlayScrollbars Config -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: 'os-theme-light',
                    autoHide: 'leave',
                    clickScroll: true
                }
            });
        }
    });
</script>

<!-- Sortable Cards -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.connectedSortable').forEach(el => {
            new Sortable(el, {
                group: 'shared',
                handle: '.card-header'
            });
        });
        document.querySelectorAll('.connectedSortable .card-header').forEach(el => el.style.cursor = 'move');
    });
</script>

{{-- <script>
function initMap() {
    const tripLogs = window.tripLogs || [];
    const tripEnded = window.tripEnded;

    if (!tripLogs.length) {
        console.warn("No trip logs available.");
        return;
    }

    const pathCoordinates = tripLogs.map(l => ({
        lat: parseFloat(l.latitude),
        lng: parseFloat(l.longitude),
        recorded_at: l.recorded_at ?? ''
    }));

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: pathCoordinates[0],
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    });

    // Draw Route Line
    if (pathCoordinates.length > 1) {
        const tripPath = new google.maps.Polyline({
            path: pathCoordinates,
            geodesic: true,
            strokeColor: "#007bff",
            strokeOpacity: 1,
            strokeWeight: 4,
        });
        tripPath.setMap(map);
    }

    // CUSTOM ICONS
    const startIcon = {
        url: "{{ asset('img/start-green.png') }}",
        scaledSize: new google.maps.Size(60, 60) // LARGE
    };

    const middleIcon = {
        url: "{{ asset('img/mid-blue.png') }}",
        scaledSize: new google.maps.Size(30, 30) // MEDIUM
    };

    const endIcon = {
        url: "{{ asset('img/end-red.png') }}",
        scaledSize: new google.maps.Size(60, 60) // LARGE
    };

    // START MARKER (GREEN)
    new google.maps.Marker({
        position: pathCoordinates[0],
        map,
        title: "Start Point: " + (pathCoordinates[0].recorded_at || ''),
        icon: startIcon,
        label: {
            text: "Start",
            color: "#fff",
            fontSize: "12px",
            fontWeight: "bold"
        }
    });

    // MIDDLE MARKERS (BLUE)
    for (let i = 1; i <= pathCoordinates.length - 2; i++) {
        new google.maps.Marker({
            position: pathCoordinates[i],
            map,
            title: pathCoordinates[i].recorded_at,
            icon: middleIcon
        });
    }

    // END MARKER (RED)
    if (pathCoordinates.length > 1) {
        const last = pathCoordinates[pathCoordinates.length - 1];
        new google.maps.Marker({
            position: last,
            map,
            title: "End Point: " + (last.recorded_at || ''),
            icon: endIcon,
            label: {
                text: "End",
                color: "#fff",
                fontSize: "12px",
                fontWeight: "bold"
            }
        });
    }

    // Auto-fit Zoom
    if (pathCoordinates.length > 1) {
        const bounds = new google.maps.LatLngBounds();
        pathCoordinates.forEach(c => bounds.extend(c));
        map.fitBounds(bounds);

        google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
            const currentZoom = map.getZoom();
            if (currentZoom > 16) map.setZoom(16);
            if (currentZoom < 12) map.setZoom(12);
        });
    }
}

document.addEventListener("DOMContentLoaded", initMap);
</script> --}}

<script>

function initMap() {
    const tripLogs = window.tripLogs || [];
    const tripEnded = window.tripEnded;

    if (!tripLogs.length) {
        console.warn("No trip logs available.");
        return;
    }

    const pathCoordinates = tripLogs.map(l => ({
        lat: parseFloat(l.latitude),
        lng: parseFloat(l.longitude),
        recorded_at: l.recorded_at ?? ''
    }));

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: pathCoordinates[0],
    });

    // Draw Polyline
    if (pathCoordinates.length > 1) {
        const tripPath = new google.maps.Polyline({
            path: pathCoordinates,
            geodesic: true,
            strokeColor: "#007bff",
            strokeWeight: 4,
        });
        tripPath.setMap(map);
    }

    // Icons
    const startIcon = {
        url: "{{ asset('img/start-green.png') }}",
        scaledSize: new google.maps.Size(60, 60)
    };
    const middleIcon = {
        url: "{{ asset('img/mid-blue.png') }}",
        scaledSize: new google.maps.Size(30, 30)
    };
    const endIcon = {
        url: "{{ asset('img/end-red.png') }}",
        scaledSize: new google.maps.Size(60, 60)
    };

    // START MARKER (always)
    new google.maps.Marker({
        position: pathCoordinates[0],
        map,
        title: "Start: " + (pathCoordinates[0].recorded_at || ''),
        icon: startIcon,
        label: {
            text: "Start",
            color: "#fff",
            fontSize: "12px",
            fontWeight: "bold"
        }
    });

    // MIDDLE MARKERS
    for (let i = 1; i < pathCoordinates.length - 1; i++) {
        new google.maps.Marker({
            position: pathCoordinates[i],
            map,
            icon: middleIcon,
            title: pathCoordinates[i].recorded_at,
        });
    }

    // END MARKER ONLY IF TRIP ENDED
    if (tripEnded && pathCoordinates.length > 1) {
        const last = pathCoordinates[pathCoordinates.length - 1];

        new google.maps.Marker({
            position: last,
            map,
            title: "End: " + (last.recorded_at || ''),
            icon: endIcon,
            label: {
                text: "End",
                color: "#fff",
                fontSize: "12px",
                fontWeight: "bold"
            }
        });
    }

    // Auto-fit zoom
    const bounds = new google.maps.LatLngBounds();
    pathCoordinates.forEach(p => bounds.extend(p));
    map.fitBounds(bounds);
}


document.addEventListener("DOMContentLoaded", initMap);
</script>





<!-- Dependent Dropdowns (District/City/Tehsil/Pincode) -->
<script>
    const urls = {
        districts: "{!! url('admin/get-districts') !!}/",
        cities: "{!! url('admin/get-cities') !!}/",
        tehsils: "{!! url('admin/get-tehsils') !!}/",
        pincodes: "{!! url('admin/get-pincodes') !!}/"
    };
    $(function() {
        $('#state').on('change', function() {
            
            let id = $(this).val();
            if (id) $.get(urls.districts + id).done(d => fillOptions('#district', d));
        });
        $('#district').on('change', function() {
            let id = $(this).val();
            if (id) $.get(urls.cities + id).done(d => fillOptions('#city', d));
        });
        $('#city').on('change', function() {
            let id = $(this).val();
            if (id) {
                $.get(urls.tehsils + id).done(d => fillOptions('#tehsil', d));
                $.get(urls.pincodes + id).done(d => fillOptions('#pincode', d, 'pincode'));
            }
        });
    });

    function fillOptions(selector, data, label = 'name') {
        let opts = '<option value="">Select</option>';
        data.forEach(o => opts += `<option value="${o.id}">${o[label]}</option>`);
        $(selector).html(opts);
    }


    $(document).ready(function() {
        const stateId = "{{ old('state_id', $user->state_id ?? '') }}";
        const districtId = "{{ old('district_id', $user->district_id ?? '') }}";
        const cityId = "{{ old('city_id', $user->city_id ?? '') }}";
        const tehsilId = "{{ old('tehsil_id', $user->tehsil_id ?? '') }}";
        const pincodeId = "{{ old('pincode_id', $user->pincode_id ?? '') }}";

        if (stateId) {
            $.get(urls.districts + stateId).done(function(data) {
                fillOptions('#district', data);
                $('#district').val(districtId);

                if (districtId) {
                    $.get(urls.cities + districtId).done(function(data) {
                        fillOptions('#city', data);
                        $('#city').val(cityId);

                        if (cityId) {
                            $.get(urls.tehsils + cityId).done(function(data) {
                                fillOptions('#tehsil', data);
                                $('#tehsil').val(tehsilId);
                            });

                            $.get(urls.pincodes + cityId).done(function(data) {
                                fillOptions('#pincode', data, 'pincode');
                                $('#pincode').val(pincodeId);
                            });
                        }
                    });
                }
            });
        }
    });
</script>

<!-- Company > Executive Dropdown Linkage -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cSelect = document.getElementById('company_id');
        const eSelect = document.getElementById('user_id');
        if (!cSelect || !eSelect) return;
        cSelect.addEventListener('change', () => {
            fetch(`/admin/companies/${cSelect.value}/executives`)
                .then(r => r.json()).then(d => {
                    eSelect.innerHTML = '<option value="">-- Select Executive --</option>';
                    d.executives.forEach(e => {
                        let opt = document.createElement('option');
                        opt.value = e.id;
                        opt.textContent = e.name;
                        eSelect.appendChild(opt);
                    });
                });
        });
    });
</script>

<!-- Travel Mode / Purpose / Tour Type Dropdown Loader -->
<script>
    const baseUrl = "{{ url('admin') }}";

    function loadDropdown(type, id, selected = null) {
        $.get(baseUrl + "/dropdown-values/" + type).done(r => {
            if (r.status === 'success') {
                let dd = $('#' + id).empty().append('<option value="">-- Select --</option>');
                r.values.forEach(v => dd.append(
                    `<option value="${v}" ${v==selected?'selected':''}>${v}</option>`));
            }
        });
    }
    // $(function() {
    //     @if(!isset($trip))
    //     loadDropdown('travel_mode', 'travel_mode');
    //     loadDropdown('purpose', 'purpose');
    //     loadDropdown('tour_type', 'tour_type');
    //     @else
    //     loadDropdown('travel_mode', 'travel_mode', "{{ old('travel_mode', $trip->travel_mode) }}");
    //     loadDropdown('purpose', 'purpose', "{{ old('purpose', $trip->purpose) }}");
    //     loadDropdown('tour_type', 'tour_type', "{{ old('tour_type', $trip->tour_type) }}");
    //     @endif
    // });
</script>

<!-- Select All Checkbox Control -->
<script>
    document.getElementById('select-all')?.addEventListener('change', function() {
        document.querySelectorAll('.customer-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>

<!-- Deny Reason Toggle -->
<script>
    function toggleReasonField() {
        document.getElementById('denial-reason-block').style.display =
            (document.getElementById('approval_status').value === 'denied') ? 'block' : 'none';
    }
</script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
    const modalElement = document.getElementById("sessionHistoryModal");
    if (!modalElement) return; // stop if modal not on page

    const modal = new bootstrap.Modal(modalElement);
    const modalContent = document.getElementById("sessionHistoryContent");
    const modalTitle = document.getElementById("sessionHistoryModalLabel");

    document.body.addEventListener("click", function(e) {
        if (e.target.classList.contains("view-sessions-link")) {
            e.preventDefault();
            const userId = e.target.getAttribute("data-user-id");
            const userName = e.target.getAttribute("data-user-name");

            modalTitle.innerText = `Session History - ${userName}`;
            modalContent.innerHTML = "Loading...";

            fetch(`/admin/users/${userId}/sessions`)
                .then(response => {
                    if (!response.ok) throw new Error("Network error");
                    return response.text();
                })
                .then(data => modalContent.innerHTML = data)
                .catch(() => modalContent.innerHTML =
                    "<p class='text-danger'>Failed to load session history.</p>"
                );

            modal.show();
        }
    });
});

</script>
<script>
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif

    @if(Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif

    @if(Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
    @endif
</script>
@stack('scripts')