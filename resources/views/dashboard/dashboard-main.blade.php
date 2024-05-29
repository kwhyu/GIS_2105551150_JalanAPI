<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="Kwhy" />
    <title>GIS - 210555150</title>
    <!-- Favicon-->
    <!-- <link rel="icon" type="image/x-icon" href="{{ asset('landingpage/assets/favicon.ico') }}" /> -->
    <link href="{{ asset('assets/img/ooo.png') }}" rel="icon">

    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('landingpage/css/styles.css') }}" rel="stylesheet" />

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

</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top"><img src=" {{ asset('landingpage/assets/img/pp.jpeg') }}" alt="..." />GIS - 2105551150</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars ms-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#map">Map</a></li>
                    <li class="nav-item"><a class="nav-link" href="/add-ruasjalan">Tambah Ruas Jalan</a></li>
                    @if(session('token'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Sign Out</a></li>
                    @else
                    <li class="nav-item"><a class="nav-link" href="/login">Sign In</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <div class="masthead-subheading">Welcome</div>
            <div class="masthead-heading text-uppercase">Managemen Ruas Jalan</div>
            <!-- <a class="btn btn-primary btn-xl text-uppercase" href="#map">Tell Me More</a> -->
        </div>
    </header>

    <!-- Map-->
    <section class="page-section" id="map">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Peta Ruas Jalan</h2>
                <!-- <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3> -->
            </div>
            <div id="mapid"></div>
            <script>
                var mymap = L.map('mapid').setView([-8.4095188, 115.188919], 10);

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
    </section>
    <!-- Portfolio Grid-->
    <!-- <section class="page-section bg-light" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Portfolio</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>

        </div>
    </section> -->
    <!-- About-->
    <!-- <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">About</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
    </section> -->
    <!-- Team-->
    <!-- <section class="page-section bg-light" id="team">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Our Amazing Team</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
    </section> -->
    <!-- Clients-->
    <div class="py-5">
        <div class="container">

        </div>
    </div>
    <!-- Contact-->
    <!-- <section class="page-section" id="contact">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Contact Us</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>

        </div>
    </section> -->
    <!-- Footer-->

    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-lg-start">Copyright &copy;I Putu Eka Wahyu</div>
                <div class="col-lg-4 my-3 my-lg-0">
                    <!-- <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Twitter"><i class="fab fa-twitter"></i></a> -->
                    <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <!-- <a class="link-dark text-decoration-none me-3" href="#!">Privacy Policy</a>
                    <a class="link-dark text-decoration-none" href="#!">Terms of Use</a> -->
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('landingpage/js/scripts.js') }}"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>