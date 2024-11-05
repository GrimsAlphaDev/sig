<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\panen;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LandingPageController extends Controller
{
    public function index()
    {
        $provinsi = Provinsi::all();

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

        return view('welcome', compact('provinsi', 'dataBps'));
    }

    public function dataPanen()
    {
        $provinsi = Provinsi::all();

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
        return view('data_panen', compact('provinsi', 'dataBps'));
    }

    public function bpsMaps()
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

        return view('bps_maps', compact('dataBps', 'years', 'provinsis'));
    }

    public function sendAduan(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'subject.required' => 'Subject harus diisi',
            'message.required' => 'Message harus diisi'
        ]);

        $aduan = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unreply'
        ];

        Aduan::create($aduan);

        return redirect('/')->with('success', 'Pesan berhasil dikirim');
    }
}
