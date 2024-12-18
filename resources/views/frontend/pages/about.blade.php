@extends('frontend.main')
@section('title', 'Index Page')

@section('styles')
@endsection

@push('css')
@endpush

@section('extra_class')

@endsection

@section('banner')
    @include('frontend.partial._navbar')
    <section class="hero-banner position-relative custom-pt-1 custom-pb-2 bg-dark"
        data-bg-img="{{ asset('frontend/assets/images/bg/02.png') }}">
        <div class="container">
            <div class="row text-white text-center z-index-1">
                <div class="col">
                    <h1 class="text-white">About Us</h1>
                </div>
            </div>
            <!-- / .row -->
        </div>
        <!-- / .container -->
        <div class="shape-1 bottom">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#fff" fill-opacity="1"
                    d="M0,288L48,288C96,288,192,288,288,266.7C384,245,480,203,576,208C672,213,768,267,864,245.3C960,224,1056,128,1152,96C1248,64,1344,96,1392,112L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>

@endsection
@section('content')
    <!--feature start-->

    <section>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-5">
                        <h2 class="mb-0"><span class="font-w-4 d-block">Exclusive services</span> for the next creative
                            project</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex justify-content-between">
                        <div class="me-3">
                            <div class="f-icon-s p-3 rounded" data-bg-color="#88C5DD"> <i class="flaticon-dashboard"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-2">Dashboard</h5>
                            <p class="mb-0">Taking design from {{ config('app.name') }} design and typography layouts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-5 mt-md-0">
                    <div class="d-flex justify-content-between">
                        <div class="me-3">
                            <div class="f-icon-s p-3 rounded" data-bg-color="#ffeff8"> <i class="flaticon-relationship"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-2">Management</h5>
                            <p class="mb-0">Taking design from {{ config('app.name') }} design and typography layouts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-5 mt-lg-0">
                    <div class="d-flex justify-content-between">
                        <div class="me-3">
                            <div class="f-icon-s p-3 rounded" data-bg-color="#d3f6fe"> <i class="flaticon-solution"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-2">Platform</h5>
                            <p class="mb-0">Taking design from {{ config('app.name') }} design and typography layouts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-5">
                    <div class="d-flex justify-content-between">
                        <div class="me-3">
                            <div class="f-icon-s p-3 rounded" data-bg-color="#fff5d9"> <i class="flaticon-system"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-2">Integrations</h5>
                            <p class="mb-0">Taking design from {{ config('app.name') }} design and typography layouts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-5">
                    <div class="d-flex justify-content-between">
                        <div class="me-3">
                            <div class="f-icon-s p-3 rounded" data-bg-color="#fdf9ee"> <i class="flaticon-friendship"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-2">User Friendly</h5>
                            <p class="mb-0">Taking design from {{ config('app.name') }} design and typography layouts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-5">
                    <div class="d-flex justify-content-between">
                        <div class="me-3">
                            <div class="f-icon-s p-3 rounded" data-bg-color="#e5f5f5"> <i
                                    class="flaticon-call-center-1"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-2">Quick Support</h5>
                            <p class="mb-0">Taking design from {{ config('app.name') }} design and typography layouts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--feature end-->


    <!--about start-->

    <section>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-12 col-lg-6">
                    <img src="{{ asset('frontend/assets/images/about/06.png') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-12 col-lg-5 mt-5 mt-lg-0">
                    <div class="mb-4">
                        <h2><span class="font-w-4 d-block">Easily manage</span> your own business</h2>
                        <p class="lead mb-0">We use the latest technologies it voluptatem accusantium doloremque laudantium.
                        </p>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-sm">
                            <div class="mb-3 me-4 ms-lg-0 me-lg-4">
                                <div class="d-flex align-items-center">
                                    <div> <i class="las la-angle-right"></i>
                                    </div>
                                    <p class="mb-0 ms-3">Perfect Design</p>
                                </div>
                            </div>
                            <div class="mb-3 me-4 ms-lg-0 me-lg-4">
                                <div class="d-flex align-items-center">
                                    <div> <i class="las la-angle-right"></i>
                                    </div>
                                    <p class="mb-0 ms-3">More Flexible</p>
                                </div>
                            </div>
                            <div class="mb-3 me-4 ms-lg-0 me-lg-4">
                                <div class="d-flex align-items-center">
                                    <div> <i class="las la-angle-right"></i>
                                    </div>
                                    <p class="mb-0 ms-3">High Performance</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="mb-3 me-4 ms-lg-0 me-lg-4">
                                <div class="d-flex align-items-center">
                                    <div> <i class="las la-angle-right"></i>
                                    </div>
                                    <p class="mb-0 ms-3">Key Features</p>
                                </div>
                            </div>
                            <div class="mb-3 me-4 ms-lg-0 me-lg-4">
                                <div class="d-flex align-items-center">
                                    <div> <i class="las la-angle-right"></i>
                                    </div>
                                    <p class="mb-0 ms-3">Based On Bootstrap 4</p>
                                </div>
                            </div>
                            <div class="mb-3 me-4 ms-lg-0 me-lg-4">
                                <div class="d-flex align-items-center">
                                    <div> <i class="las la-angle-right"></i>
                                    </div>
                                    <p class="mb-0 ms-3">Built with Sass</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-outline-primary mt-4">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!--about end-->


    <!--testimonial start-->

    <section class="bg-pos-r" data-bg-img="{{ asset('frontend/assets/images/bg/01.png') }}">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-5">
                        <h2><span class="font-w-4 d-block">You can see our clients</span> feedback what you say?</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div id="testimonial" class="testimonial-carousel carousel slide testimonial z-index-1"
                        data-bs-ride="carousel" data-bs-interval="2500">
                        <!-- Wrapper for slides -->
                        <div class="row justify-content-center text-center">
                            <div class="col-md-7">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/01.jpg') }}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">{{ config('app.name') }} Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Raymond Lee</h5>
                                                    <small class="text-muted fst-italic">- Founder</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/02.jpg') }}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">{{ config('app.name') }} Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Dani Karie</h5>
                                                    <small class="text-muted fst-italic">- Supervisor</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/03.jpg') }}
