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

            <!-- Add dropdown for filtering roads -->
            <div class="text-center my-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Filter Ruas Jalan
                    </button>
                    <div class="dropdown-menu" aria-labelledby="filterDropdown">
                        <label class="dropdown-item"><input type="checkbox" id="filter-baik" checked> Jalan Baik</label>
                        <label class="dropdown-item"><input type="checkbox" id="filter-sedang" checked> Jalan Sedang</label>
                        <label class="dropdown-item"><input type="checkbox" id="filter-rusak" checked> Jalan Rusak</label>
                        <label class="dropdown-item"><input type="checkbox" id="filter-desa" checked> Jalan Desa</label>
                        <label class="dropdown-item"><input type="checkbox" id="filter-kabupaten" checked> Jalan
                            Kabupaten</label>
                        <label class="dropdown-item"><input type="checkbox" id="filter-provinsi" checked> Jalan
                            Provinsi</label>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-4" style="max-width: 100%; overflow-x: auto;">
                @if (!empty($ruasJalanDetails))

                    <!-- Jalan Desa/Kabupaten/Provinsi Table -->
                    <table class="summary-table table-desktop">
                        <thead>
                            <tr>
                                <th>Jalan Desa</th>
                                <th>Jalan Kabupaten</th>
                                <th>Jalan Provinsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['jenisjalan_id'] == 1)) }}</td>
                                <td>{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['jenisjalan_id'] == 2)) }}</td>
                                <td>{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['jenisjalan_id'] == 3)) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Kondisi Baik/Sedang/Rusak Table -->
                    <table class="summary-table table-laptop">
                        <thead>
                            <tr>
                                <th>Kondisi Baik</th>
                                <th>Kondisi Sedang</th>
                                <th>Kondisi Rusak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['kondisi_id'] == 1)) }}</td>
                                <td>{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['kondisi_id'] == 2)) }}</td>
                                <td>{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['kondisi_id'] == 3)) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Total Ruas Jalan Table -->
                    <table class="summary-table table-total">
                        <thead>
                            <tr>
                                <th>Total Ruas Jalan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ count($ruasJalanDetails) }}</td>
                            </tr>
                        </tbody>
                    </table>
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
                @else
                    <p>Data ruas jalan tidak dapat diakses karena user belum login, silahkan login terlebih dahulu</p>
                @endif
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
                        "dom": 'lBfrtip',
                        "buttons": []
                    });

                    // Add event listeners to checkboxes
                    $('#filter-baik').change(function() {
                        filterPolylines();
                    });

                    $('#filter-sedang').change(function() {
                        filterPolylines();
                    });

                    $('#filter-rusak').change(function() {
                        filterPolylines();
                    });

                    $('#filter-desa').change(function() {
                        filterPolylines();
                    });

                    $('#filter-kabupaten').change(function() {
                        filterPolylines();
                    });

                    $('#filter-provinsi').change(function() {
                        filterPolylines();
                    });
                });

                var mymap = L.map('mapid').setView([-8.409518, 115.188919], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mymap);

                var ruasJalanDetails = JSON.parse(document.getElementById('ruasJalanDetails').textContent);
                console.log(ruasJalanDetails);

                // Define polylines globally
                var polylines = [];

                ruasJalanDetails.forEach(function(ruas, index) {
                    var color = '';
                    switch (ruas.kondisi_id) {
                        case 1:
                            color = 'green';
                            break;
                        case 2:
                            color = 'orange';
                            break;
                        case 3:
                            color = 'red';
                            break;
                        default:
                            color = 'gray'; // Default color for unknown conditions
                    }

                    var polyline = L.polyline(ruas.paths, {
                        color: color,
                        id: ruas.id // Ensure the id is set correctly
                    }).addTo(mymap);

                    polylines.push(polyline);

                    // Event listener for polyline click
                    polyline.on('click', function(e) {
                        console.log(ruas);
                        var popupContent = `
                            <div class="popup-content">
                                <h5>Ruas Jalan Details</h5>
                                <p><strong>ID:</strong> ${ruas.id}</p>
                                <p><strong>Desa ID:</strong> ${ruas.desa_id}</p>
                                <p><strong>Desa:</strong> ${ruas.nama_desa}</p>                                                                
                                <p><strong>Kode Ruas:</strong> ${ruas.kode_ruas}</p>
                                <p><strong>Nama Ruas:</strong> ${ruas.nama_ruas}</p>
                                <p><strong>Panjang:</strong> ${ruas.panjang}</p>
                                <p><strong>Lebar:</strong> ${ruas.lebar}</p>
                                <p><strong>Eksisting:</strong> ${ruas.eksisting_id}</p>
                                <p><strong>Kondisi:</strong> ${ruas.kondisi_id}</p>
                                <p><strong>Jenis Jalan:</strong> ${ruas.jenisjalan_id}</p>
                                <p><strong>Keterangan:</strong> ${ruas.keterangan}</p>
                            </div>
                        `;

                        polyline.bindPopup(popupContent).openPopup();
                    });

                });

                function filterPolylines() {
                    var showBaik = $('#filter-baik').is(':checked');
                    var showSedang = $('#filter-sedang').is(':checked');
                    var showRusak = $('#filter-rusak').is(':checked');
                    var showDesa = $('#filter-desa').is(':checked');
                    var showKabupaten = $('#filter-kabupaten').is(':checked');
                    var showProvinsi = $('#filter-provinsi').is(':checked');

                    polylines.forEach(function(polyline) {
                        var ruas = ruasJalanDetails.find(ruas => ruas.id === polyline.options.id);
                        var condition = ruas.kondisi_id;
                        var type = ruas.jenisjalan_id;

                        if (
                            ((condition === 1 && showBaik) || (condition === 2 && showSedang) || (condition === 3 &&
                                showRusak)) &&
                            ((type === 1 && showDesa) || (type === 2 && showKabupaten) || (type === 3 && showProvinsi))
                        ) {
                            mymap.addLayer(polyline);
                        } else {
                            mymap.removeLayer(polyline);
                        }
                    });
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

                function boundPolyline(encodedPaths, ruasId) {
                    var paths = decodePolyline(encodedPaths);

                    var latLngs = paths.map(function(path) {
                        return [path[0], path[1]];
                    });

                    // Find polyline with the matching ID
                    var targetPolyline = polylines.find(function(polyline) {
                        return polyline.options.id.toString() === ruasId.toString();
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
                    iconUrl: 'assets/img/pin.png',
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
