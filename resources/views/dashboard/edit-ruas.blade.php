    @extends('layout')

    @section('content')
        @if (isset($ruasJalanDetails) && !empty($ruasJalanDetails))
            <div id="ruasJalanDetails" style="display: none;">{{ json_encode($ruasJalanDetails) }}</div>
        @endif
        <section class="page-section" id="map-form-section" style="background-color: #f0f0f0;">
            <!-- Modal Konfirmasi Simpan -->
            <div class="modal fade" id="confirmSimpanModal" tabindex="-1" role="dialog" aria-labelledby="confirmSimpanModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmSimpanModalLabel">Konfirmasi Simpan</h5>
                        </div>
                        <div class="modal-body text-center">
                            <p>Apakah Anda yakin untuk menyimpan data ini?</p>
                            <img src="{{ asset('assets/img/centang.png') }}" alt="Centang Icon"
                                style="width: 70px; height: 70px;">
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" onclick="submitForm('PUT')" class="btn btn-primary">Yakin</button>
                            <button type="button" onclick="closeModal()" class="btn btn-secondary"
                                data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="confirmHapusModal" tabindex="-1" role="dialog"
                aria-labelledby="confirmHapusModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmHapusModalLabel">Konfirmasi Hapus</h5>
                        </div>
                        <div class="modal-body text-center">
                            <p>Apakah Anda yakin untuk menghapus data ini?</p>
                            <img src="{{ asset('assets/img/silang.png') }}" alt="Silang Icon"
                                style="width: 70px; height: 70px;">
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" onclick="submitForm('DELETE')" class="btn btn-danger">Yakin</button>
                            <button type="button" onclick="closeModal()" class="btn btn-secondary"
                                data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row no-gutters">
                    <div class="col-md-8 map-container p-4">
                        <h2>Map</h2>
                        <div id="map" class="map"></div>
                        <button type="button" class="btn btn-secondary mt-3" id="resetMap">Reset</button>
                        <button id="toggle-add-marker" type="button" class="btn btn-primary mt-3">Add Marker On</button>
                    </div>

                    <div class="col-md-4 p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Edit</h2>
                            </div>
                        </div>
                        <form id="ruasForm" method="POST" class="ruas-jalan-form">
                            @csrf
                            <input type="hidden" name="_method" id="_method" value="PUT">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_ruas">ID Ruas:</label>
                                        <input type="text" id="id_ruas" name="id_ruas" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="provinsi_id">Provinsi:</label>
                                        <select id="provinsi_id" name="provinsi_id" class="form-control">
                                            <option value="">Pilih Provinsi</option>
                                            <option value="1">Bali</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kabupaten_id">Kabupaten:</label>
                                        <select id="kabupaten_id" name="kabupaten_id" class="form-control">
                                            <option value="">Pilih Kabupaten</option>
                                            <option value="1">Jembrana</option>
                                            <option value="2">Tabanan</option>
                                            <option value="3">Badung</option>
                                            <option value="4">Denpasar</option>
                                            <option value="5">Buleleng</option>
                                            <option value="6">Gianyar</option>
                                            <option value="7">Bangli</option>
                                            <option value="8">Klungkung</option>
                                            <option value="9">Karangasem</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_ruas">Nama Ruas:</label>
                                        <input type="text" id="nama_ruas" name="nama_ruas" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="eksisting_id">Eksisting ID:</label>
                                        <select id="eksisting_id" name="eksisting_id" class="form-control">
                                            <option value="">Pilih Eksisting</option>
                                            <option value="1">Tanah</option>
                                            <option value="2">Tanah/Beton</option>
                                            <option value="3">Perkerasan</option>
                                            <option value="4">Koral</option>
                                            <option value="5">Lapen</option>
                                            <option value="6">Paving</option>
                                            <option value="7">Hotmix</option>
                                            <option value="8">Beton</option>
                                            <option value="9">Beton/Lapen</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenisjalan_id">Jenis Jalan:</label>
                                        <select id="jenisjalan_id" name="jenisjalan_id" class="form-control">
                                            <option value="">Pilih Jenis Jalan</option>
                                            <option value="1">Jalan Desa</option>
                                            <option value="2">Jalan Kabupaten</option>
                                            <option value="3">Jalan Provinsi</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kondisi_id">Kondisi:</label>
                                        <select id="kondisi_id" name="kondisi_id" class="form-control">
                                            <option value="">Pilih Kondisi</option>
                                            <option value="1">Kondisi Baik</option>
                                            <option value="2">Kondisi Sedang</option>
                                            <option value="3">Kondisi Rusak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="desa_id">ID Desa:</label>
                                        <input type="text" id="desa_id" name="desa_id" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="paths_get">Path:</label>
                                        <input type="text" id="paths_get" name="paths_get" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="kecamatan_id">Kecamatan:</label>
                                        <input type="text" id="kecamatan_id" name="kecamatan_id" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="desa_nama">Desa:</label>
                                        <input type="text" id="desa_nama" name="desa_nama" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_ruas">Kode Ruas:</label>
                                        <input type="text" id="kode_ruas" name="kode_ruas" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="panjang">Panjang (KM):</label>
                                        <input type="text" id="panjang" name="panjang" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="lebar">Lebar:</label>
                                        <input type="number" id="lebar" name="lebar" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan:</label>
                                <input type="text" id="keterangan" name="keterangan" class="form-control">
                            </div>
                            <button type="button" onclick="showSimpanModal()" class="btn btn-primary">Simpan</button>
                            <button type="button" onclick="showHapusModal()" class="btn btn-danger ml-2">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-4" style="max-width: 100%; overflow-x: auto;">
                <table class="table table-bordered" id="ruasJalanTable">
                    <thead>
                        <tr>
                            <th>Nama Desa</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten</th>
                            <th>Provinsi</th>
                            <th>Nama Ruas</th>
                            <th>Panjang (KM)</th>
                            <th>Lebar</th>
                            <th>Eksisting</th>
                            <th>Kondisi</th>
                            <th>Jenis Jalan</th>
                            <th>Keterangan</th>
                            <th>Cari</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ruasJalanDetails as $ruas)
                            @php
                                $rowClass = '';
                                switch ($ruas['jenisjalan_id']) {
                                    case 1:
                                        $rowClass = 'jalan-desa';
                                        break;
                                    case 2:
                                        $rowClass = 'jalan-kabupaten';
                                        break;
                                    case 3:
                                        $rowClass = 'jalan-provinsi';
                                        break;
                                }
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>{{ $ruas['nama_desa'] }}</td>
                                <td>{{ $ruas['nama_kecamatan'] }}</td>
                                <td>{{ $ruas['nama_kabupaten'] }}</td>
                                <td>{{ $ruas['nama_provinsi'] }}</td>
                                <td>{{ $ruas['nama_ruas'] }}</td>
                                <td>{{ $ruas['panjang'] }}</td>
                                <td>{{ $ruas['lebar'] }}</td>
                                <td>
                                    @php
                                        $perkerasan = '';
                                        switch ($ruas['eksisting_id']) {
                                            case 1:
                                                $perkerasan = 'Tanah';
                                                break;
                                            case 2:
                                                $perkerasan = 'Tanah/Beton';
                                                break;
                                            case 3:
                                                $perkerasan = 'Perkerasan';
                                                break;
                                            case 4:
                                                $perkerasan = 'Koral';
                                                break;
                                            case 5:
                                                $perkerasan = 'Lapen';
                                                break;
                                            case 6:
                                                $perkerasan = 'Paving';
                                                break;
                                            case 7:
                                                $perkerasan = 'Hotmix';
                                                break;
                                            case 8:
                                                $perkerasan = 'Beton';
                                                break;
                                            case 9:
                                                $perkerasan = 'Beton/Lapen';
                                                break;
                                            default:
                                                $perkerasan = 'Unknown';
                                        }
                                    @endphp
                                    {{ $perkerasan }}
                                </td>
                                <td>
                                    @php
                                        $kondisi = '';
                                        switch ($ruas['kondisi_id']) {
                                            case 1:
                                                $kondisi = 'Baik';
                                                break;
                                            case 2:
                                                $kondisi = 'Sedang';
                                                break;
                                            case 3:
                                                $kondisi = 'Rusak';
                                                break;
                                            default:
                                                $kondisi = 'Unknown';
                                        }
                                    @endphp
                                    {{ $kondisi }}
                                </td>
                                <td>
                                    @php
                                        $jenisJalan = '';
                                        switch ($ruas['jenisjalan_id']) {
                                            case 1:
                                                $jenisJalan = 'Desa';
                                                break;
                                            case 2:
                                                $jenisJalan = 'Kabupaten';
                                                break;
                                            case 3:
                                                $jenisJalan = 'Provinsi';
                                                break;
                                            default:
                                                $jenisJalan = 'Unknown';
                                        }
                                    @endphp
                                    {{ $jenisJalan }}
                                </td>
                                <td>{{ $ruas['keterangan'] }}</td>
                                <td><button
                                        onclick="boundPolyline('{{ $ruas['paths2'] }}', {{ $ruas['id'] }})">Cari</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#ruasJalanTable').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "pageLength": 10,
                        "dom": 'lBfrtip',
                        "buttons": []
                    });
                });
            </script>
            <script>
                var polylineData = JSON.parse(document.getElementById('polylineData').textContent);
                console.log(polylineData);
            </script>
            <!-- Bootstrap JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                function showSimpanModal() {
                    $('#confirmSimpanModal').modal('show');
                    const form = document.getElementById('ruasForm');

                    const formData = new FormData(form);
                    const formValues = {};

                    formData.forEach((value, key) => {
                        formValues[key] = value;
                    });

                    // Tampilkan nilai form di console
                    console.log('Form Values:', formValues);
                }

                function showHapusModal() {
                    $('#confirmHapusModal').modal('show');
                }

                function submitForm(method) {
                    var form = document.getElementById('ruasForm');
                    var methodInput = document.getElementById('_method');
                    methodInput.value = method;
                    if (method === 'DELETE') {
                        form.action = `/ruasjalan/delete/${currentRuasId}`;
                    }
                    form.submit();
                    // else {
                    //     form.action = `/ruasjalan/update/${currentRuasId}`;
                    // }
                    // form.submit();
                }

                function closeModal() {
                    $('#confirmSimpanModal').modal('hide');
                    $('#confirmHapusModal').modal('hide');
                }
            </script>

            <!-- Leaflet JS -->
            <script>
                var map = L.map('map').setView([-8.4095188, 115.188919], 10);
                var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                }).addTo(map);

                function boundPolyline(encodedPaths, ruasId) {
                    var paths = decodePolyline(encodedPaths);

                    var latLngs = paths.map(function(path) {
                        return [path[0], path[1]];
                    });

                    // Find polyline with the matching ID
                    var targetPolyline = polyline.find(function(polyline) {
                        return polyline.options.id.toString() === ruasId.toString();
                    });

                    if (targetPolyline) {
                        var bounds = L.latLngBounds(latLngs);
                        map.fitBounds(bounds);
                    } else {
                        console.log('Polyline not found');
                    }
                }

                var markers = [];
                var polyline = null;
                var canAddMarker = true;
                var isOnDrag = false;
                var currentRuasId = null;

                var myIcon = L.icon({
                    iconUrl: 'assets/img/pin.png',
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
                        document.getElementById('desa_nama').value = ruas.nama_desa;
                        document.getElementById('provinsi_id').value = ruas.id_provinsi;
                        document.getElementById('kabupaten_id').value = ruas.id_kabupaten;
                        document.getElementById('kecamatan_id').value = ruas.nama_kecamatan;
                        document.getElementById('kode_ruas').value = ruas.kode_ruas;
                        document.getElementById('nama_ruas').value = ruas.nama_ruas;
                        document.getElementById('panjang').value = ruas.panjang;
                        document.getElementById('lebar').value = ruas.lebar;
                        document.getElementById('eksisting_id').value = ruas.eksisting_id;
                        document.getElementById('kondisi_id').value = ruas.kondisi_id;
                        document.getElementById('jenisjalan_id').value = ruas.jenisjalan_id;
                        document.getElementById('keterangan').value = ruas.keterangan;

                        currentRuasId = ruas.id;

                        addPolylineMarkers(ruas.paths);

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
                    updatePolyline();
                    updatePathsInput();
                }

                function addPolylineMarkers(latlngs) {
                    // Remove existing markers
                    markers.forEach(function(marker) {
                        map.removeLayer(marker);
                    });
                    markers = [];

                    // Add markers for each point in the polyline
                    latlngs.forEach(function(latlng, index) {
                        addMarker(latlng, index);
                    });

                    updatePolyline();
                    updatePathsInput();
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
                    this.textContent = canAddMarker ? 'Add Marker On' : ' Add Marker Off';
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

                function decodePolyline(encoded) {
                        let points = [];
                        let index = 0,
                            len = encoded.length;
                        let lat = 0,
                            lng = 0;

                        while (index < len) {
                            let b, shift = 0,
                                result = 0;
                            do {
                                b = encoded.charCodeAt(index++) - 63;
                                result |= (b & 0x1f) << shift;
                                shift += 5;
                            } while (b >= 0x20);
                            let dlat = (result & 1) ? ~(result >> 1) : (result >> 1);
                            lat += dlat;

                            shift = 0;
                            result = 0;
                            do {
                                b = encoded.charCodeAt(index++) - 63;
                                result |= (b & 0x1f) << shift;
                                shift += 5;
                            } while (b >= 0x20);
                            let dlng = (result & 1) ? ~(result >> 1) : (result >> 1);
                            lng += dlng;

                            points.push([lat * 1e-5, lng * 1e-5]);
                        }
                        return points;
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
                    document.getElementById('id_ruas').value = ruasJalanDetails.id_ruas || '';
                    document.getElementById('paths_get').value = ruasJalanDetails.paths2 || '';
                    document.getElementById('desa_id').value = ruasJalanDetails.desa_id || '';
                    document.getElementById('nama_ruas').value = ruasJalanDetails.nama_ruas || '';
                    document.getElementById('kode_ruas').value = ruasJalanDetails.kode_ruas || '';
                    document.getElementById('kondisi_id').value = ruasJalanDetails.kondisi_id || '';
                    document.getElementById('eksisting_id').value = ruasJalanDetails.eksisting_id || '';
                    document.getElementById('jenisjalan_id').value = ruasJalanDetails.jenisjalan_id || '';
                    document.getElementById('panjang').value = ruasJalanDetails.panjang || '';
                    document.getElementById('lebar').value = ruasJalanDetails.lebar || '';
                    document.getElementById('keterangan').value = ruasJalanDetails.keterangan || '';
                }
            </script>
        </section>
    @endsection
