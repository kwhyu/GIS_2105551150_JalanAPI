@extends('layout')

@section('content')
<section class="page-section" id="map-form-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 map-container">
                <!-- Left Side (Map) -->
                <div class="p-4">
                    <!-- Your map content here -->
                    <h2>Map</h2>
                    <div id="map" class="map"></div>
                    <button type="button" class="btn btn-secondary" id="resetMap">Reset</button>
                </div>
            </div>
            <div class="col-md-4 form-container">
                <!-- Right Side (Form) -->
                <div class="p-4">
                    <h2>Tambah</h2>
                    <form id="ruasForm" action="{{ route('submitRuasJalan') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="paths">Paths:</label>
                            <input type="text" name="paths" id="paths" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="kode_ruas">Kode Ruas:</label>
                            <input type="text" name="kode_ruas" id="kode_ruas" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nama_ruas">Nama Ruas:</label>
                            <input type="text" name="nama_ruas" id="nama_ruas" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="panjang">Panjang:</label>
                            <input type="text" name="panjang" id="panjang" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="lebar">Lebar:</label>
                            <input type="text" name="lebar" id="lebar" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="provinsi">Provinsi:</label>
                            <select name="provinsi" id="provinsi" class="form-control">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinsi as $provinsi)
                                <option value="{{ $provinsi['id'] }}">{{ $provinsi['provinsi'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kabupaten">Kabupaten:</label>
                            <select name="kabupaten" id="kabupaten" class="form-control" disabled>
                                <option value="">Pilih Kabupaten</option>
                                @foreach($kabupaten as $kabupaten)
                                <option value="{{ $kabupaten['id'] }}">{{ $kabupaten['kabupaten'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan:</label>
                            <select name="kecamatan" id="kecamatan" class="form-control" disabled>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatan as $kecamatan)
                                <option value="{{ $kecamatan['id'] }}">{{ $kecamatan['kecamatan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="desa">Desa:</label>
                            <select name="desa" id="desa" class="form-control" disabled>
                                <option value="">Pilih Desa</option>
                                @foreach($desa as $desa)
                                <option value="{{ $desa['id'] }}">{{ $desa['desa'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="perkerasan-dropdown">Perkerasan Eksisting:</label>
                            <select name="perkerasan" id="perkerasan-dropdown" class="form-control">
                                <option value="">Pilih Perkerasan Eksisting</option>
                                @foreach($perkerasanEksisting as $perkerasan)
                                <option value="{{ $perkerasan['id'] }}">{{ $perkerasan['eksisting'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jenisjalan-dropdown">Jenis Jalan:</label>
                            <select name="jenis_jalan" id="jenisjalan-dropdown" class="form-control">
                                <option value="">Pilih Jenis Jalan</option>
                                @foreach($jenisJalan as $jalan)
                                <option value="{{ $jalan['id'] }}">{{ $jalan['jenisjalan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kondisiJalan-dropdown">Kondisi Jalan:</label>
                            <select name="kondisi_jalan" id="kondisiJalan-dropdown" class="form-control">
                                <option value="">Pilih Kondisi Jalan</option>
                                @foreach($kondisiJalan as $kondisi)
                                <option value="{{ $kondisi['id'] }}">{{ $kondisi['kondisi'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha384-IW65Vy9Gkf4lHTNerFxFqV4OFL2FgITJ0bMWz2PyQT7zj2xlF5v9Skk7V4VzQ+A9" crossorigin=""></script>

    <script>
        // Initialize the Leaflet map
        var map = L.map('map').setView([-8.4095188, 115.188919], 10);

        // Add the base OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        var markers = [];
        var polyline = null;

        var isOnDrag = false;
        var myIcon = L.icon({
            iconUrl: 'assets/img/lilin.png',
            iconSize: [35, 40],
            iconAnchor: [20, 40],
        });

        // Function to format popup content
        function formatContent(lat, lng, index) {
            var polylineCoordinates = markers.map(marker => marker.getLatLng()); // Ambil koordinat dari semua marker
            return `
        <div class="wrapper">
            <div class="row">
                <div class="cell merged" style="text-align:center">Marker [ ${index+1} ]</div>
            </div>
            <div class="row">
                <div class="col">Latitude</div>
                <div class="col2">${lat}</div>
            </div>
            <div class="row">
                <div class="col">Longitude</div>
                <div class="col2">${lng}</div>
            </div>
            <div class="row">
                <div class="col">Coordinates</div>
                <div class="col2">${polylineCoordinates.map(c => `(${c.lat}, ${c.lng})`).join(', ')}</div>
            </div>
        </div>
    `;
        }

        function addMarker(latlng, index) {
            var marker = L.marker(latlng, {
                icon: myIcon,
                draggable: true
            }).addTo(map); // Menggunakan variabel 'map' bukan 'mymap'

            var popup = L.popup({
                offset: [0, -30]
            });

            marker.bindPopup(popup); // Mengikat popup ke marker

            marker.on('click', function(event) {
                // Set konten popup dan buka popup ketika marker diklik
                popup.setLatLng(marker.getLatLng()).setContent(formatContent(marker.getLatLng().lat, marker.getLatLng().lng, index)).openOn(map);
            });

            marker.on('dragstart', function(event) {
                isOnDrag = true;
            });

            marker.on('drag', function(event) {
                popup.setLatLng(marker.getLatLng()).setContent(formatContent(marker.getLatLng().lat, marker.getLatLng().lng, index));
            });

            marker.on('dragend', function(event) {
                updatePolyline(); // Update polyline ketika marker selesai di-drag
                setTimeout(function() {
                    isOnDrag = false;
                }, 500);
            });

            marker.on('contextmenu', function(event) {
                // Menghapus marker ketika mengklik kanan pada marker
                map.removeLayer(marker);
                markers.splice(index, 1);
                updatePolyline(); // Update polyline ketika marker dihapus
                updatePathsInput(); // Update paths input ketika marker dihapus
            });

            markers.push(marker); // Menambahkan marker ke dalam array markers
        }

        map.on('click', function(event) {
            var latlng = event.latlng;
            addMarker(latlng, markers.length); 
            updatePolyline();
            updatePathsInput();
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

        function calculateDistance(lat1, lon1, lat2, lon2) {
            var R = 6371; // Radius of the earth in km
            var dLat = deg2rad(lat2 - lat1); // deg2rad below
            var dLon = deg2rad(lon2 - lon1);
            var a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c; // Distance in km
            return d;
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180)
        }

        // Function to calculate total distance of polyline
        function calculatePolylineDistance(latlngs) {
            var totalDistance = 0;

            // Iterate through each pair of coordinates
            for (var i = 0; i < latlngs.length - 1; i++) {
                var lat1 = latlngs[i].lat;
                var lon1 = latlngs[i].lng;
                var lat2 = latlngs[i + 1].lat;
                var lon2 = latlngs[i + 1].lng;

                // Convert latitude and longitude from degrees to radians
                var dLat = (lat2 - lat1) * Math.PI / 180;
                var dLon = (lon2 - lon1) * Math.PI / 180;

                // Haversine formula
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var distance = 6371 * c; // Earth's radius in kilometers

                totalDistance += distance;
            }

            return totalDistance;
        }


        function updatePathsInput() {
            var encodedPolyline = encodePolyline(markers.map(function(marker) {
                return marker.getLatLng();
            }));
            document.getElementById('paths').value = encodedPolyline;

            var totalDistance = calculatePolylineDistance(markers.map(function(marker) {
                return marker.getLatLng();
            }));
            document.getElementById('panjang').value = totalDistance.toFixed(2) + " km";
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('ruasForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting
                this.submit();
            });

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
        }
    </script>
    </div>
</section>
@endsection