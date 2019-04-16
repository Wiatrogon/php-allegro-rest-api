<?php

namespace Allegro\REST;

class Resource
{

    protected $_apiResource;

    /**
     * Resource constructor.
     * @param string $id
     * @param Resource $parent
     */
    public function __construct($id, Resource $parent)
    {
        $this->id = $id;
        $this->parent = $parent;
    }

    protected function apiResourse()
    {
        if (empty($this->_apiResource)) {
            $apiResourse = (json_decode(file_get_contents(__DIR__ . '/swagger.json'), true))['paths'];
            array_walk(
                $apiResourse,
                function (&$path, $index) {
                    $path = array_shift(array_keys(($get = array_shift((array_shift($path))['responses']))['content']));
                }
            );
            $res = array();
            foreach ($apiResourse as $key => $value) {
                preg_match_all('/(\/[A-Z-a-z]*)+/m', $key, $match);
                $res[] = array('value' => $value, 'path' => implode('', array_shift($match)), 'pattern' => $key);
            }
            $this->_apiResource = $res;
        }
        return $this->_apiResource;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->parent->getAccessToken();
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->parent->getApiKey();
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->parent->getUri() . '/' . $this->id;
    }

    /**
     * @return Commands
     */
    public function commands()
    {
        return new Commands($this);
    }

    /**
     * @param null|array $data
     * @return bool|string
     */
    public function get($data = null)
    {
        $uri = $this->getUri();
        $content = (array_shift(array_filter($this->apiResourse(), function ($item) use ($uri) {
            return strpos($uri, $item['path']);
        })))['value'];
        echo $content;
        if ($data !== null) {
            $uri .= '?';
            $uri .= http_build_query($data);
        }
        echo '<br>'.$uri;
        return $this->sendApiRequest($uri, 'GET', array(), $content);
    }

    /**
     * @param array $data
     * @return bool|string
     */
    public function put($data)
    {
        return $this->sendApiRequest($this->getUri(), 'PUT', $data);
    }

    /**
     * @param array $data
     * @return bool|string
     */
    public function post($data)
    {
        return $this->sendApiRequest($this->getUri(), 'POST', $data);
    }

    /**
     * @param null|array $data
     * @return bool|string
     */
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

    /**
     * @param string $url
     * @param string $method
     * @param array $data
     * @return bool|string
     */
    protected function sendApiRequest($url, $method, $data = array(), $content = 'application/vnd.allegro.public.v1+json')
    {
        $token = $this->getAccessToken();
        $key = $this->getApiKey();


        $headers = array(
            "Authorization: Bearer $token",
            "Api-Key: $key",
            "Content-Type: " . $content,
            "Accept: " . $content,
        );

        $data = json_encode($data);

        return $this->sendHttpRequest($url, $method, $headers, $data);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $headers
     * @param string $data
     * @return bool|string
     */
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

    /**
     * @var string
     */
    private $id;

    /**
     * @var Resource
     */
    private $parent;
}
