@extends('layout')

@section('content')
    @if (isset($ruasJalanDetails) && !empty($ruasJalanDetails))
        <div id="ruasJalanDetails" style="display: none;">{{ json_encode($ruasJalanDetails) }}</div>
    @endif
    <section class="page-section">
        <div class="container" style="padding-top: 0;">
            <div class="text-center" style="padding-top: 0;">
                <div id="polylineText"></div>
                <h2 class="section-heading text-uppercase">Peta Ruas Jalan</h2>
            </div>
            <div id="mapid" style="background-color: #f0f0f0;"></div>

            <div class="table-responsive mt-4" style="max-width: 100%; overflow-x: auto;">
                @if (!empty($ruasJalanDetails))
                    <table class="table table-bordered" id="ruasJalanTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Desa ID</th>
                                <th>Kode Ruas</th>
                                <th>Nama Ruas</th>
                                <th>Panjang</th>
                                <th>Lebar</th>
                                <th>Eksisting ID</th>
                                <th>Kondisi ID</th>
                                <th>Jenis Jalan ID</th>
                                <th>Keterangan</th>
                                <th>Cari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruasJalanDetails as $ruas)
                                <tr>
                                    <td>{{ $ruas['id'] }}</td>
                                    <td>{{ $ruas['desa_id'] }}</td>
                                    <td>{{ $ruas['kode_ruas'] }}</td>
                                    <td>{{ $ruas['nama_ruas'] }}</td>
                                    <td>{{ $ruas['panjang'] }}</td>
                                    <td>{{ $ruas['lebar'] }}</td>
                                    <td>{{ $ruas['eksisting_id'] }}</td>
                                    <td>{{ $ruas['kondisi_id'] }}</td>
                                    <td>{{ $ruas['jenisjalan_id'] }}</td>
                                    <td>{{ $ruas['keterangan'] }}</td>
                                    <td><button onclick="boundPolyline('{{ $ruas['paths2'] }}', {{ $ruas['id'] }})">Cari</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Data ruas jalan tidak dapat diakses karena user belum login, silahkan login terlebih dahulu</p>
                @endif
            </div>

            <script>
                var mymap = L.map('mapid').setView([-8.4095188, 115.188919], 10);

                var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                }).addTo(mymap);

                var ruasJalanDetails = JSON.parse(document.getElementById('ruasJalanDetails').textContent);
                console.log(ruasJalanDetails);

                var polycolors = ['red', 'blue', 'green', 'purple', 'orange', 'yellow', 'pink', 'brown', 'black'];

                // Define polylines globally
                var polylines = [];

                ruasJalanDetails.forEach(function(ruas, index) {
                    var color = polycolors[index % polycolors.length];
                    var polyline = L.polyline(ruas.paths, {
                        color: color,
                        id: ruas.id // Ensure the id is set correctly
                    }).addTo(mymap);

                    polylines.push(polyline);

                    // Event listener for polyline click
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
                            .openOn(mymap);
                    });
                });

                function decodePolyline(encoded) {
                    let index = 0,
                        lat = 0,
                        lng = 0,
                        latlngs = [];

                    while (index < encoded.length) {
                        let shift = 0,
                            result = 0,
                            bit;

                        do {
                            bit = encoded.charCodeAt(index++) - 63;
                            result |= (bit & 0x1f) << shift;
                            shift += 5;
                        } while (bit >= 0x20);

                        let dlat = (result & 1) ? ~(result >> 1) : (result >> 1);
                        lat += dlat;

                        shift = result = 0;

                        do {
                            bit = encoded.charCodeAt(index++) - 63;
                            result |= (bit & 0x1f) << shift;
                            shift += 5;
                        } while (bit >= 0x20);

                        let dlng = (result & 1) ? ~(result >> 1) : (result >> 1);
                        lng += dlng;

                        latlngs.push([lat * 1e-5, lng * 1e-5]);
                    }

                    return latlngs;
                }

                function boundPolyline(encodedPaths, ruasId) {
                    console.log("Encoded paths:", encodedPaths);
                    console.log("Ruas ID:", ruasId);

                    // Decode the polyline paths
                    var paths = decodePolyline(encodedPaths);
                    console.log("Decoded paths:", paths);

                    var latLngs = paths.map(function(path) {
                        return [path[0], path[1]];
                    });

                    console.log("LatLngs:", latLngs);

                    // Find polyline with the matching ID
                    var targetPolyline = polylines.find(function(polyline) {
                        console.log("Checking polyline ID:", polyline.options.id, "against", ruasId);
                        return polyline.options.id.toString() === ruasId.toString(); // Convert both to strings for comparison
                    });

                    if (targetPolyline) {
                        console.log('Target Polyline found:', targetPolyline);
                        var bounds = L.latLngBounds(latLngs);
                        mymap.fitBounds(bounds);
                    } else {
                        console.log('Polyline not found');
                    }
                }

                var markers = [];
                var polylineCoordinates = [];
                var polyline = null;
                var isOnDrag = false;
                var myIcon = L.icon({
                    iconUrl: 'assets/img/lilin.png',
                    iconSize: [35, 40],
                    iconAnchor: [20, 40],
                });

                function formatContent(lat, lng, index) {
                    return `
                    <div class="wrapper">
                        <div class="row">
                            <div class="cell merged" style="text-align:center">Marker [ ${index + 1} ]</div>
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

                // Update polyline
                function updatePolyline() {
                    polylineCoordinates = markers.map(marker => marker.getLatLng()); // Update polylineCoordinates array
                    if (polyline) {
                        mymap.removeLayer(polyline); // Remove existing polyline
                    }
                    polyline = L.polyline(polylineCoordinates, {
                        color: 'red'
                    }).addTo(mymap);
                }
            </script>
        </div>
    </section>
@endsection
