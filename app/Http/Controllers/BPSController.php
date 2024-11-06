<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;

class BPSController extends Controller
{
    public function index()
    {

        $provinsis = Provinsi::all();
        $bps = public_path('data/bps.json');

        // Cek apakah file JSON ada
        if (file_exists($bps)) {
            // Ambil isi file JSON
            $data = file_get_contents($bps);

            // Decode JSON ke dalam bentuk array
            $data = json_decode($data, true);

            // Sekarang $data adalah array PHP yang berisi data dari bps.json
            $dataBps = $data['datas'];

            // sort data berdasarkan tahun
            usort($dataBps, function ($a, $b) {
                return $a['tahun'] <=> $b['tahun'];
            });
        } else {
            $dataBps = [];
        }

        // ambil tahun dari distict dataBps
        $tahun = array_unique(array_column($dataBps, 'tahun'));
        // convert tahun ke array
        $tahun = array_values($tahun);

        return view('user.bps.index', compact('provinsis', 'dataBps', 'tahun'));
    }
    public function map()
    {
        $provinsis = Provinsi::all();

        // Path ke file JSON di dalam folder public/data
        $bps = public_path('data/bps.json');

        // Cek apakah file JSON ada
        if (file_exists($bps)) {
            // Ambil isi file JSON
            $data = file_get_contents($bps);

            // Decode JSON ke dalam bentuk array
            $data = json_decode($data, true);

            // Sekarang $data adalah array PHP yang berisi data dari bps.json
            $dataBps = $data['datas'];
        } else {
            $dataBps = [];
        }

        // get year constraint from $dataBps
        $years = array_unique(array_column($dataBps, 'tahun'));

        return view('user.bps.map', compact('dataBps', 'years', 'provinsis'));
    }
}