')}}
')}}
')}}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">{{ config('app.name') }} Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Karlo Bond</h5>
                                                    <small class="text-muted fst-italic">- Manager</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/04.jpg') }}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">{{ config('app.name') }} Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Rain Meeta</h5>
                                                    <small class="text-muted fst-italic">- Ceo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/05.jpg') }}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">vC{{ config('app.name') }}ards Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Aubee Dion</h5>
                                                    <small class="text-muted fst-italic">- Ceo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/06.jpg') }}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">{{ config('app.name') }} Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Mark Beele</h5>
                                                    <small class="text-muted fst-italic">- Ceo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/07.jpg') }}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">{{ config('app.name') }} Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Nicole James</h5>
                                                    <small class="text-muted fst-italic">- Ceo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="card p-2 p-md-5 border-0">
                                            <div class="mb-3">
                                                <img alt="Image"
                                                    src="{{ asset('frontend/assets/images/testimonial/08.jpg') }}"
                                                    class="shadow-primary img-fluid rounded-circle d-inline">
                                            </div>
                                            <div class="card-body p-0">
                                                <p class="lead font-w-5">{{ config('app.name') }} Amazing Landing Page All-in-one, clean code,
                                                    Crative &amp; Modern design Professional Recommended crofessional and
                                                    great experience, Nam pulvinar vitae neque et porttitor, Praesent sed
                                                    nisi eleifend, adipisicing elit.</p>
                                                <div>
                                                    <h5 class="text-primary d-inline">Lena Shea</h5>
                                                    <small class="text-muted fst-italic">- Ceo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Item -->
                                </div>
                                <!-- End Carousel Inner -->
                            </div>
                        </div>
                        <div class="controls">
                            <ul class="nav justify-content-md-between justify-content-center">
                                <li data-bs-target="#testimonial" data-bs-slide-to="0" class="active">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/01.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                                <li class="mt-3" data-bs-target="#testimonial" data-bs-slide-to="1">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/02.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                                <li data-bs-target="#testimonial" data-bs-slide-to="2">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/03.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                                <li class="mt-3" data-bs-target="#testimonial" data-bs-slide-to="3">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/04.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                                <li data-bs-target="#testimonial" data-bs-slide-to="4">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/05.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                                <li class="mt-3" data-bs-target="#testimonial" data-bs-slide-to="5">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/06.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                                <li data-bs-target="#testimonial" data-bs-slide-to="6">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/07.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                                <li class="mt-3" data-bs-target="#testimonial" data-bs-slide-to="7">
                                    <a href="#">
                                        <img class="img-fluid rounded-circle shadow-primary"
                                            src="{{ asset('frontend/assets/images/testimonial/08.jpg') }}"
                                            alt="">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Carousel -->
                </div>
            </div>
        </div>
    </section>

    <!--testimonial end-->


    <!--newsletter start-->

    @include('frontend.partial.newsletter')

    <!--newsletter end-->

@endsection


@section('scripts')
@endsection

@push('js')
@endpush
