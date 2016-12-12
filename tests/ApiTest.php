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

        $this->assertEquals('https://allegroapi.io/',
                            $api->getUri());

        $this->assertEquals('https://allegroapi.io/categories/',
                            $api->categories->getUri());

        $this->assertEquals('https://allegroapi.io/categories/12/',
                            $api->categories(12)->getUri());
    }

    function testGetAutorizationUri()
    {
        $api = new Allegro\REST\Api('eggs', 'spam', 'ham', 'beans');

        $this->assertEquals('https://ssl.allegro.pl/auth/oauth/authorize' .
                            '?response_type=code&client_id=eggs&api-key=ham&redirect_uri=beans',
                            $api->getAuthorizationUri());
    }
}
