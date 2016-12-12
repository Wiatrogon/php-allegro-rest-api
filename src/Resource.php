<?php
namespace Allegro\REST;

class Resource
{

    public function __construct($id, Resource $parent)
    {
        $this->id = $id;
        $this->parent = $parent;
    }

    public function getAccessToken()
    {
        return $this->parent->getAccessToken();
    }

    public function getApiKey()
    {
        return $this->parent->getApiKey();
    }

    public function getUri()
    {
        return $this->parent->getUri() . $this->id . '/';
    }

    public function commands()
    {
        return new Commands($this);
    }

    public function get($data = null)
    {
        $uri = $this->getUri();

        if ($data !== null) {
            $uri .= '?';
            $uri .= http_build_query($data);
        }

        return $this->sendApiRequest($uri, 'GET');
    }

    public function put($data)
    {
        return $this->sendApiRequest($this->getUri(), 'PUT', $data);
    }

    public function post($data)
    {
        return $this->sendApiRequest($this->getUri(), 'POST', $data);
    }

    public function delete($data = null)
    {
        $uri = $this->getUri();

        if ($data !== null) {
            $uri .= '?';
            $uri .= http_build_query($data);
        }

        return $this->sendApiRequest($uri, 'DELETE');
    }

    public function __get($name)
    {
        return new Resource($name, $this);
    }

    public function __call($name, $args)
    {
        $id = array_shift($args);
        $collection = new Resource($name, $this);
        return new Resource($id, $collection);
    }

    protected function sendApiRequest($url, $method, $data = array())
    {
        $token = $this->getAccessToken();
        $key = $this->getApiKey();

        $headers = array(
            "Authorization: Bearer $token",
            "Api-Key: $key",
            "Content-Type: application/vnd.allegro.public.v1+json",
            "Accept: application/vnd.allegro.public.v1+json"
        );

        $data = json_encode($data);

        return $this->sendHttpRequest($url, $method, $headers, $data);
    }

    protected function sendHttpRequest($url, $method, $headers = array(), $data = '')
    {
        $options = array(
            'http' => array(
                'method' => $method,
                'header' => implode("\r\n", $headers),
                'content' => $data,
                'ignore_errors' => true
            )
        );

        $context = stream_context_create($options);

        return file_get_contents($url, false, $context);
    }

    private $id;

    private $parent;
}
