@extends('layout')

@section('content')
    @if (isset($ruasJalanDetails) && !empty($ruasJalanDetails))
        <div id="ruasJalanDetails" style="display: none;">{{ json_encode($ruasJalanDetails) }}</div>
    @endif
    <section class="page-section" id="map-form-section" style="background-color: #f0f0f0;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 map-container">
                    <script>
                        var polylineData = JSON.parse(document.getElementById('polylineData').textContent);
                        console.log(polylineData);
                    </script>
                    <!-- Left Side (Map) -->
                    <div class="p-4">
                        <!-- Your map content here -->
                        <h2>Map</h2>
                        <div id="map" class="map"></div>
                        <button type="button" class="btn btn-secondary" id="resetMap">Reset</button>
                        <button id="toggle-add-marker" type="button">Off Add Marker</button>
                    </div>
                </div>
                <div class="col-md-4 form-container">
                    <!-- Right Side (Form) -->
                    <div class="p-4">
                        <!-- Your form content here -->
                        <h2>Edit</h2>
                        <form id="ruasForm" method="POST" class="ruas-jalan-form">
                            @csrf
                            <input type="hidden" name="_method" id="_method" value="PUT">
                            <div class="form-group">
                                <label for="id_ruas">ID Ruas:</label>
                                <input type="text" id="id_ruas" name="id_ruas" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="paths_get">Path:</label>
                                <input type="text" id="paths_get" name="paths_get" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="desa_id">Desa:</label>
                                <input type="text" id="desa_id" name="desa_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="kode_ruas">Kode Ruas:</label>
                                <input type="text" id="kode_ruas" name="kode_ruas" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="nama_ruas">Nama Ruas:</label>
                                <input type="text" id="nama_ruas" name="nama_ruas" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="panjang">Panjang:</label>
                                <input type="text" id="panjang" name="panjang" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="lebar">Lebar:</label>
                                <input type="number" id="lebar" name="lebar" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="eksisting_id">Eksisting ID:</label>
                                <input type="number" id="eksisting_id" name="eksisting_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="kondisi_id">Kondisi ID:</label>
                                <input type="number" id="kondisi_id" name="kondisi_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jenisjalan_id">Jenis Jalan ID:</label>
                                <input type="number" id="jenisjalan_id" name="jenisjalan_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan:</label>
                                <input type="text" id="keterangan" name="keterangan" class="form-control">
                            </div>
                            <button type="button" onclick="submitForm('PUT')">Simpan</button>
                            <button type="button" onclick="submitForm('DELETE')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#provinsi').on('change', function() {
                    var prov_id = $(this).val();
                    $.ajax({
                        url: '/get-kabupaten',
                        data: {
                            prov_id: prov_id
                        },
                        success: function(response) {
                            var options = '<option value="">Pilih Kabupaten</option>';
                            $.each(response, function(index, item) {
                                options += '<option value="' + item.id + '">' + item
                                    .kabupaten + '</option>';
                            });
                            $('#kabupaten').html(options);
                            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                            $('#desa').html('<option value="">Pilih Desa</option>');
                        }
                    });
                });

                $('#kabupaten').on('change', function() {
                    var kab_id = $(this).val();
                    $.ajax({
                        url: '/get-kecamatan',
                        data: {
                            kab_id: kab_id
                        },
                        success: function(response) {
                            var options = '<option value="">Pilih Kecamatan</option>';
                            $.each(response, function(index, item) {
                                options += '<option value="' + item.id + '">' + item
                                    .kecamatan + '</option>';
                            });
                            $('#kecamatan').html(options);
                            $('#desa').html('<option value="">Pilih Desa</option>');
                        }
                    });
                });

                $('#kecamatan').on('change', function() {
                    var kec_id = $(this).val();
                    $.ajax({
                        url: '/get-desa',
                        data: {
                            kec_id: kec_id
                        },
                        success: function(response) {
                            var options = '<option value="">Pilih Desa</option>';
                            $.each(response, function(index, item) {
                                options += '<option value="' + item.id + '">' + item.desa +
                                    '</option>';
                            });
                            $('#desa').html(options);
                        }
                    });
                });
            });
        </script>
        <!-- Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Leaflet JS -->
        <!-- <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha384-IW65Vy9Gkf4lHTNerFxFqV4OFL2FgITJ0bMWz2PyQT7zj2xlF5v9Skk7V4VzQ+A9" crossorigin=""></script> -->
        <script>
            var map = L.map('map').setView([-8.4095188, 115.188919], 10);
            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            var markers = [];
            var polyline = null;
            var canAddMarker = true; // Variable to control marker addition
            var isOnDrag = false;

            var myIcon = L.icon({
                iconUrl: 'assets/img/lilin.png',
                iconSize: [35, 40],
                iconAnchor: [20, 40],
            });

            var ruasJalanDetails = JSON.parse(document.getElementById('ruasJalanDetails').textContent);
            console.log(ruasJalanDetails);

            var polycolors = ['red', 'blue', 'green', 'purple', 'orange', 'yellow', 'pink', 'brown', 'black'];

            ruasJalanDetails.forEach(function(ruas, index) {
                var color = polycolors[index % polycolors.length];
                var polyline = L.polyline(ruas.paths, {
                    color: color
                }).addTo(map);

                polyline.on('click', function(e) {
                    console.log(`Polyline ${index + 1} clicked`);
                    var popupContent = `
                <div class="popup-content">
                    <p>ID Ruas: ${ruas.id}</p>
                    <p>Kode Ruas: ${ruas.kode_ruas}</p>
                    <p>Nama Ruas: ${ruas.nama_ruas}</p>
                    <p>Panjang: ${ruas.panjang}</p>
                    <p>Lebar: ${ruas.lebar}</p>
                    <p>Keterangan: ${ruas.keterangan}</p>
                </div>
            `;
                    L.popup()
                        .setLatLng(e.latlng)
                        .setContent(popupContent)
                        .openOn(map);

                    document.getElementById('id_ruas').value = ruas.id;
                    document.getElementById('paths_get').value = ruas.paths2;
                    document.getElementById('desa_id').value = ruas.desa_id;
                    document.getElementById('kode_ruas').value = ruas.kode_ruas;
                    document.getElementById('nama_ruas').value = ruas.nama_ruas;
                    document.getElementById('panjang').value = ruas.panjang;
                    document.getElementById('lebar').value = ruas.lebar;
                    document.getElementById('eksisting_id').value = ruas.eksisting_id;
                    document.getElementById('kondisi_id').value = ruas.kondisi_id;
                    document.getElementById('jenisjalan_id').value = ruas.jenisjalan_id;
                    document.getElementById('keterangan').value = ruas.keterangan;

                    currentRuasId = ruas.id;

                    updateFormAction();
                });
            });

            function updateFormAction() {
                var form = document.getElementById('ruasForm');
                form.action = `/ruasjalan/update/${currentRuasId}`;
            }

            function submitForm(method) {
                var form = document.getElementById('ruasForm');
                var methodInput = document.getElementById('_method');
                methodInput.value = method;
                if (method === 'DELETE') {
                    form.action = `/ruasjalan/delete/${currentRuasId}`;
                }
                form.submit();
            }

            function addMarker(latlng, index) {
                var marker = L.marker(latlng, {
                    icon: myIcon,
                    draggable: true
                }).addTo(map);

                var popup = L.popup({
                    offset: [0, -30]
                });

                marker.bindPopup(popup);

                marker.on('click', function(event) {
                    popup.setLatLng(marker.getLatLng()).setContent(formatContent(marker.getLatLng().lat, marker
                        .getLatLng().lng, index)).openOn(map);
                });

                marker.on('dragstart', function(event) {
                    isOnDrag = true;
                });

                marker.on('drag', function(event) {
                    popup.setLatLng(marker.getLatLng()).setContent(formatContent(marker.getLatLng().lat, marker
                        .getLatLng().lng, index));
                });

                marker.on('dragend', function(event) {
                    updatePolyline();
                    setTimeout(function() {
                        isOnDrag = false;
                    }, 500);
                });

                marker.on('contextmenu', function(event) {
                    map.removeLayer(marker);
                    markers.splice(index, 1);
                    updatePolyline();
                    updatePathsInput();
                });

                markers.push(marker);
            }

            map.on('click', function(event) {
                if (canAddMarker) {
                    var latlng = event.latlng;
                    addMarker(latlng, markers.length);
                    updatePolyline();
                    updatePathsInput();
                }
            });

            document.getElementById('toggle-add-marker').addEventListener('click', function() {
                canAddMarker = !canAddMarker;
                this.textContent = canAddMarker ? 'Off Add Marker' : 'On Add Marker';
            });

            function updatePolyline() {
                if (polyline) {
                    map.removeLayer(polyline);
                }
                var latLngs = markers.map(function(marker) {
                    return marker.getLatLng();
                });
                polyline = L.polyline(latLngs, {
                    color: 'red'
                }).addTo(map);
            }

            function encodePolyline(latlngs) {
                var encoded = '';
                var lastPoint = [0, 0];

                for (var i = 0; i < latlngs.length; i++) {
                    var currentPoint = [
                        Math.round(latlngs[i].lat * 1e5),
                        Math.round(latlngs[i].lng * 1e5)
                    ];

                    var relativePoint = [
                        currentPoint[0] - lastPoint[0],
                        currentPoint[1] - lastPoint[1]
                    ];

                    encoded += encodePoint(relativePoint[0]) + encodePoint(relativePoint[1]);

                    lastPoint = currentPoint;
                }

                return encoded;
            }

            function encodePoint(point) {
                point = point < 0 ? ~(point << 1) : (point << 1);
                var chunks = '';

                while (point >= 0x20) {
                    chunks += String.fromCharCode((0x20 | (point & 0x1f)) + 63);
                    point >>= 5;
                }

                chunks += String.fromCharCode(point + 63);
                return chunks;
            }

            function deg2rad(deg) {
                return deg * (Math.PI / 180);
            }

            function calculatePolylineDistance(latlngs) {
                var totalDistance = 0;

                for (var i = 0; i < latlngs.length - 1; i++) {
                    var lat1 = latlngs[i].lat;
                    var lon1 = latlngs[i].lng;
                    var lat2 = latlngs[i + 1].lat;
                    var lon2 = latlngs[i + 1].lng;

                    var dLat = deg2rad(lat2 - lat1);
                    var dLon = deg2rad(lon2 - lon1);

                    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    var distance = 6371 * c;

                    totalDistance += distance;
                }

                return totalDistance;
            }

            function updatePathsInput() {
                var encodedPolyline = encodePolyline(markers.map(function(marker) {
                    return marker.getLatLng();
                }));
                document.getElementById('paths_get').value = encodedPolyline;

                var totalDistance = calculatePolylineDistance(markers.map(function(marker) {
                    return marker.getLatLng();
                }));
                document.getElementById('panjang').value = totalDistance.toFixed(2);
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('resetMap').addEventListener('click', function() {
                    resetMap();
                });
            });

            function resetMap() {
                markers.forEach(function(marker) {
                    map.removeLayer(marker);
                });
                markers = [];
                if (polyline) {
                    map.removeLayer(polyline);
                    polyline = null;
                }
                document.getElementById('paths_get').value = "";
                document.getElementById('id_ruas').value = "";
                document.getElementById('panjang').value = "";
            }
        </script>
    </section>
    </div>
    </section>
@endsection
