<?php

namespace App\Helpers;

use Midtrans\Config;

class MidtransConfig
{
    public static function set()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}

