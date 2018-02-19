<?php

namespace AppBundle\Service\Mapping;

trait MapperAwareTrait
{
    /** @var  \NSM\Mapper\Mapper */
    protected $mapper;

    public function setMapper(\NSM\Mapper\Mapper $mapper)
    {
        $this->mapper = $mapper;
    }
}