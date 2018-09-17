<?php
namespace Allegro\REST;

class Api extends Resource
{

    const API_URI = 'https://api.allegro.pl';

    const TOKEN_URI = 'https://allegro.pl/auth/oauth/token';

    const AUTHORIZATION_URI = 'https://allegro.pl/auth/oauth/authorize';

    /**
     * Api constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $apiKey
     * @param string $redirectUri
     * @param null|string $accessToken
     * @param null|string $refreshToken
     */
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

    /**
     * @return string
     */
    public function getUri()
    {
        return static::API_URI;
    }

    /**
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
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

    /**
     * @param string $code
     * @return object
     */
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

    /**
     * @return object
     */
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

    /**
     * @param array $data
     * @return object
     */
    private function requestAccessToken($data)
    {
        $authorization = base64_encode($this->clientId . ':' . $this->clientSecret);

        $headers = array(
            "Authorization: Basic $authorization",
            "Content-Type: application/x-www-form-urlencoded"
        );

        $data = http_build_query($data);

        $response = $this->sendHttpRequest(static::TOKEN_URI, 'POST', $headers, $data);

        $data = json_decode($response);

        if (isset($data->access_token) && isset($data->refresh_token))
        {
            $this->accessToken = $data->access_token;
            $this->refreshToken = $data->refresh_token;
        }

        return $response;
    }

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $refreshToken;
}
