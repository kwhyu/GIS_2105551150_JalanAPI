<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
                    'Content-Type'  => 'application/json', // Menambahkan header Content-Type
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
                return redirect('/');
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

            if ($response->getStatusCode() === 200) {
                return redirect('/dashboard');
            } else {
                return response()->json(json_decode($content), $response->getStatusCode());
            }
            // return response()->json(json_decode($content), $response->getStatusCode());
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
}
