<?php

namespace AppBundle\Service\Mapping;

use NSM\Mapper\PropertyAccess\GetterInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestGetter implements GetterInterface
{
    private $paramName;

    public function __construct(string $paramName)
    {
        $this->paramName = $paramName;
    }

    public function getValue($source)
    {
        if (! $source instanceof Request) {
            throw new \Exception("Invalid source");
        }

        return $source->get($this->paramName);
    }
}