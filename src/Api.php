<?php
namespace Allegro\REST;

class Api extends Resource
{

    const API_URI = 'https://allegroapi.io/';

    const TOKEN_URI = 'https://ssl.allegro.pl/auth/oauth/token';

    const AUTHORIZATION_URI = 'https://ssl.allegro.pl/auth/oauth/authorize';

    public function __construct($clientId, $clientSecret, $apiKey, $redirectUri,
                                $accessToken = null, $refreshToken = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->apiKey = $apiKey;
        $this->redirectUri = $redirectUri;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function getUri()
    {
        return static::API_URI;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getAuthorizationUri()
    {
        $data = array(
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'api-key' => $this->apiKey,
            'redirect_uri' => $this->redirectUri
        );

        return static::AUTHORIZATION_URI . '?' . http_build_query($data);
    }

    public function getNewAccessToken($code)
    {
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'api-key' => $this->apiKey,
            'redirect_uri' => $this->redirectUri
        );

        return $this->requestAccessToken($data);
    }

    public function refreshAccessToken()
    {
        $data = array(
            'grant_type' => 'refresh_token',
            'api-key' => $this->apiKey,
            'refresh_token' => $this->refreshToken,
            'redirect_uri' => $this->redirectUri
        );

        return $this->requestAccessToken($data);
    }

    private function requestAccessToken($data)
    {
        $authorization = base64_encode($this->clientId . ':' . $this->clientSecret);

        $headers = array(
            "Authorization: Basic $authorization",
            "Content-Type: application/x-www-form-urlencoded"
        );

        $data = http_build_query($data);

        return $this->sendHttpRequest(static::TOKEN_URI, 'POST', $headers, $data);
    }

    protected $clientId;

    protected $clientSecret;

    protected $apiKey;

    protected $redirectUri;

    protected $accessToken;

    protected $refreshToken;
}
