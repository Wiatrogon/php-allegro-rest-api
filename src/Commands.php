<?php
namespace Allegro\REST;

class Commands
{
    /**
     * Commands constructor.
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function __call($name, $args)
    {
        $data = array_shift($args);
        $name = str_replace('_', '-', $name) . '-commands';
        $type = new Resource($name, $this->resource);
        $command = new Resource($this->getUuid(), $type);

        return $command->put($data);
    }

    /**
     * @return string
     */
    private function getUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * @var Resource
     */
    private $resource;
}
