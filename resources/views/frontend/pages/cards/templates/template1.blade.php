<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.pages.cards.partials._meta')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/2883165f21.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{ asset('templates/template1/assets/css/all.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: "Poppins", sans-serif;
            overflow-x: hidden;
        }
        img { max-width: 100%; height: auto; }

        a {
            text-decoration: none;
            color: inherit;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .design-for-desktop {
            position: relative;
            transition: 0.3s all ease;
        }

        .top-backgound-box {
            background: #267871;
            background: linear-gradient(180deg, rgba(38, 120, 113, 1) 0%, rgba(19, 106, 138, 1) 100%);
            width: 100%;
            height: 350px;
        }

        .top-backgound-box.for-shop {
            background: rgb(75, 108, 183);
            background: linear-gradient(180deg, rgba(75, 108, 183, 1) 0%, rgba(24, 40, 72, 1) 100%);
        }

        .user-top-details {
            position: absolute;
            top: 210px;
            width: 100%;
        }

        .profile-img {
            border-radius: 50rem;
            width: 250px;
            height: 250px;
            border: 6px solid #fff;
            margin: 0 auto;
        }

        .name {
            font-size: 30px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0;
        }

        .position-tags {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 10px;
            margin-bottom: 10px;
        }

        .position-tags p {
            font-size: 14px;
            font-weight: normal;
            color: #fff;
        }

        .save-contact-btn-white {
            background-color: #fff;
            color: #1F8AFF;
            border-radius: 50rem;
            padding: 7px 60px;
            font-size: 16px;
            font-weight: 500;
            border: 1px solid #fff;
            transition: 0.3s all ease;
            display: inline-block;
            min-height: 44px;
            line-height: 28px;
            text-align: center;
        }

        .save-contact-btn-white:hover {
            background-color: #1F8AFF;
            color: #fff;
            border-color: #1F8AFF;
        }

        .social-box {
            display: flex;
            align-items: center;
            justify-content: end;
            gap: 15px;
        }

        .social-box a img {
            height: 20px;
        }

        .location {
            color: #858585;
            font-size: 16px;
        }

        .location i {
            color: #000;
            ;
        }


        .user-about-detail {
            margin-top: 150px;
        }

        .heading {
            font-size: 30px;
            font-weight: 600;
            color: #000;
            margin-bottom: 15px;
        }

        .user-about-detail .description {
            color: #686868;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 22px;
        }

        .check-me-out-box {
            margin-bottom: 30px;
        }

        .check-me-out-link {
            background-color: #F5F5F5;
            border: 0.5px solid #F9F9F9;
            padding: 10px 20px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50rem;
            text-align: center;
            font-size: 12px;
            color: #000;
            transition: 0.3s all ease;
        }

        .check-me-out-link i {
            font-size: 20px;
        }

        .check-me-out-link:hover {
            filter: brightness(0.9);
        }

        .design-for-desktop footer {
            background-color: #F5F5F5;
            border-top: 0.5px solid #F9F9F9;
            padding: 7px 20px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .design-for-desktop footer p {
            color: #000;
            font-weight: 500;
            font-size: 12px;
        }

        .design-for-desktop footer p a {
            color: #1F8AFF;
        }




        .google-rating-button {
            background-color: #F5F5F5;
            border: 0.5px solid #F9F9F9;
            padding: 10px 60px;
            width: fit-content;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50rem;
            text-align: center;
            font-size: 14px;
            color: #000;
            transition: 0.3s all ease;
            margin-bottom: 30px;
        }

        .google-rating-button i {
            color: #FED30A;
        }

        .google-rating-button img {
            margin-left: 10px;
        }

        .google-rating-button:hover {
            filter: brightness(0.9);
        }



        .ouer-team-section {
            margin-bottom: 50px;
        }

        .team-box {
            position: relative;
            display: block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 30px;
            margin-bottom: 20px;
        }

        .team-box img {
            height: 400px;
            width: 100%;
            object-fit: cover;
            object-position: center top;
            border-radius: 30px;
        }

        .team-box .team-detail-box {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            padding: 20px 20px 20px 20px;
            background: rgb(0, 0, 0);
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 81%);
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .team-box .team-detail-box .team-name {
            font-size: 16px;
            font-weight: 500;
            color: #fff;
        }

        .team-box .team-detail-box .team-designation {
            font-size: 11px;
            font-weight: 300;
            color: #fff;
        }






        .for-mobile {
            display: none;
        }

        @media only screen and (max-width: 1200px) {
            .profile-img {
                width: 200px;
                height: 200px;
            }

            .user-top-details {
                top: 235px;
            }
        }

        @media only screen and (max-width: 992px) {
            .profile-img {
                width: 170px;
                height: 170px;
            }

            .user-top-details {
                top: 250px;
            }

            .save-contact-btn-white {
                padding: 7px 40px;
            }

            .user-about-detail {
                margin-top: 100px;
            }
        }

        @media only screen and (max-width: 768px) {
            /* .design-for-desktop {
        display: none;
    } */



            .profile-img {
                width: 180px;
                height: 180px;
                margin-bottom: 20px;
            }

            .heading {
                text-align: center;
            }

            .user-about-detail .description {
                text-align: center;
            }

            .check-me-out-link {
                margin-bottom: 20px;
            }

            .design-for-desktop footer {
                background-color: transparent;
                position: relative;
            }

            .user-top-details {
                position: absolute;
                top: 110px;
            }

            .user-about-detail {
                margin-top: 330px;
            }

            .save-contact-btn-white {
                background-color: #1F8AFF;
                color: #fff;
                width: 75%;
            }

            .name {
                color: #000;
                text-align: center;
                margin-bottom: 3px;
            }

            .position-tags {
                justify-content: center;
                margin-bottom: 5px;
            }

            .position-tags p {
                color: #B6B6B6;
            }

            .location {
                text-align: center;
                margin-bottom: 15px;
            }


            .check-me-out-box {
                margin-bottom: 30px;
            }

            .social-box {
                justify-content: center;
                gap: 20px;
            }

            .social-box a img {
                height: 35px;
            }

            .lets-connect-section {
                margin-bottom: 50px;
            }

            .top-backgound-box {
                height: 215px;
            }







            .ouer-team-section {
                margin-bottom: 30px;
            }

            .google-rating-button {
                text-align: center;
                width: 75%;
                padding: 10px 10px;
                margin: 0 auto;
            }

            .team-box {
                text-align: center;
                box-shadow: none;
                margin-bottom: 10px;
            }

            .team-box img {
                width: 76px;
                height: 76px;
                border-radius: 50rem;
                border: 3px solid #1F8AFF;
            }

            .team-box .team-detail-box {
                position: relative;
                background: none;
                padding-top: 10px;
            }

            .team-box .team-detail-box .team-name {
                font-size: 14px;
                font-weight: 500;
                color: #000;
            }

            .team-box .team-detail-box .team-designation {
                font-size: 8px;
                font-weight: 300;
                color: #B9B9B9;
            }

            .catlog-box img {
                width: 100%;
                height: 100px;
                margin-bottom: 15px;
                border-radius: 20px;
                border: none;
            }

            .catlog-box .big {
                height: 215px;
            }

            .catlog-box .team-detail-box {
                position: absolute;
                left: 0;
                bottom: 0;
                width: 100%;
                padding: 20px 20px 20px 20px;
                background: rgb(0, 0, 0);
                background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 81%);
                border-bottom-left-radius: 20px;
                border-bottom-right-radius: 20px;
            }

            .catlog-box .team-detail-box .team-name {
                font-size: 12px;
                font-weight: 500;
                color: #fff;
            }

            .catlog-box .team-detail-box .team-designation {
                font-size: 7px;
                font-weight: 300;
                color: #fff;
            }

            .for-desktop {
                display: none;
            }

            .for-mobile {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="body-wrapper">
        <div class="design-for-desktop">
            <div class="top-backgound-box for-shop"></div>
            <div class="user-top-details">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="text-center">
                                <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}"
                                    alt="{{ $user->full_name }}" class="profile-img" loading="lazy">
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div>
                                        <h1 class="name">
                                            {{ getFullNameById($user->id) }}
                                        </h1>
                                        <div class="position-tags">
                                            <p>
                                                {{ $profile->organization ?? '-' }}
                                            </p>
                                            <p>
                                                {{ $profile->designation ?? '-' }}
                                            </p>
                                        </div>
                                        @if ($profile->primaryaddress && ($profile->primaryaddress->city || $profile->primaryaddress->state || $profile->primaryaddress->country))
                                            <span class="location for-mobile">
                                                <i class="fal fa-map-marker-alt me-2"></i>
                                                {{ collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') }}
                                            </span>
                                        @elseif($user->address)
                                            <span class="location for-mobile"><i class="fal fa-map-marker-alt me-2"></i>{{ $user->address }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="text-center text-lg-end text-md-end">
                                        <a href="{{ route('downloadVCard', $user->username) }}"
                                            class="save-contact-btn-white">
                                            <i class="fal fa-cloud-download-alt me-2"></i>Save Contact
                                        </a>
                                        @include('frontend.pages.cards.partials._nfc')
                                        @if ($profile->googlereview->count() > 0)
                                            @foreach ($profile->googlereview as $link)
                                                @if ($link->shortlink && ($url = $link->shortlink->shortlink ?? $link->shortlink->link))
                                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                                        class="google-rating-button for-mobile mt-4">
                                                        <i class="fas fa-star me-2"></i>{{ $link->name }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif


                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="">
                                        @if ($profile->primaryaddress && ($profile->primaryaddress->city || $profile->primaryaddress->state || $profile->primaryaddress->country))
                                            <span class="location for-desktop">
                                                <i class="fal fa-map-marker-alt me-2"></i>
                                                {{ collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') }}
                                            </span>
                                        @elseif($user->address)
                                            <span class="location for-desktop"><i class="fal fa-map-marker-alt me-2"></i>{{ $user->address }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">

                                    <div class="social-box for-desktop">
                                        @if ($profile->socials->count() > 0)
                                            @foreach ($profile->socials as $social)
                                                @php $socialUrl = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                                                @if ($socialUrl)
                                                <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social->name ?? 'Social link' }}">
                                                    <i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i>
                                                </a>
                                                @endif
                                                @endforeach
                                        @endif
                                        {{-- <a href="">
                                            <img src="assets/images/devicon_linkedin.svg" alt="">
                                        </a>
                                        <a href="">
                                            <img src="assets/images/skill-icons_instagram.svg" alt="">
                                        </a>
                                        <a href="">
                                            <img src="assets/images/logos_facebook.svg" alt="">
                                        </a>
                                        <a href="">
                                            <img src="assets/images/logos_google-gmail.svg" alt="">
                                        </a> --}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-about-detail">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="heading">
                                About Me
                            </h2>
                            <p class="description">
                                {{ $profile->bio ?: 'No description yet.' }}
                            </p>
                            @if ($profile->googlereview->count() > 0)
                                @foreach ($profile->googlereview as $link)
                                    @if ($link->shortlink && ($url = $link->shortlink->shortlink ?? $link->shortlink->link))
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="google-rating-button for-desktop">
                                        <i class="fas fa-star me-2"></i>Review {{ $link->name }}
                                    </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($profile->customlinks->count() > 0)
                <div class="check-me-out-box">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="heading">
                                    Check Us Out
                                </h2>
                                <div class="link-box">

                                    <div class="row">
                                        @foreach ($profile->customlinks as $link)
                                            @if ($link->shortlink && ($linkUrl = $link->shortlink->shortlink ?? $link->shortlink->link))
                                            <div class="col-lg-3 col-md-3 col-12">
                                                <div>
                                                    <a href="{{ $linkUrl }}" target="_blank" rel="noopener noreferrer"
                                                        class="check-me-out-link">
                                                        <i class="fal fa-globe me-2"></i> {{ $link->name ?? 'Link' }}
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            {{-- <div class="ouer-team-section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="heading">
                                Our Team
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-4">
                            <a href="" class="team-box">
                                <img src="assets/images/unsplash_WMD64tMfc4k.png" alt="">
                                <div class="team-detail-box">
                                    <p class="team-name">
                                        Jhon Ale.
                                    </p>
                                    <p class="team-designation">
                                        CEO - Co Founder
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-4">
                            <a href="" class="team-box">
                                <img src="assets/images/unsplash_WMD64tMfc4k.png" alt="">
                                <div class="team-detail-box">
                                    <p class="team-name">
                                        Jhon Ale.
                                    </p>
                                    <p class="team-designation">
                                        CEO - Co Founder
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-4">
                            <a href="" class="team-box">
                                <img src="assets/images/unsplash_WMD64tMfc4k.png" alt="">
                                <div class="team-detail-box">
                                    <p class="team-name">
                                        Jhon Ale.
                                    </p>
                                    <p class="team-designation">
                                        CEO - Co Founder
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="ouer-team-section d-none">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="heading">
                                Google’s Catalog
                            </h2>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-3 col-md-6 col-6">
                            <a href="" class="team-box catlog-box">
                                <img class="big"
                                    src="https://hips.hearstapps.com/vader-prod.s3.amazonaws.com/1605551269-chromecast-with-google-tv-1605551260.jpg"
                                    alt="">
                                <div class="team-detail-box">
                                    <p class="team-name">
                                        Product One
                                    </p>
                                    <p class="team-designation">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-9 col-md-6 col-6">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-12">
                                    <a href="" class="team-box catlog-box">
                                        <img src="https://hips.hearstapps.com/vader-prod.s3.amazonaws.com/1605553347-google-nest-audio-1605553335.jpg"
                                            alt="">
                                        <div class="team-detail-box">
                                            <p class="team-name">
                                                Product One
                                            </p>
                                            <p class="team-designation">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <a href="" class="team-box catlog-box">
                                        <img src="https://hips.hearstapps.com/vader-prod.s3.amazonaws.com/1663873765-google-pixel-buds-pro-1663873758.jpg"
                                            alt="">
                                        <div class="team-detail-box">
                                            <p class="team-name">
                                                Product One
                                            </p>
                                            <p class="team-designation">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($profile->socials->count() > 0)
                <div class="lets-connect-section for-mobile">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="heading">
                                    Let’s Connect
                                </h2>
                            </div>
                            <div class="col-12">
                                <div class="social-box">
                                    @if ($profile->socials->count() > 0)
                                        @foreach ($profile->socials as $social)
                                            @php $mUrl = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                                            @if ($mUrl)
                                            <a href="{{ $mUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social->name ?? 'Social' }}">
                                                <i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i>
                                            </a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @include('frontend.pages.cards.partials._shop')

            <footer class="p-3">
                <p>
                    Powered by <a href="{{ route('home') }}" rel="noopener">{{ config('app.name') }}</a>
                </p>
                <p class="mb-0">For custom website building or customization, contact us. <a href="https://wa.me/447561498786" target="_blank" rel="noopener noreferrer">WhatsApp</a></p>
            </footer>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
