<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @param string|int|float $value
 * @return float|int
 */
if (!function_exists('unformat_number')) {
    function unformat_number($value)
    {
        return str_replace(',', '', $value);;

        // if ($value === null || $value === '') return 0;

        // $value = preg_replace('/[^\d.,-]/u', '', $value);

        // if (preg_match('/\d+\.\d+,\d+/', $value)) {
        //     $value = str_replace('.', '', $value);
        //     $value = str_replace(',', '.', $value);
        // }
        // elseif (preg_match('/\d+,\d+\.\d+/', $value)) {
        //     $value = str_replace(',', '', $value);
        // }
        // elseif (preg_match('/\d+ \d+,\d+/', $value)) {
        //     $value = str_replace(' ', '', $value);
        //     $value = str_replace(',', '.', $value);
        // }
        // else {
        //     $value = str_replace(['.', ',', ' '], ['', '.', ''], $value);
        // }

        // return (strpos($value, '.') !== false) ? floatval($value) : intval($value);
    }
}
