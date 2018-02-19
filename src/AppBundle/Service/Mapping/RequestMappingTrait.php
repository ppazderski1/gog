<?php

namespace AppBundle\Service\Mapping;

trait RequestMappingTrait
{
    protected function buildForRequest(\NSM\Mapper\Mapping $mapping, string $propertyName, string $requestPropertyName = null)
    {
        if ($requestPropertyName === null) {
            $requestPropertyName = $propertyName;
        }

        $mapping->forProperty($propertyName, new RequestGetter($requestPropertyName));
    }
}