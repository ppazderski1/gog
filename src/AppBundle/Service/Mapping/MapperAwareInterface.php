<?php

namespace AppBundle\Service\Mapping;


interface MapperAwareInterface
{
    public function setMapper(\NSM\Mapper\Mapper $mapper);
}