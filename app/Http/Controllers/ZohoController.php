<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZohoController extends Controller
{
    // 🔐 Your Zoho App Credentials (Hardcoded for development)
    private $clientId = '1000.8TOOG81Y5VEV5YYU0GA48R9EZ0XMGN';
    private $clientSecret = '30b44316d59d88e4132ecadd90cc0f69a48154299e';
    private $redirectUri = 'https://payzone.2ndproject.net/api/callback.php';
    private $zohoBaseUrl = 'https://accounts.zoho.in';

    /**
     * Step 1: Redirect to Zoho Authorization Page
     */
    public function redirectToZoho()
    {
        $url = $this->zohoBaseUrl . "/oauth/v2/auth?" . http_build_query([
            'scope' => 'AaaServer.profile.Read,Directory.users.READ',
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'access_type' => 'offline',
            'redirect_uri' => $this->redirectUri,
        ]);

        return redirect($url);
    }

    /**
     * Step 2: Handle Callback and Exchange Code for Tokens
     */
    public function handleZohoCallback(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $code = $request->code;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->zohoBaseUrl . '/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'code' => $code,
            ]),
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json(['error' => $error], 500);
        }

        $data = json_decode($response, true);

        // Save refresh token for future use
        if (isset($data['refresh_token'])) {
            file_put_contents(storage_path('app/zoho_refresh_token.txt'), $data['refresh_token']);
        }

        return response()->json($data);
    }

    /**
     * Step 3: Generate New Access Token Using Refresh Token
     */
    public function refreshAccessToken()
    {
        $refreshToken = null;

        if (file_exists(storage_path('app/zoho_refresh_token.txt'))) {
            $refreshToken = trim(file_get_contents(storage_path('app/zoho_refresh_token.txt')));
        }

        if (!$refreshToken) {
            return response()->json(['error' => 'No refresh token found'], 400);
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->zohoBaseUrl . '/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
            ]),
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json(['error' => $error], 500);
        }

        return response()->json(json_decode($response, true));
    }
}
