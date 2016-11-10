<?php
class Commands
{
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

    private function getUuid ()
    {
        return '9b84e1bc-5341-45e7-837e-4250720e606f';
    }

    private $resource;
}
