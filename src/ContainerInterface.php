<?php
namespace TurkishStemmer;

interface ContainerInterface
{
    public function has($name);
    public function get($name);
    public function set($name, $object);
}
