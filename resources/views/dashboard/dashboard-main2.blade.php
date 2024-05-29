@extends('layout')

@section('content')
<!-- @if(isset($polylines) && !empty($polylines))
<div id="polylineData" style="display: none;">{{ json_encode($polylines) }}</div>
@endif -->
@if(isset($ruasJalanDetails) && !empty($ruasJalanDetails))
<div id="ruasJalanDetails" style="display: none;">{{ json_encode($ruasJalanDetails) }}</div>
@endif
<section class="page-section" style="background-color: #f0f0f0;">
    <div class="container" style="padding-top: 0;">
        <div class="text-center" style="padding-top: 0;">
            <div id="polylineText"></div>
            <script>
                var polylineData = JSON.parse(document.getElementById('polylineData').textContent);
                console.log(polylineData);
            </script>
            <h2 class="section-heading text-uppercase">Peta Ruas Jalan</h2>
        </div>
        <div id="mapid"></div>
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
                }).addTo(mymap); // Add new polyline
            }
        </script>
    </div>
</section>
@endsection