<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.pages.cards.partials._meta')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
    <style>
        html {
            width: 100%;
            height: 100%;
        }

        body {

            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            -webkit-animation: Gradient 15s ease infinite;
            -moz-animation: Gradient 15s ease infinite;
            animation: Gradient 15s ease infinite;
        }

        p {
            margin: 10px;
        }

        @-webkit-keyframes Gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @-moz-keyframes Gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes Gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }


        .banner {
            background-image: linear-gradient(to bottom, #333, #666);
            background-size: 100% 300px;
            background-position: 0% 100%;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 36px;
            font-weight: bold;
            border-radius: 20px 20px 0 0;
            position: relative;
            /* Add this to make the banner a positioning context */
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: absolute;
            /* Make the image absolute */
            top: 220px;
            /* Adjust the top margin to make it overlap the banner */
            left: 50%;
            /* Center the image horizontally */
            transform: translateX(-50%);
            /* Center the image horizontally */
            z-index: 1;
            /* Make sure the image is on top of the banner */
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .info {
            background-color: #fff;
            padding: 20px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .social-media {
            margin-top: 20px;
            text-align: center;
            /* Center the icons horizontally */
        }

        .social-media a {
            margin: 10px;
            font-size: 24px;
            color: #333;
        }

        .social-media a:hover {
            color: #666;
        }

        /* Add styles for the shop section */
        .shop {
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product {
            display: inline-block;
            margin: 20px;
            width: 250px;
            height: 350px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .product img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
            transition: transform 0.5s ease-in-out;
        }

        .product:hover img {
            transform: scale(1.1);
        }

        .product-info {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 10px;
            text-align: center;
            transition: opacity 0.5s ease-in-out;
            opacity: 1;
        }

        .product:hover.product-info {
            opacity: 1;
        }


        /* footer */
        .footer-box {
            text-align: center;
            padding: 0px 30px 10px 0;
        }

        .footer-box.text {
            color: #000;
            font-size: 12px;
            font-weight: 500;
        }

        .footer-box.text a {
            color: #1F8AFF;
            font-weight: 600;
        }
        * { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }
        body { overflow-x: hidden; }
        .container { max-width: 100%; padding-left: 1rem; padding-right: 1rem; }
        img { max-width: 100%; height: auto; }
        .btn { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; padding: 0.6rem 1.25rem; }
        @media (max-width: 768px) {
            .banner { height: 220px; font-size: 1.5rem; background-size: 100% 220px; }
            .profile-pic { width: 120px; height: 120px; top: 160px; }
            .info { padding: 1rem; }
            .product { width: 100%; max-width: 280px; margin: 0.75rem auto; display: block; }
        }
        @media (max-width: 480px) {
            .container.mt-2 { margin-top: 0.5rem !important; }
            .col-md-6 { padding: 0 0.5rem; }
        }
    </style>
</head>

<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="banner">
                    <h1>Contact Card</h1>
                    <div class="profile-pic">
                        <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}"
                            alt="{{ $user->full_name }}" class="img-fluid" loading="lazy">
                    </div>
                </div>
                <div class="info">
                    <h2>{{ $user->full_name }}</h2>
                    @if ($profile->organization || $profile->designation)
                        <p>{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
                    @endif
                    @if ($profile->primaryaddress && ($profile->primaryaddress->city || $profile->primaryaddress->state || $profile->primaryaddress->country))
                        <p>{{ collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(' · ') }}</p>
                    @elseif($user->address)
                        <p>{{ $user->address }}</p>
                    @endif
                    @if ($user->email)
                        <p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                    @endif
                    <p>{{ $profile->bio ?: 'No description yet.' }}</p>

                    <div class="text-center">
                        <a href="{{ route('downloadVCard', $user->username) }}" class="btn btn-block btn-primary">Save Contact</a>
                        @include('frontend.pages.cards.partials._nfc')
                    </div>
                </div>
                <div class="social-media my-3">
                    @if ($profile->socials->count() > 0)
                        @foreach ($profile->socials as $social)
                            @php $sUrl = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                            @if ($sUrl)
                            <a href="{{ $sUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social->name ?? 'Social' }}">
                                <i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i>
                            </a>
                            @endif
                        @endforeach
                    @endif
                </div>


            </div>
        </div>

    </div>

    @include('frontend.pages.cards.partials._shop')

    <footer>
        <div class="footer-box m-2">
            <p class="text">
                Powered by <a href="{{ route('home') }}" rel="noopener">{{ config('app.name') }}</a>
            </p>
            <p class="mb-0">For custom website building or customization, contact us. <a href="https://wa.me/447561498786" target="_blank" rel="noopener noreferrer">WhatsApp</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
