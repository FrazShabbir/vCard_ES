<!DOCTYPE html>
<html lang="en">

<head>

    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="keywords" content="bootstrap 5, premium, multipurpose, sass, scss, saas" />
    <meta name="description" content="Bootstrap 5 Landing Page Template" />
    <meta name="author" content="www.themeht.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title -->
    <title>{{ config('app.name') }} - Create New Account</title>

    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.png') }}" />

    <!-- inject css start -->

    <!--== bootstrap -->
    <link href="{{ asset('frontend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!--== animate -->
    <link href="{{ asset('frontend/assets/css/animate.css') }}" rel="stylesheet" type="text/css" />

    <!--== line-awesome -->
    <link href="{{ asset('frontend/assets/css/line-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <!--== magnific-popup -->
    <link href="{{ asset('frontend/assets/css/magnific-popup.css') }}" rel="stylesheet" type="text/css" />

    <!--== owl.carousel -->
    <link href="{{ asset('frontend/assets/css/owl.carousel.css') }}" rel="stylesheet" type="text/css" />

    <!--== spacing -->
    <link href="{{ asset('frontend/assets/css/spacing.css') }}" rel="stylesheet" type="text/css" />

    <!--== theme.min -->
    <link href="{{ asset('frontend/assets/css/theme.min.css') }}" rel="stylesheet" />

    <!-- inject css end -->

</head>

