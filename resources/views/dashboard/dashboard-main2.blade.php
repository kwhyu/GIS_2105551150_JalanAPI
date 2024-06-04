@extends('layout')

@section('content')
    <!-- @if (isset($polylines) && !empty($polylines))
    <div id="polylineData" style="display: none;">{{ json_encode($polylines) }}</div>
    @endif -->
    @if (isset($ruasJalanDetails) && !empty($ruasJalanDetails))
        <div id="ruasJalanDetails" style="display: none;">{{ json_encode($ruasJalanDetails) }}</div>
    @endif
    <section class="page-section">
        <div class="container" style="padding-top: 0;">
            <div class="text-center" style="padding-top: 0;">
                <div id="polylineText"></div>
                <script>
                    var polylineData = JSON.parse(document.getElementById('polylineData').textContent);
                    console.log(polylineData);
                </script>
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
                                    <td><button onclick="boundPolyline({{ json_encode($ruas['paths2']) }})">Cari</button>
                                    </td>
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

                // var polylineData = JSON.parse(document.getElementById('polylineData').textContent);
                var ruasJalanDetails = JSON.parse(document.getElementById('ruasJalanDetails').textContent);
                console.log(ruasJalanDetails);

                var polycolors = ['red', 'blue', 'green', 'purple', 'orange', 'yellow', 'pink', 'brown', 'black'];

                ruasJalanDetails.forEach(function(ruas, index) {
                    var color = polycolors[index % polycolors.length];
                    var polyline = L.polyline(ruas.paths, {
                        color: color
                    }).addTo(mymap);

                    // polylineData.forEach(function(polylineCoords, index) {
                    //     var color = polycolors[index % polycolors.length];
                    //     var polyline = L.polyline(polylineCoords, {
                    //         color: color
                    //     }).addTo(mymap);

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

                function boundPolyline(paths) {
                    var latLngs = paths.map(function(path) {
                        return [path.lat, path.lng];
                    });

                    // Cari polyline dengan ID yang sesuai
                    var targetPolyline = polylines.find(function(polyline) {
                        return polyline.options.id == paths[0].ruas_id;
                    });

                    if (targetPolyline) {
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
