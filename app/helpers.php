<?php

if (!function_exists('normalizePhone')) {
    function normalizePhone(string $phone): string
    {
        // Hanya angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Ganti awalan 0 ke 62
        if (preg_match('/^0/', $phone)) {
            $phone = preg_replace('/^0/', '62', $phone);
        }

        return $phone;
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('formatRupiahWithSuffix')) {
    function formatRupiahWithSuffix($amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.') . ',-';
    }
}
