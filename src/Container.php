<?php
namespace TurkishStemmer;

class Container implements ContainerInterface
{
    protected $container;

    public function get($name)
    {
        if ($this->has($name)) {
            return $this->container[$name];
        }

        return null;
    }

    public function set($name, $object)
    {
        $this->container[$name] = $object;
    }

    public function has($name)
    {
        return isset($this->container[$name]);
    }
}
