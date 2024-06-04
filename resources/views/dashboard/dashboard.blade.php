<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>GIS-2105551150</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/ooo.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Map Stuff -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.min.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>


  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="d-flex flex-column">

      <div class="profile">
        <img src="assets/img/pp.jpeg" alt="" class="img-fluid rounded-circle">
        <h1 class="text-light"><a href="index.html">GIS - 2105551150</a></h1>
      </div>

      <nav id="navbar" class="nav-menu navbar">
        <ul>
          <li><a href="#hero" class="nav-link scrollto active"><i class="bx bx-home"></i> <span>Home</span></a></li>
          <li><a href="#about" class="nav-link scrollto"><i class="bx bx-map"></i> <span>Map</span></a></li>
          <!-- <li><a href="#resume" class="nav-link scrollto"><i class="bx bx-file-blank"></i> <span>List Rumah Sakit</span></a></li> -->
          <!-- <li><a href="#portfolio" class="nav-link scrollto"><i class="bx bx-book-content"></i> <span>Portfolio</span></a></li>
          <li><a href="#services" class="nav-link scrollto"><i class="bx bx-server"></i> <span>Services</span></a></li>
          <li><a href="#contact" class="nav-link scrollto"><i class="bx bx-envelope"></i> <span>Contact</span></a></li> -->
        </ul>
      </nav>
      <!-- .nav-menu -->
    </div>
  </header>
  <!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="hero-container" data-aos="fade-in">
      <h1>I Putu Eka Wahyu</h1>
      <p> <span class="typed" data-typed-items="Im, The, One, Im The One"></span></p>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Map</h2>
        </div>
        <div id="mapid"></div>
        <script>
            var mymap = L.map('mapid').setView([-8.4095188, 115.188919], 11);

            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                // attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
                maxZoom: 18,
            }).addTo(mymap);

            var markers = [];
            var polylineCoordinates = [];
            var polyline = null;
            var isOnDrag = false;
            var myIcon = L.icon({
                iconUrl: 'assets/img/lilin.png',
                iconSize: [35, 40],
                iconAnchor: [20, 40],
            });
            // Function to format popup content
            formatContent = function(lat, lng, index) {
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
                            <div class="col">Longitude</div>
                            <div class="col2">${polylineCoordinates}</div>
                        </div>
                    </div>
                `;
            }

            // Function to add marker
            addMarker = function(latlng, index) {
                var marker = L.marker(latlng, {
                    icon: myIcon,
                    draggable: true
                }).addTo(mymap);

                var popup = L.popup({
                    offset: [0, -30]
                }).setLatLng(latlng);

                marker.bindPopup(popup);

                marker.on('click', function() {
                    popup.setLatLng(marker.getLatLng()),
                    popup.setContent(formatContent(marker.getLatLng().lat, marker.getLatLng().lng, index));
                });

                marker.on('dragstart', function(event) {
                    isOnDrag = true;
                });

                marker.on('drag', function(event) {
                    popup.setLatLng(marker.getLatLng()),
                    popup.setContent(formatContent(marker.getLatLng().lat, marker.getLatLng().lng, index));
                    marker.openPopup();
                });

                marker.on('dragend', function(event) {
                    updatePolyline(); // Update polyline ketika marker selesai di-drag
                    setTimeout(function() {
                        isOnDrag = false;
                    }, 500);
                });

                marker.on('contextmenu', function(event) {
                    markers.forEach(function(m, i) {
                        if (marker == m) {
                            m.removeFrom(mymap);
                            markers.splice(i, 1);
                            polylineCoordinates.splice(i, 1); // Remove coordinate from polylineCoordinates
                            updatePolyline(); // Update polyline ketika marker dihapus
                        }
                    });
                });
                return marker;
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

            mymap.on('click', function(e) {
                if (!isOnDrag) {
                    var newMarker = addMarker(e.latlng, markers.length);
                    markers.push(newMarker);
                    polylineCoordinates.push(e.latlng); // Add coordinate to polylineCoordinates
                    updatePolyline(); // Update polyline setelah menambahkan marker baru
                }
            });
        </script>
      </div>
      <div class="container">
        <div class="base">
        <button id="showPolylineBtn">Tampilkan Polyline</button>
          <!-- <div class="kiri">
            <button onclick="getMarker()" type="button" class="btn btn-dark">Tampilkan Marker</button>
          </div>
          <div class="kanan">
            <button onclick="resetMarkers()" type="button" class="btn btn-dark">Reset Marker</button>
          </div> -->
        </div>
      </div>
    </section><!-- End About Section -->

    <!-- ======= Facts Section ======= -->
    <section id="facts" class="facts">
      <div class="container">
      <div class="section-title">
          <h2>Tambah Ruas Jalan</h2>
        </div>
        <div class="form-input">
        <form method="POST" action="/add-ruasjalan">
            @csrf
            <div class="form-group">
                <label for="paths">Paths</label>
                <input type="text" name="paths" id="paths"required />
            </div>
            <div class="form-group">
                <label for="desa_id">Desa ID</label>
                <input type="number" name="desa_id" id="desa_id"required />
            </div>
            <div class="form-group">
                <label for="kode_ruas">Kode Ruas</label>
                <input type="text" name="kode_ruas" id="kode_ruas"required />
            </div>
            <div class="form-group">
                <label for="nama_ruas">Nama Ruas</label>
                <input type="text" name="nama_ruas" id="nama_ruas"required />
            </div>
            <div class="form-group">
                <label for="panjang">Panjang</label>
                <input type="text" name="panjang" id="panjang"required />
            </div>
            <div class="form-group">
                <label for="lebar">Lebar</label>
                <input type="text" name="lebar" id="lebar"required />
            </div>
            <div class="form-group">
                <label for="eksisting_id">Eksisting ID</label>
                <input type="number" name="eksisting_id" id="eksisting_id"required />
            </div>
            <div class="form-group">
                <label for="kondisi_id">Kondisi ID</label>
                <input type="number" name="kondisi_id" id="kondisi_id"required />
            </div>
            <div class="form-group">
                <label for="jenisjalan_id">Jenis Jalan ID</label>
                <input type="number" name="jenisjalan_id" id="jenisjalan_id"required />
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan"required />
            </div>
            <div class="form-group form-button">
                <input type="submit" class="form-submit" value="Tambah Ruas Jalan"/>
            </div>
        </form>
        </div>
      </div>
    </section><!-- End Facts Section -->

    <!-- ======= Resume Section ======= -->
    <section id="resume" class="resume">
      <div class="container">
      </div>
    </section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; NIM <strong><span>2105551150</span></strong>
      </div>
    </div>
  </footer><!-- End  Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/typed.js/typed.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <!-- <script src="{{ asset('assets/js/server.js') }}"></script> -->

</body>

</html>