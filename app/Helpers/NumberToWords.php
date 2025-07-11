<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convert($number)
    {
        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'minus ';
        $decimal     = ' point '; // Ini akan diabaikan jika fraksi nol
        $dictionary  = array(
            0                   => 'nol',
            1                   => 'satu',
            2                   => 'dua',
            3                   => 'tiga',
            4                   => 'empat',
            5                   => 'lima',
            6                   => 'enam',
            7                   => 'tujuh',
            8                   => 'delapan',
            9                   => 'sembilan',
            10                  => 'sepuluh',
            11                  => 'sebelas',
            12                  => 'dua belas',
            13                  => 'tiga belas',
            14                  => 'empat belas',
            15                  => 'lima belas',
            16                  => 'enam belas',
            17                  => 'tujuh belas',
            18                  => 'delapan belas',
            19                  => 'sembilan belas',
            20                  => 'dua puluh',
            30                  => 'tiga puluh',
            40                  => 'empat puluh',
            50                  => 'lima puluh',
            60                  => 'enam puluh',
            70                  => 'tujuh puluh',
            80                  => 'delapan puluh',
            90                  => 'sembilan puluh',
            100                 => 'ratus',
            1000                => 'ribu',
            1000000             => 'juta',
            1000000000          => 'miliar',
            1000000000000       => 'triliun',
            1000000000000000    => 'kuadriliun',
            1000000000000000000 => 'kuintiliun'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . self::convert(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        // Konversi bagian bilangan bulat
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::convert($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::convert($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::convert($remainder);
                }
                break;
        }

        // Penanganan bagian desimal
        // Hanya tambahkan jika ada fraksi dan fraksi tersebut bukan '0' atau '00'
        if (null !== $fraction && is_numeric($fraction) && (int)$fraction > 0) { // <-- Perubahan di sini
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $num) { // Ubah $number menjadi $num untuk menghindari konflik variabel
                $words[] = $dictionary[$num];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}
