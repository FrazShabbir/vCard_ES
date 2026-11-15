<?php

use App\Models\GeneralSetting;
use App\Models\Shop;
use App\Models\Slide;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


if (!function_exists('fromSettings')) {
    function fromSettings(string $key, $alternative = null)
    {
        return GeneralSetting::where('key', $key)->first()->value ?? $alternative;
    }
}

if (!function_exists('setSettings')) {
    function setSettings(string $key, string $value)
    {
        GeneralSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        return true;
    }
}

if (!function_exists('getuser')) {
    function getuser()
    {
        return Auth::user();
    }
}

if (!function_exists('getFullName')) {
    function getFullName()
    {
        $user = Auth::user();
        return $user->first_name . ' ' . $user->last_name;
    }
}
if (!function_exists('getFullNameById')) {
    function getFullNameById($id)
    {
        $user = User::find($id);
        return $user->first_name . ' ' . $user->last_name;
    }
}
if (!function_exists('getUserStatus')) {
    function getUserStatus($id)
    {
        $user = User::find($id);
        if ($user->status == 1) {
            return 'Active';
        } else {
            return 'In Active';
        }
    }
}
// make a function that will print 15 char random string
if (!function_exists('getRandomString')) {
    function getRandomString($length = 15)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('generateAlphaNumeric')) {
    function generateAlphaNumeric($length = 3)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $random1 = rand(100, 999);
        $random2 = rand(100, 999);

        $alphanumeric = $randomString . $random1 . $random2;
        return $alphanumeric;
    }
}

if (!function_exists('showGreetings')) {

    function showGreetings()
    {
        $hour = date('H');
        if ($hour < 12) {
            return 'Good Morning';
        } else if ($hour < 17) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }

    }
}
if (!function_exists('generateAlpha')) {
    function generateAlpha($length = 5)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        // $random1 = rand(100, 999);
        // $random2 = rand(100, 999);

        $alphanumeric = $randomString;
        return $alphanumeric;
    }
}
if (!function_exists('generateNumeric')) {
    function generateNumeric()
    {
        $random1 = rand(100, 999);
        $random2 = rand(100, 999);
        $random3 = rand(100, 999);

        $alphanumeric = $random1 . $random2 . $random3;
        return $alphanumeric;
    }
}
if (!function_exists('generateNumericSix')) {
    function generateNumericSix()
    {
        $random1 = rand(10, 99);
        $random2 = rand(10, 99);
        $random3 = rand(10, 99);

        $alphanumeric = $random1 . $random2 . $random3;
        return $alphanumeric;
    }
}

if (!function_exists('getStatus')) {
    function getStatus($num)
    {
        if ($num == 1) {
            return 'Active';
        } elseif ($num == 0) {
            return 'In Active';
        } else {
            return 'Contact Support';
        }
    }
}

if (!function_exists('getSlider')) {
    function getSlider()
    {
        $slides = Slide::all();
        return $slides;
    }

}

if (!function_exists('checkSocials')) {
    function checkSocials($id)
    {
        $user = User::find($id);
        if ($user->youtube or $user->instagram or $user->facebook or $user->twitter or $user->tiktok or $user->google or $user->linkedin or $user->pinterest) {
            return true;
        } else {
            return false;
        }

    }

}

if (!function_exists('getShopCode')) {
    function getShopCode($name)
    {

        $name = strtolower(str_replace(' ', '-', $name));

        $shop = Shop::where('slug', $name)->first();
        if ($shop) {
            $name = $name . '-' . generateAlphaNumeric();
        }
        return $name . '-' . generateAlphaNumeric();
    }

}

if (!function_exists('getProductCode')) {
    function getProductCode($name)
    {

        $name = strtolower(str_replace(' ', '-', $name));

        $name = $name . '-' . date('Ymdhis');
        return $name;

    }

}

if (!function_exists('maxPricePaisa')) {
    function maxPricePaisa(): int
    {
        return 1_000_000_000 * 100;
    }
}

if (!function_exists('toPaisa')) {
    function toPaisa($rupees): int
    {
        return (int) round(((float) $rupees) * 100);
    }
}

if (!function_exists('fromPaisa')) {
    function fromPaisa(int $paisa): float
    {
        return $paisa / 100;
    }
}

if (!function_exists('formatPkr')) {
    function formatPkr(int $paisa): string
    {
        return 'Rs ' . number_format(fromPaisa($paisa), 0, '.', ',');
    }
}
