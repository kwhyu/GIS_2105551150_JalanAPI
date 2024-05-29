@extends('layout')

@section('content')
@if(isset($ruasJalanDetails) && !empty($ruasJalanDetails))
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
                </div>
            </div>
            <div class="col-md-4 form-container">
                <!-- Right Side (Form) -->
                <div class="p-4">
                    <!-- Your form content here -->
                    <h2>Edit</h2>
                    <form action="" method="post" class="ruas-jalan-form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="id_ruas">ID Ruas:</label>
                            <input type="text" id="id_ruas" name="id_ruas" class="form-control">
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
                            <input type="number" id="panjang" name="panjang" class="form-control">
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
                    </form>
                    <button type="submit">Simpan</button>
                    <button type="delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Leaflet JS -->
    <!-- <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha384-IW65Vy9Gkf4lHTNerFxFqV4OFL2FgITJ0bMWz2PyQT7zj2xlF5v9Skk7V4VzQ+A9" crossorigin=""></script> -->
    <script>
        var map = L.map('map').setView([-8.4095188, 115.188919], 10);
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

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

                // Update form fields with polyline data
                document.getElementById('id_ruas').value = ruas.id;
                document.getElementById('kode_ruas').value = ruas.kode_ruas;
                document.getElementById('nama_ruas').value = ruas.nama_ruas;
                document.getElementById('panjang').value = ruas.panjang;
                document.getElementById('lebar').value = ruas.lebar;
                document.getElementById('eksisting_id').value = ruas.eksisting_id;
                document.getElementById('kondisi_id').value = ruas.kondisi_id;
                document.getElementById('jenisjalan_id').value = ruas.jenisjalan_id;
                document.getElementById('keterangan').value = ruas.keterangan;
            });
        });

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
            });

            markers.push(marker); // Menambahkan marker ke dalam array markers
        }

        map.on('click', function(event) {
            var latlng = event.latlng;
            addMarker(latlng, markers.length);
            updatePolyline();
        });
    </script>
</section>
</div>
</section>
@endsection