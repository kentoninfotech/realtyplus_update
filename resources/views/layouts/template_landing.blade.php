<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>STRIDDERR Global Services</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="Information Systems Solution Ltd (kenton)
        Information technology solutions,IT consulting,Software development
        Systems integration,Data management,Network infrastructure,Cybersecurity
        Cloud computing,Enterprise resource planning (ERP),Customer relationship management (CRM),Business intelligence,IT project management,IT support and maintenance,IT outsourcing,Digital transformation,Software-as-a-Service (SaaS),Application development,IT strategy,IT infrastructure solutions">
    <meta content="" name="At Kenton Ltd., we prioritize customer satisfaction, quality, and innovation. We are committed to delivering solutions that drive operational efficiency, improve productivity, and fuel growth for our clients. With our expertise and dedication, we strive to be a trusted partner for organizations seeking to harness the power of information systems to thrive in an increasingly digital world.">

    <!-- Favicon -->
    <link href="img/k.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;500&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('dist/landing/lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('dist/landing/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('dist/landing/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('dist/landing/css/style.css')}}" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <div class="container-fluid fixed-top px-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="top-bar row gx-0 align-items-center d-none d-lg-flex">
            <div class="col-lg-6 px-5 text-start">
                <small><i class="fa fa-map-marker-alt text-primary me-2"></i>Berger, Abuja</small>
                <small class="ms-4"><i class="fa fa-clock text-primary me-2"></i>9.00 am - 5.00 pm</small>
            </div>
            <div class="col-lg-6 px-5 text-end">
                <small><i class="fa fa-envelope text-primary me-2"></i>info@stridderr.com.ng</small>
                <small class="ms-4"><i class="fa fa-phone-alt text-primary me-2"></i>+234 803 507 3028</small>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
            <a href="index.html" class="navbar-brand ms-4 ms-lg-0">
                <h1 class="display-5 text-primary m-0"><img src="{{asset('dist/landing/img/logo.png')}}" alt="kenton" height="50" width="100"></h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="/" class="nav-item nav-link active">Home</a>
                    <a href="#about" class="nav-item nav-link">About</a>
                    <a href="#services" class="nav-item nav-link">Services</a>
                    <a href="#contact" class="nav-item nav-link">Contact</a>
                    <a href="{{ route('home') }}" class="nav-item nav-link">Dashboard</a>
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <a class="btn btn-light btn-sm-square rounded-circle ms-3" href="https://facebook.com/stridderr">
                        <small class="fab fa-facebook-f text-primary"></small>
                    </a>
                    <a class="btn btn-light btn-sm-square rounded-circle ms-3" href="https://twitter.com/stridderr">
                        <small class="fab fa-twitter text-primary"></small>
                    </a>
                    <a class="btn btn-light btn-sm-square rounded-circle ms-3" href="https://www.linkedin.com/in/clement-george-3b880219b">
                        <small class="fab fa-linkedin-in text-primary"></small>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    @yield('content')

       <!-- Footer Start -->
       <div class="container-fluid bg-dark text-light footer mt-5 py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Our Office</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Berger, Abuja</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+234 803 507 3028</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@stridderr.com.ng</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light rounded-circle me-2" href="https://twitter.com/stridderr"><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-2" href="https://facebook.com/stridderr"><i
                                class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Services</h4>
                    <a class="btn btn-link" href="">Land Inspection</a>
                    <a class="btn btn-link" href="">Estate Developers</a>
                    <a class="btn btn-link" href="">Building Engineering</a>
                    <a class="btn btn-link" href="">Property Consultancy</a>
                    <a class="btn btn-link" href="">Infrastructure Development</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="about.php">About Us</a>
                    <a class="btn btn-link" href="contact.php">Contact Us</a>
                    <a class="btn btn-link" href="service.php">Our Services</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Get updates</h4>
                    <p>Subscribe to Stridderr Updates</p>
                    <div class="position-relative w-100">
                        <input class="form-control bg-white border-0 w-100 py-3 ps-4 pe-5" type="text"
                            placeholder="Your email">
                        <button type="button"
                            class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="https://stridderr.com.ng/">Stridderr Global Services Ltd</a>, All Right Reserved. <a href="{{url('home')}}">ADMIN</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i
            class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('dist/landing/lib/wow/wow.min.js')}}"></script>
    <script src="{{asset('dist/landing/lib/easing/easing.min.js')}}"></script>
    <script src="{{asset('dist/landing/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{asset('dist/landing/lib/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('dist/landing/lib/counterup/counterup.min.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{asset('dist/landing/js/main.js')}}"></script>
</body>

</html>
