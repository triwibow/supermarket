<?php

if (!function_exists('nomor')) {
    function nomor($currentPage, $perPage)
    {
        if (is_null($currentPage)) {
            $nomor = 1;
        } else {
            $nomor = 1 + ($perPage * ($currentPage - 1));
        }
        return $nomor;
    }
}


function rupiah($angka){

    $hasil_rupiah = "" . number_format($angka,2,',','.');
    return "Rp ".$hasil_rupiah;

}