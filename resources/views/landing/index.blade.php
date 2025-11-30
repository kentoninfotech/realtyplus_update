@extends('layouts.template_landing')

@section('content')


    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="{{asset('dist/landing/img/home.jpg')}}" height="500" alt="Image">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-lg-8">
                                    <p
                                        class="d-inline-block border border-white rounded text-primary fw-semi-bold py-1 px-3 animated slideInDown">
                                        Welcome to THE ROYAL REFUGE LTD</p>
                                    <h1 class="display-1 mb-4 animated slideInDown" style="text-shadow:2px 2px white;">...Building, Construction, Real Estate
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="{{asset('dist/landing/img/carousel-1.jpg')}}" height="500" alt="Image">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-lg-7">
                                    <p
                                        class="d-inline-block border border-white rounded text-primary fw-semi-bold py-1 px-3 animated slideInDown">
                                        Welcome to THE ROYAL REFUGE LTD</p>
                                    <h1 class="display-1 mb-4 animated slideInDown" style="text-shadow:2px 2px white;">Real Estate Developers</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">Available Properties</p>
                <h1 class="display-5 mb-5">Explore Our Available Properties</h1>
            </div>
            <div class="row g-4">
                @foreach ($properties as $property)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded overflow-hidden">
                        <div class="position-relative overflow-hidden">
                            @if(isset($property->images) && $property->images->count() > 0)
                                @php
                                    // Check if there is a featured image
                                    $featuredImage = $property->images->firstWhere('is_featured', 1);
                                    // If no featured image, get the first image
                                    if (!$featuredImage) {
                                        $featuredImage = $property->images->first();
                                    }
                                @endphp
                                <img class="img-fluid" src="{{ asset('public/'.$featuredImage->image_path) }}" style="height: 280px; width: 100%;" alt="Royal Refuge Property">
                            @else
                                <img class="img-fluid" src="{{ asset('dist/landing/img/placeholder.jpg') }}" style="height: 280px; width: 100%;" alt="No Image Available">
                            @endif
                            <div
                                class="service-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                <h3 class="text-white mb-3">{{ $property->name }}</h3>
                                <a class="btn btn-primary" href="{{ route('properties', $property->id) }}">Read More</a>
                            </div>
                        </div>
                        <div class="p-4">
                            <h5 class="mb-3">{{ $property->name }}</h5>
                            <p>{{ Str::limit($property->address, 100) }}, {{ $property->state }}</p>
                            @if($property->listing_type == 'rent')
                            <span class="text-primary">Rent: ₦{{ number_format($property->rent_price, 0) }} /year</span>
                            @else
                            <span class="text-primary">Sale Price: ₦{{ number_format($property->sale_price, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- About Start -->
    <div class="container-xxl py-5" id="about">
        <div class="container">
            <div class="row g-4 align-items-end mb-4">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <img class="img-fluid rounded" src="{{asset('dist/landing/img/about.jpg')}}">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">About Us</p>
                    <h1 class="display-5 mb-4">We provide cutting-edge building and infrastructures</h1>
                    <p class="mb-4">
                       <br>
                       At ROYAL REFUGE LTD, we pride ourselves on being a leading force in the construction industry, dedicated to building high-quality homes and providing exceptional architectural services. With years of experience under our belt, our team of skilled professionals is committed to transforming your vision into reality. We believe that every project we undertake is a testament to our unwavering dedication to excellence, innovation, and craftsmanship. From conceptual design to project completion, we ensure that every detail is meticulously planned and executed, delivering results that exceed our clients' expectations.

                    </p>
                    <div class="border rounded p-4">
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link fw-semi-bold active" id="nav-story-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-story" type="button" role="tab" aria-controls="nav-story"
                                    aria-selected="true">Moto</button>
                                <button class="nav-link fw-semi-bold" id="nav-mission-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-mission" type="button" role="tab" aria-controls="nav-mission"
                                    aria-selected="false">Mission</button>
                                <button class="nav-link fw-semi-bold" id="nav-vision-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-vision" type="button" role="tab" aria-controls="nav-vision"
                                    aria-selected="false">Vision</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-story" role="tabpanel"
                                aria-labelledby="nav-story-tab">
                                <p>"Building Dreams, Creating Futures."</p>

                            </div>
                            <div class="tab-pane fade" id="nav-mission" role="tabpanel"
                                aria-labelledby="nav-mission-tab">
                                <p>At ROYAL REFUGE HOMES Ltd, our mission is to deliver exceptional construction, architectural, and civil infrastructure services that exceed our clients' expectations. We are dedicated to transforming visions into reality through innovative design, superior craftsmanship, and sustainable practices. Our commitment is to build not just structures, but lasting relationships with our clients, partners, and communities, ensuring the highest standards of quality, safety, and integrity in everything we do.</p>
                            </div>
                            <div class="tab-pane fade" id="nav-vision" role="tabpanel" aria-labelledby="nav-vision-tab">
                                <p>Our vision at ROYAL REFUGE HOMES Ltd is to be the premier construction company renowned for excellence, innovation, and sustainability. We aspire to lead the industry in creating iconic, enduring structures that enhance the quality of life and contribute to the development of vibrant, resilient communities. By continuously pushing the boundaries of what’s possible in construction and design, we aim to set new benchmarks for quality and efficiency, inspiring a future where our built environment harmonizes with the natural world.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-4">
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                    <i class="fa fa-times text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>Commitment to Excellence</h4>
                                    <span>Our Solutions are tailored to client needs.</span>
                                </div>
                                <div class="border-end d-none d-lg-block"></div>
                            </div>
                            <div class="border-bottom mt-4 d-block d-lg-none"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                    <i class="fa fa-users text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>Dedicated Team</h4>
                                    <span>We have an excellent Team of professional you can really on.</span>
                                </div>
                                <div class="border-end d-none d-lg-block"></div>
                            </div>
                            <div class="border-bottom mt-4 d-block d-lg-none"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                    <i class="fa fa-phone text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>24/7 Available</h4>
                                    <span>We just a call away from delivering our Services</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Facts Start -->
    <div class="container-fluid facts my-5 py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s">
                    <i class="fa fa-users fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">87</h1>
                    <span class="fs-5 text-white">Happy Clients</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s">
                    <i class="fa fa-check fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">14</h1>
                    <span class="fs-5 text-white">Projects Completed</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s">
                    <i class="fa fa-users-cog fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">15</h1>
                    <span class="fs-5 text-white">Dedicated Staff</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s">
                    <i class="fa fa-award fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">132</h1>
                    <span class="fs-5 text-white">Ungoing</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
            </div>
        </div>
    </div>
    <!-- Facts End -->


    <!-- Features Start -->
    <div class="container-xxl feature py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">Why Choosing Us!</p>
                    <h1 class="display-5 mb-4">Reasons Why People Are Choosing Us!</h1>
                    <p class="mb-4">

                        <b>Unmatched Quality and Craftsmanship</b><br>

                        At ROYAL REFUGE HOMES Ltd, we understand that quality is paramount in construction. Our team of experienced professionals is committed to delivering superior craftsmanship in every project we undertake. From residential homes to commercial buildings and civil infrastructure, we use only the highest quality materials and adhere to rigorous standards to ensure the longevity and durability of our constructions. Our meticulous attention to detail and dedication to excellence result in structures that not only meet but exceed our clients' expectations.
                        <br><br>

                        <b>Comprehensive and Customized Services</b>
                        <br>
                        We offer a wide range of services tailored to meet the unique needs of our clients. Whether you require architectural design, residential construction, or large-scale civil infrastructure projects, ROYAL REFUGE HOMES Ltd provides end-to-end solutions. Our comprehensive services include site analysis, space planning, 3D modeling, interior design, and project management. By working closely with our clients, we ensure that every aspect of the project aligns with their vision, budget, and timeline. This personalized approach sets us apart and ensures that each project is a true reflection of our clients' aspirations.
                        <br><br>

                        <b>Commitment to Sustainability and Innovation</b>
                        <br>
                        In today's world, sustainability is more important than ever. At ROYAL REFUGE HOMES Ltd, we are committed to incorporating sustainable practices into all our projects. We utilize eco-friendly materials, energy-efficient designs, and cutting-edge technologies to minimize our environmental impact and promote long-term sustainability. Our innovative approach to construction not only benefits the environment but also provides our clients with energy-efficient and cost-effective solutions. By choosing us, clients are investing in a future where their structures are both beautiful and environmentally responsible.
                        <br><br>
                        People choose ROYAL REFUGE HOMES Ltd because we deliver exceptional quality, offer customized and comprehensive services, and are committed to sustainability and innovation. Our reputation for excellence, our dedication to client satisfaction, and our forward-thinking approach make us the preferred choice for construction and architectural services.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6">
                            <div class="row g-4">
                                <div class="col-12 wow fadeIn" data-wow-delay="0.3s">
                                    <div class="feature-box border rounded p-4">
                                        <i class="fa fa-check fa-3x text-primary mb-3"></i>
                                        <h4 class="mb-3">Fast Executions</h4>
                                        <p class="mb-3">boasts a team of highly skilled professionals with extensive expertise in the field of architecture and building construction. </p>
                                        <a class="fw-semi-bold" href="service.php">Read More <i
                                                class="fa fa-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                                <div class="col-12 wow fadeIn" data-wow-delay="0.5s">
                                    <div class="feature-box border rounded p-4">
                                        <i class="fa fa-check fa-3x text-primary mb-3"></i>
                                        <h4 class="mb-3">Guide & Support</h4>
                                        <p class="mb-3"> Customer satisfaction is at the core of everything we do. We prioritize open communication, actively listening to your needs, and keeping you informed throughout the project lifecycle.</p>
                                        <a class="fw-semi-bold" href="service.php">Read More <i
                                                class="fa fa-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.7s">
                            <div class="feature-box border rounded p-4">
                                <i class="fa fa-check fa-3x text-primary mb-3"></i>
                                <h4 class="mb-3">Financial Secure</h4>
                                <p class="mb-3">Our pricing models are transparent, and we work with you to find the most suitable solution that aligns with your budgetary requirements.</p>
                                <a class="fw-semi-bold" href="service.php">Read More <i class="fa fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->


    <!-- Service Start -->
    <div class="container-xxl service py-5" id="services">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">Our Services</p>
                <h1 class="display-5 mb-5">ROYAL REFUGE HOMES Ltd Services</h1>
            </div>
            <div class="row g-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="col-lg-4">
                    <div class="nav nav-pills d-flex justify-content-between w-100 h-100 me-4">
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-4 active"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-1" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-primary me-3"></i>Building Construction</h5>
                        </button>
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-4"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-2" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-primary me-3"></i>Property Management</h5>
                        </button>
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-4"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-3" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-primary me-3"></i>Architectural Services</h5>
                        </button>
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-0"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-4" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-primary me-3"></i>Estate Development</h5>
                        </button>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="tab-content w-100">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="{{asset('dist/landing/img/service-1.jpg')}}"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Building Construction</h3>
                                    <p class="mb-4">At ROYAL REFUGE HOMES Ltd, our building construction services are designed to bring our clients' visions to life with precision and excellence. We handle every aspect of the construction process, from initial planning and design to the final finishing touches, ensuring that each project is completed on time, within budget, and to the highest standards. Whether it's a residential home, commercial building, or industrial facility, our experienced team leverages the latest construction technologies and best practices to deliver structures that are durable, functional, and aesthetically pleasing.</p>

                                    {{-- <a href="service.php" class="btn btn-primary py-3 px-5 mt-3">Read More</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-2">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="{{asset('dist/landing/img/service-3.jpg')}}"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Property Management</h3>
                                    <p class="mb-4">Our property management services at ROYAL REFUGE HOMES Ltd provide comprehensive solutions to ensure the smooth operation and maintenance of your real estate assets. We offer a full range of services, including tenant management, property maintenance, financial reporting, and lease administration. Our dedicated team works diligently to enhance the value of your property, maximize occupancy rates, and ensure a seamless experience for both property owners and tenants. With our professional and proactive approach, you can trust us to manage your properties with the utmost care and efficiency.</p>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-3">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="{{asset('dist/landing/img/service-2.jpg')}}"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Architectural Services</h3>
                                    <p class="mb-4">ROYAL REFUGE HOMES Ltd offers a wide array of architectural services that blend creativity with functionality to create inspiring and practical spaces. Our team of skilled architects collaborates closely with clients to understand their needs and preferences, providing innovative design solutions that reflect their vision. From concept development and 3D modeling to detailed construction drawings and project management, we ensure that every architectural project is executed with precision and attention to detail. Our commitment to sustainability and cutting-edge design ensures that our projects are not only beautiful but also environmentally responsible.</p>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-4">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="{{asset('dist/landing/img/service-4.jpg')}}"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">In estate development, ROYAL REFUGE HOMES Ltd excels at transforming raw land into vibrant, livable communities. We manage the entire development process, from land acquisition and planning to infrastructure development and construction. Our approach is holistic, ensuring that every aspect of the estate is thoughtfully designed and executed to create harmonious and sustainable living environments. By integrating modern amenities, green spaces, and efficient infrastructure, we develop estates that offer a high quality of life and a strong sense of community for residents.</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Callback Start -->
    <div class="container-fluid callback my-5 pt-5" id="contact">
        <div class="container pt-5">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="bg-white border rounded p-4 p-sm-5 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                            <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">Get In Touch
                            </p>
                            <h1 class="display-5 mb-5">Request A Call-Back</h1>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" placeholder="Your Name">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="mail" placeholder="Your Email">
                                    <label for="mail">Your Email</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="mobile" placeholder="Your Mobile">
                                    <label for="mobile">Your Mobile</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" placeholder="Subject">
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message"
                                        style="height: 100px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary w-100 py-3" type="submit">Submit Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Callback End -->


    <!-- Projects Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">Our Projects</p>
                <h1 class="display-5 mb-5">We Have Completed Latest Projects</h1>
            </div>
            <div class="owl-carousel project-carousel wow fadeInUp" data-wow-delay="0.3s">
                <div class="project-item pe-5 pb-5">
                    <div class="project-img mb-3">
                        <img class="img-fluid rounded" src="{{asset('dist/landing/img/Social Media Ad.jpg')}}" alt="">
                        <a href=""><i class="fa fa-link fa-3x text-primary"></i></a>
                    </div>
                    <div class="project-title">
                        <h4 class="mb-0">Product Ready</h4>
                    </div>
                </div>
                <div class="project-item pe-5 pb-5">
                    <div class="project-img mb-3">
                        <img class="img-fluid rounded" src="{{asset('dist/landing/img/Social-Media-001.jpg')}}" alt="">
                        <a href=""><i class="fa fa-link fa-3x text-primary"></i></a>
                    </div>
                    <div class="project-title">
                        <h4 class="mb-0">Product Ready</h4>
                    </div>
                </div>
                <div class="project-item pe-5 pb-5">
                    <div class="project-img mb-3">
                        <img class="img-fluid rounded" src="{{asset('dist/landing/img/Social-Media-002.jpg')}}" alt="">
                        <a href=""><i class="fa fa-link fa-3x text-primary"></i></a>
                    </div>
                    <div class="project-title">
                        <h4 class="mb-0">Product Ready</h4>
                    </div>
                </div>
                <div class="project-item pe-5 pb-5">
                    <div class="project-img mb-3">
                        <img class="img-fluid rounded" src="{{asset('dist/landing/img/Social-Media-004.jpg')}}" alt="">
                        <a href=""><i class="fa fa-link fa-3x text-primary"></i></a>
                    </div>
                    <div class="project-title">
                        <h4 class="mb-0">Product Ready</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Projects End -->

    <!-- Testimonial Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">Testimonial</p>
                <h1 class="display-5 mb-5">What Our Clients Say!</h1>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.3s">
                <div class="testimonial-item">
                    <div class="testimonial-text border rounded p-4 pt-5 mb-5">
                        <div class="btn-square bg-white border rounded-circle">
                            <i class="fa fa-quote-right fa-2x text-primary"></i>
                        </div>
                        “I’m always impressed with the services from STRIDDER.”
                    </div>
                    <img class="rounded-circle mb-3" src="{{asset('dist/landing/img/download.png')}}" alt="">
                    <h4>Kojo Motors</h4>
                    <span>Auto-repair</span>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-text border rounded p-4 pt-5 mb-5">
                        <div class="btn-square bg-white border rounded-circle">
                            <i class="fa fa-quote-right fa-2x text-primary"></i>
                        </div>
                        “Keep it up ROYAL REFUGE, I got my projects fixed.”
                    </div>
                    <img class="rounded-circle mb-3" src="{{asset('dist/landing/img/download (1).png')}}" alt="">
                    <h4>Realty Plus</h4>
                    <span>Realty</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

@endsection