<body>

    <!-- page wrapper start -->

    <div class="page-wrapper">

        <!-- preloader start -->

        @include('frontend.partial._preloader')


        <!-- preloader end -->


        <!--header start-->

        @include('frontend.partial._navbar')


        <!--hero section start-->

        <section class="hero-banner position-relative custom-pt-1 custom-pb-2 bg-dark"
            data-bg-img="{{ asset('frontend/assets/images/bg/02.png') }}')}}">
            <div class="container">
                <div class="row text-white text-center z-index-1">
                    <div class="col">
                        <h1 class="text-white">Create New Account</h1>
                        <nav aria-label="breadcrumb">

                        </nav>
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

        <!--hero section end-->


        <!--body content start-->

        <div class="page-content">

            <!--login start-->
            <section class="register">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-lg-8 col-md-12">
                            <div class="mb-5">
                                <h2><span class="font-w-4">Create Your</span> vCard Account</h2>
                                <p class="lead">Fill in your details below. Fields marked with * are required. Your profile will be available at {{ config('app.url') }}/<strong>{{ old('slug', $slug ?? 'your-name') }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <div class="col-lg-8 col-md-10 ms-auto me-auto">
                            <div class="register-form text-center">
                                <form id="register-form" method="post" action="{{ route('register') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="slug" value="{{ old('slug', $slug ?? '') }}">
                                    <div class="messages"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="form_name_fname" class="form-label">First name <span class="text-danger">*</span></label>
                                                <input id="form_name_fname" type="text" name="first_name"
                                                    class="form-control @error('first_name') is-invalid @enderror"
                                                    placeholder="e.g. John" value="{{ old('first_name') }}" required
                                                    maxlength="100" autocomplete="given-name">
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="form_name_lname" class="form-label">Last name <span class="text-danger">*</span></label>
                                                <input id="form_name_lname" type="text" name="last_name"
                                                    class="form-control @error('last_name') is-invalid @enderror"
                                                    placeholder="e.g. Smith" value="{{ old('last_name') }}" required
                                                    maxlength="100" autocomplete="family-name">
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="form_name_email" class="form-label">Email address <span class="text-danger">*</span></label>
                                                <input id="form_name_email" type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="e.g. john.smith@example.com" value="{{ old('email') }}"
                                                    required maxlength="255" autocomplete="email">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="form_name_number" class="form-label">Phone number <span class="text-danger">*</span></label>
                                                <input id="form_name_number" type="tel" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    placeholder="e.g. +44 7700 900123 or 07700900123" value="{{ old('phone') }}"
                                                    required maxlength="20" autocomplete="tel">
                                                <small class="form-text text-muted">UK or international format</small>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="account_type" class="form-label">Account type <span class="text-danger">*</span></label>
                                                <select name="account_type" id="account_type" class="form-control @error('account_type') is-invalid @enderror" required>
                                                    <option value="">Select account type</option>
                                                    <option value="personal" {{ old('account_type') === 'personal' ? 'selected' : '' }}>Personal</option>
                                                    <option value="business" {{ old('account_type') === 'business' ? 'selected' : '' }}>Business</option>
                                                </select>
                                                @error('account_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="referral_code" class="form-label">Referral code <span class="text-muted">(optional)</span></label>
                                                <input id="referral_code" type="text" name="referral_code"
                                                    class="form-control" placeholder="If you have a referral code"
                                                    value="{{ old('referral_code', request('referral_code')) }}" maxlength="50">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="form_password" class="form-label">Password <span class="text-danger">*</span></label>
                                                <input id="form_password" type="password" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="Min 8 characters" required autocomplete="new-password">
                                                <small class="form-text text-muted">At least 8 characters</small>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="form_password1" class="form-label">Confirm password <span class="text-danger">*</span></label>
                                                <input id="form_password1" type="password" name="password_confirmation"
                                                    class="form-control" placeholder="Confirm your password" required
                                                    autocomplete="new-password">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address" class="form-label">Address <span class="text-muted">(optional)</span></label>
                                                <input id="address" type="text" name="address" class="form-control"
                                                    placeholder="e.g. 123 High Street, London" value="{{ old('address') }}"
                                                    maxlength="500" autocomplete="street-address">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="remember-checkbox clearfix mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror"
                                                        id="customCheck1" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="customCheck1">
                                                        I agree to the <a href="{{ route('privacy') }}" target="_blank" rel="noopener">terms of use and privacy policy</a> <span class="text-danger">*</span>
                                                    </label>
                                                    @error('terms')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-check mb-4">
                                                <input type="checkbox" class="form-check-input" id="marketing_consent"
                                                    name="marketing_consent" value="1" {{ old('marketing_consent') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="marketing_consent">
                                                    I would like to receive news and offers by email <span class="text-muted">(optional)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-primary">Create account</button>
                                            <span class="mt-4 d-block">Already have an account? <a href="{{ route('login') }}"><i>Sign in</i></a></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!--login end-->


            <!--newsletter start-->

            @include('frontend.partial.newsletter')


            <!--newsletter end-->

        </div>

        <!--body content end-->


        <!--footer start-->

        @include('frontend.partial._footer')

        <!--footer end-->

    </div>

    <!-- page wrapper end -->


    <!--back-to-top start-->

    <div class="scroll-top"><a class="smoothscroll" href="#top">Scroll Top</a></div>

    <!--back-to-top end-->

    <!-- inject js start -->

    <!--== jquery -->
    <script src="{{ asset('frontend/assets/js/jquery.min.js') }}"></script>

    <!--== bootstrap -->
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!--== owl-carousel -->
    <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>

    <!--== magnific-popup -->
    <script src="{{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>

    <!--== counter -->
    <script src="{{ asset('frontend/assets/js/counter.js') }}"></script>

    <!--== countdown -->
    <script src="{{ asset('frontend/assets/js/jquery.countdown.min.js') }}"></script>

    <!--== particles -->
    <script src="{{ asset('frontend/assets/js/particles.js') }}"></script>

    <!--== typer -->
    <script src="{{ asset('frontend/assets/js/typer.js') }}"></script>

    <!--== wow -->
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>

    <!--== theme-script -->
    <script src="{{ asset('frontend/assets/js/theme-script.js') }}"></script>

    <!-- inject js end -->

    <script>
        $(document).ready(function() {
            // Function to update the URL with the referral_code parameter
            function updateReferralCodeInUrl(value) {
                const url = new URL(window.location.href);
                if (value) {
                    url.searchParams.set('referral_code', value);
                } else {
                    url.searchParams.delete('referral_code');
                }
                // Update the URL without reloading the page
                window.history.pushState({}, '', url);
            }

            // Get the referral_code from the URL on page load
            const initialReferralCode = "{{ old('referral_code', request('referral_code')) }}";
            $('#referral_code').val(initialReferralCode);

            // Add event listener to update the URL when the input changes
            $('#referral_code').on('input', function() {
                updateReferralCodeInUrl($(this).val());
            });
        });
    </script>

</body>


</html>
