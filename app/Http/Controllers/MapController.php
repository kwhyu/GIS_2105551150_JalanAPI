<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


use Illuminate\Http\Request;

class MapController extends Controller
{
    public function showpoly()
    {
        // Mengambil data dari API
        $response = Http::get('https://gisapis.manpits.xyz/api/ruasjalan');
        
        // Mengambil data ruas jalan dari respons API
        $ruasJalan = $response->json()['ruasjalan'];

        // Mengirim data ke tampilan
        return view('dashboard.dashboard', compact('ruasJalan'));
    }
}
