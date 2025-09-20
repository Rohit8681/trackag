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

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_-uOyQimLqBkDW_Vr8d88GX6Qk0lyksI&libraries=places">
</script>

<!-- Custom JS -->
<script src="{{ url('admin/js/custom.js') }}"></script>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $("#roles-table, #users-table, #companies-table, #permissions-table, #customers-table, #designation-table, #trips-table,#states-table,#tehsils-table,#districts-table")
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

<!-- Google Maps Polyline and Markers -->
<script>
    // function initMap() {
    //     if (!tripLogs || tripLogs.length < 2) {
    //         alert("Not enough trip logs to draw route.");
    //         return;
    //     }
    //     const pathCoordinates = tripLogs.map(l => ({
    //         lat: +l.latitude,
    //         lng: +l.longitude,
    //         recorded_at: l.recorded_at ?? ''
    //     }));
    //     const map = new google.maps.Map(document.getElementById("map"), {
    //         zoom: 13,
    //         center: pathCoordinates[0]
    //     });
    //     const tripPath = new google.maps.Polyline({
    //         path: pathCoordinates,
    //         geodesic: true,
    //         strokeColor: "#007bff",
    //         strokeOpacity: 1,
    //         strokeWeight: 4
    //     });
    //     tripPath.setMap(map);
    //     const bounds = new google.maps.LatLngBounds();
    //     pathCoordinates.forEach(c => bounds.extend(c));
    //     map.fitBounds(bounds);
    //     pathCoordinates.forEach((coord, index) => {
    //         new google.maps.Marker({
    //             position: coord,
    //             map,
    //             // label: `${index + 1}`, // Optional: label as number or timestamp
    //             label: {
    //                 text: `${index + 1}`,
    //                 color: '#FFFFFF',
    //                 fontSize: '12px'
    //             },
    //             title: coord.recorded_at ?? '',
    //             icon: {
    //                 url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png" // You can change icon color here
    //             }
    //         });
    //     });

    //     let distance = 0;
    //     for (let i = 1; i < pathCoordinates.length; i++) distance += haversineDistance(pathCoordinates[i - 1],
    //         pathCoordinates[i]);
    //     document.getElementById("distance-display").innerText = distance.toFixed(2) + " km";
    // }

    function toRad(v) {
        return v * Math.PI / 180;
    }

    function haversineDistance(c1, c2) {
        const R = 6371,
            dLat = toRad(c2.lat - c1.lat),
            dLon = toRad(c2.lng - c1.lng),
            lat1 = toRad(c1.lat),
            lat2 = toRad(c2.lat);
        const a = Math.sin(dLat / 2) ** 2 + Math.sin(dLon / 2) ** 2 * Math.cos(lat1) * Math.cos(lat2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }
    // document.addEventListener("DOMContentLoaded", initMap);
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
@stack('scripts')