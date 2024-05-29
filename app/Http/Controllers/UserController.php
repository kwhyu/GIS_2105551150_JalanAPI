<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $token = $request->input('token');

            $client = new Client();

            $response = $client->post('https://gisapis.manpits.xyz/api/register', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => [ // Mengirim data sebagai payload JSON
                    'name'     => $request->input('name'),
                    'email'    => $request->input('email'),
                    'password' => $request->input('password'),
                ],
            ]);

            $body = $response->getBody();
            $content = $body->getContents();

            if ($response->getStatusCode() === 200) {
                return redirect('/login');
            } else {
                return response()->json(json_decode($content), $response->getStatusCode());
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return response()->json(['error' => 'Failed to register.'], $response->getStatusCode());
            } else {
                return response()->json(['error' => 'Failed to connect to the server.'], 500);
            }
        }
    }

    public function login(Request $request)
    {
        try {
            $client = new Client();

            $response = $client->post('https://gisapis.manpits.xyz/api/login', [
                'json' => [
                    'email'    => $request->input('email'),
                    'password' => $request->input('password'),
                ],
            ]);

            $body = $response->getBody();
            $content = $body->getContents();
            $data = json_decode($content, true); // Decode JSON response

            // if ($response->getStatusCode() === 200 && isset($data['meta']['token'])) {
            if ($response->getStatusCode() === 200) {
                $token = $data['meta']['token'];

                // Save token in the session
                // session(['api_token' => $token, 'username' => $data['user']['name']]);
                session(['token' => $token]);

                $this->getUser();

                return redirect('/')->with('success', 'Login successful.');;
            } else {
                return response()->json($data, $response->getStatusCode());
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return response()->json(['error' => 'Failed to login.'], $response->getStatusCode());
            } else {
                return response()->json(['error' => 'Failed to connect to the server.'], 500);
            }
        }
    }

    public function getUser()
    {
        try {
            $token = session('token');

            $client = new Client();
            $response = $client->get('https://gisapis.manpits.xyz/api/user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
            ]);

            $body = $response->getBody();
            $content = $body->getContents();
            $data = json_decode($content, true);

            if ($response->getStatusCode() === 200 && isset($data['data']['user']['name'])) {
                $name = $data['data']['user']['name'];
                session(['user_name' => $name]);
                return $name;
            } else {
                return response()->json($data, $response->getStatusCode());
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return response()->json(['error' => 'Failed to get user data.'], $response->getStatusCode());
            } else {
                return response()->json(['error' => 'Failed to connect to the server.'], 500);
            }
        }
    }

    public function logout(Request $request)
    {
        try {
            // Ambil token dari sesi
            $token = session('token');

            // Buat instance dari GuzzleHttp\Client
            $client = new Client();

            // Kirim request POST ke endpoint logout
            $response = $client->post('https://gisapis.manpits.xyz/api/logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
            ]);

            // Hapus token dari sesi
            $request->session()->forget('token');

            // Redirect ke halaman login atau halaman lain sesuai kebutuhan
            return redirect('/')->with('success', 'Logout successful.');
        } catch (RequestException $e) {
            // Tangani jika terjadi error saat melakukan request
            if ($e->hasResponse()) {
                // Tangani jika terdapat response dari server
                $response = $e->getResponse();
                $content = $response->getBody()->getContents();
                return response()->json(['error' => 'Failed to logout.'], $response->getStatusCode());
            } else {
                // Tangani jika tidak dapat terhubung ke server
                return response()->json(['error' => 'Failed to connect to the server.'], 500);
            }
        }
    }
}
