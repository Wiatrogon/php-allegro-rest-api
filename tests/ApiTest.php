<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/Resource.php';
require_once dirname(__DIR__) . '/src/Api.php';

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{

    function testGetUri()
    {
        $api = new Allegro\REST\Api('eggs', 'spam', 'ham', 'beans');

        $this->assertEquals('https://allegroapi.io', $api->getUri());

        $this->assertEquals('https://allegroapi.io/categories',
                            $api->categories->getUri());

        $this->assertEquals('https://allegroapi.io/categories/12',
                            $api->categories(12)->getUri());
    }

    /**
     * @dataProvider credentialsProvider
     */
    function testAuthorization($clientId, $clientSecret, $apiKey,
                               $redirectUri, $accessToken, $refreshToken)
    {
        $api = new Allegro\REST\Api($clientId, $clientSecret, $apiKey,
                                    $redirectUri, $accessToken, $refreshToken);

        $expected = 'https://ssl.allegro.pl/auth/oauth/authorize' .
                    "?response_type=code&client_id=$clientId" .
                    "&api-key=$apiKey&redirect_uri=$redirectUri";

        $this->assertEquals($expected, $api->getAuthorizationUri());

        $this->assertEquals($accessToken, $api->getAccessToken());
        $this->assertEquals($accessToken, $api->categories->getAccessToken());
        $this->assertEquals($accessToken, $api->categories(123)->getAccessToken());

        $this->assertEquals($apiKey, $api->getApiKey());
        $this->assertEquals($apiKey, $api->categories->getApiKey());
        $this->assertEquals($apiKey, $api->categories(123)->getApiKey());
    }

    function credentialsProvider()
    {
        return array(
            array('eggs', 'spam', 'ham', 'beans', null, null),
            array('wood', 'stone', 'clay', 'wool', 'wheat', 'depleted uranium'),
            array('white', 'blue', 'black', 'red', 'green', 'colorless')
        );
    }
}
