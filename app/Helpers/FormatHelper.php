<?php

namespace App\Helpers;

class FormatHelper
{
    public static function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.') . ',-';
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.') . ',-';
    }
}