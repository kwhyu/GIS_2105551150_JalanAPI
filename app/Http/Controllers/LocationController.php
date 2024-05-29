<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;




class LocationController extends Controller
{
    public function showAddRuasJalan()
    {
        $provinsi = [];
        $kabupaten = [];
        $kecamatan = [];
        $desa = [];
        $perkerasanEksisting = [];
        $jenisJalan = [];
        $kondisiJalan = [];

        // Mendapatkan token dari sesi
        $token = Session::get('token');

        // Inisialisasi client GuzzleHttp
        $client = new Client();

        try {
            // Lakukan permintaan GET ke API mregion
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/mregion', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data provinsi, kabupaten, dan kecamatan ke dalam variabel terpisah
            $provinsi = $data['provinsi'];
            $kabupaten = $data['kabupaten'];
            $kecamatan = $data['kecamatan'];
            $desa = $data['desa'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        try {
            // Lakukan permintaan GET ke API meksisting
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/meksisting', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data perkerasan eksisting ke dalam variabel
            $perkerasanEksisting = $data['eksisting'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        try {
            // Lakukan permintaan GET ke API mjenisjalan
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/mjenisjalan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data jenis jalan ke dalam variabel
            $jenisJalan = $data['eksisting'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        try {
            // Lakukan permintaan GET ke API mkondisi
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/mkondisi', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
                ]
            ]);

            // Mendapatkan data JSON dari respons
            $data = json_decode($response->getBody()->getContents(), true);

            // Simpan data kondisi jalan ke dalam variabel
            $kondisiJalan = $data['eksisting'];
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Tampilkan data
        return view('dashboard.tambah-ruas', compact('provinsi', 'kabupaten', 'kecamatan', 'desa', 'perkerasanEksisting', 'jenisJalan', 'kondisiJalan'));
    }


    public function view()
    {
        if (Session::has('token')) {
            return $this->getRuasJalan('dashboard.dashboard-main2');
        }

        return view('dashboard.dashboard-main2');
    }

    public function getRuasJalan($viewType)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['ruasjalan']) && !empty($data['ruasjalan'])) {
                $ruasJalanDetails = [];

                // Loop through each ruas jalan and collect all necessary details
                foreach ($data['ruasjalan'] as $ruas) {
                    $latLngArray = $this->decodePolyline($ruas['paths']);

                    // Add additional details to the array
                    $ruasJalanDetails[] = [
                        'id' => $ruas['id'],
                        'paths' => $latLngArray,
                        'desa_id' => $ruas['desa_id'],
                        'kode_ruas' => $ruas['kode_ruas'],
                        'nama_ruas' => $ruas['nama_ruas'],
                        'panjang' => $ruas['panjang'],
                        'lebar' => $ruas['lebar'],
                        'eksisting_id' => $ruas['eksisting_id'],
                        'kondisi_id' => $ruas['kondisi_id'],
                        'jenisjalan_id' => $ruas['jenisjalan_id'],
                        'keterangan' => $ruas['keterangan']
                    ];
                }
                return view($viewType, compact('ruasJalanDetails'));
                // return view('dashboard.dashboard-main2', compact('ruasJalanDetails'));
            } else {
                // Jika array 'ruasjalan' kosong atau tidak diset, kembalikan view tanpa data polyline
                // return view('dashboard.dashboard-main2');
                return view($viewType);
            }
        } catch (\Exception $e) {
            // Tangani semua pengecualian
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRuasJalanForEdit()
    {
        return $this->getRuasJalan('dashboard.edit-ruas');
    }


//     public function getRuasJalanForEdit()
// {
//     $token = Session::get('token');
//     $client = new Client();

//     try {
//         $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan', [
//             'headers' => [
//                 'Authorization' => 'Bearer ' . $token
//             ],
//         ]);

//         $data = json_decode($response->getBody(), true);

//         if (isset($data['ruasjalan']) && !empty($data['ruasjalan'])) {
//             $ruasJalanDetails = [];

//             // Loop through each ruas jalan and collect all necessary details
//             foreach ($data['ruasjalan'] as $ruas) {
//                 $latLngArray = $this->decodePolyline($ruas['paths']);

//                 // Add additional details to the array
//                 $ruasJalanDetails[] = [
//                     'id' => $ruas['id'],
//                     'paths' => $latLngArray,
//                     'desa_id' => $ruas['desa_id'],
//                     'kode_ruas' => $ruas['kode_ruas'],
//                     'nama_ruas' => $ruas['nama_ruas'],
//                     'panjang' => $ruas['panjang'],
//                     'lebar' => $ruas['lebar'],
//                     'eksisting_id' => $ruas['eksisting_id'],
//                     'kondisi_id' => $ruas['kondisi_id'],
//                     'jenisjalan_id' => $ruas['jenisjalan_id'],
//                     'keterangan' => $ruas['keterangan']
//                 ];
//             }
//             return view('dashboard.edit-ruas', compact('ruasJalanDetails'));
//         } else {
//             return view('dashboard.edit-ruas');
//         }
//     } catch (\Exception $e) {
//         // Tangani semua pengecualian
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

    // public function getRuasJalanForEdit()
    // {
    //     return $this->getRuasJalan('dashboard.edit-ruas');
    // }

    // public function getRuasJalan()
    // {
    //     $token = Session::get('token');
    //     $client = new Client();

    //     try {
    //         $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan', [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $token
    //             ],
    //         ]);

    //         $data = json_decode($response->getBody(), true);

    //         if (isset($data['ruasjalan']) && !empty($data['ruasjalan'])) {
    //             $polylines = [];

    //             // Loop through each ruas jalan and decode the polyline
    //             foreach ($data['ruasjalan'] as $ruas) {
    //                 $paths = $ruas['paths'];
    //                 $latLngArray = $this->decodePolyline($paths);
    //                 $polylines[] = $latLngArray;
    //             }
    //             return view('dashboard.dashboard-main2', compact('polylines'));
    //         } else {
    //             // Jika array 'ruasjalan' kosong atau tidak diset, kembalikan view tanpa data polyline
    //             return view('dashboard.dashboard-main2');
    //         }
    //     } catch (\Exception $e) {
    //         // Tangani semua pengecualian
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    private function decodePolyline($encoded)
    {
        $index = $lat = $lng = $i = 0;
        $latlngs = [];

        while ($index < strlen($encoded)) {
            $shift = $result = 0;
            do {
                $bit = ord(substr($encoded, $index++)) - 63;
                $result |= ($bit & 0x1f) << $shift;
                $shift += 5;
            } while ($bit >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            $shift = $result = 0;
            do {
                $bit = ord(substr($encoded, $index++)) - 63;
                $result |= ($bit & 0x1f) << $shift;
                $shift += 5;
            } while ($bit >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            $latlngs[] = [$lat * 1e-5, $lng * 1e-5];
        }

        return $latlngs;
    }


    public function submitRuasJalan(Request $request)
    {
        $token = Session::get('token');
        $client = new Client();

        try {
            $response = $client->request('POST', 'https://gisapis.manpits.xyz/api/ruasjalan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'form_params' => [
                    'paths' => $request->paths,
                    'desa_id' => $request->desa_id,
                    'kode_ruas' => $request->kode_ruas,
                    'nama_ruas' => $request->nama_ruas,
                    'panjang' => $request->panjang,
                    'lebar' => $request->lebar,
                    'eksisting_id' => $request->eksisting_id,
                    'kondisi_id' => $request->kondisi_id,
                    'jenisjalan_id' => $request->jenisjalan_id,
                    'keterangan' => $request->keterangan,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Handle response as needed
            // For example, redirect back with success message
            return redirect()->back()->with('success', 'Ruas Jalan berhasil ditambahkan');
        } catch (\Exception $e) {
            // Handle exception
            return redirect()->back()->with('error', 'Gagal menambahkan Ruas Jalan: ' . $e->getMessage());
        }
    }
    // public function getRuasJalanForEdit()
    // {

    //     $token = Session::get('token');
    //     $client = new Client();

    //     try {
    //         // Lakukan permintaan GET ke API ruas jalan
    //         $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan', [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $token // Gunakan token dari sesi
    //             ]
    //         ]);

    //         // Mendapatkan data JSON dari respons
    //         $data = json_decode($response->getBody()->getContents(), true);

    //         // Tangkap data ruas jalan
    //         $ruasJalan = [];

    //         foreach ($data['ruasjalan'] as $ruas) {
    //             // Menyiapkan data ruas jalan agar sesuai dengan struktur yang diharapkan
    //             $ruasJalan[] = [
    //                 'id' => $ruas['id'],
    //                 'paths' => $ruas['paths'],
    //                 'desa_id' => $ruas['desa_id'],
    //                 'kode_ruas' => $ruas['kode_ruas'],
    //                 'nama_ruas' => $ruas['nama_ruas'],
    //                 'panjang' => $ruas['panjang'],
    //                 'lebar' => $ruas['lebar'],
    //                 'eksisting_id' => $ruas['eksisting_id'],
    //                 'kondisi_id' => $ruas['kondisi_id'],
    //                 'jenisjalan_id' => $ruas['jenisjalan_id'],
    //                 'keterangan' => $ruas['keterangan'],
    //             ];
    //         }

    //         // Tampilkan view dengan data ruas jalan
    //         return view('dashboard.edit-ruas', compact('ruasJalan'));
    //     } catch (\Exception $e) {
    //         // Tangani kesalahan jika ada
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
}
