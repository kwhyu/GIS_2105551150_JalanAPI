<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{
    public function index()
    {
        $provinsi = [
            ['id' => 1, 'provinsi' => 'Bali'],
            ['id' => 2, 'provinsi' => 'NTT'],
            ['id' => 3, 'provinsi' => 'NTB'],
        ];

        $kabupaten = [
            ['id' => 1, 'prov_id' => 1, 'kabupaten' => 'Jembrana'],
            ['id' => 2, 'prov_id' => 1, 'kabupaten' => 'Tabanan'],
            ['id' => 3, 'prov_id' => 1, 'kabupaten' => 'Badung'],
        ];

        $kecamatan = [
            ['id' => 1, 'kab_id' => 1, 'kecamatan' => 'NEGARA'],
            ['id' => 2, 'kab_id' => 1, 'kecamatan' => 'JEMBRANA'],
            ['id' => 3, 'kab_id' => 1, 'kecamatan' => 'MENDOYO'],
        ];

        $desa = [
            ['id' => 1, 'kec_id' => 1, 'desa' => 'Cupel'],
            ['id' => 2, 'kec_id' => 1, 'desa' => 'Dauhwaru'],
            ['id' => 3, 'kec_id' => 1, 'desa' => 'Banjar Tengah'],
        ];

        return view('dashboard.edit-ruas', compact('provinsi', 'kabupaten', 'kecamatan', 'desa'));
    }

    public function getKabupaten(Request $request)
    {
        $prov_id = $request->input('prov_id');

        $kabupaten = [
            ['id' => 1, 'prov_id' => 1, 'kabupaten' => 'Jembrana'],
            ['id' => 2, 'prov_id' => 1, 'kabupaten' => 'Tabanan'],
            ['id' => 3, 'prov_id' => 1, 'kabupaten' => 'Badung'],
        ];

        $filtered_kabupaten = array_filter($kabupaten, function ($item) use ($prov_id) {
            return $item['prov_id'] == $prov_id;
        });

        return response()->json($filtered_kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $kab_id = $request->input('kab_id');

        $kecamatan = [
            ['id' => 1, 'kab_id' => 1, 'kecamatan' => 'NEGARA'],
            ['id' => 2, 'kab_id' => 1, 'kecamatan' => 'JEMBRANA'],
            ['id' => 3, 'kab_id' => 1, 'kecamatan' => 'MENDOYO'],
        ];

        $filtered_kecamatan = array_filter($kecamatan, function ($item) use ($kab_id) {
            return $item['kab_id'] == $kab_id;
        });

        return response()->json($filtered_kecamatan);
    }

    public function getDesa(Request $request)
    {
        $kec_id = $request->input('kec_id');

        $desa = [
            ['id' => 1, 'kec_id' => 1, 'desa' => 'Cupel'],
            ['id' => 2, 'kec_id' => 1, 'desa' => 'Dauhwaru'],
            ['id' => 3, 'kec_id' => 1, 'desa' => 'Banjar Tengah'],
        ];

        $filtered_desa = array_filter($desa, function ($item) use ($kec_id) {
            return $item['kec_id'] == $kec_id;
        });

        return response()->json($filtered_desa);
    }
}
