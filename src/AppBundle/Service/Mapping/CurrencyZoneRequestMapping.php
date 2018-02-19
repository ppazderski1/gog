<?php

namespace AppBundle\Service\Mapping;


class CurrencyZoneRequestMapping implements \NSM\Mapper\MappingBuilderInterface, MapperAwareInterface
{
    use MapperAwareTrait;
    use RequestMappingTrait;

    public function build(\NSM\Mapper\Mapping $mapping): void
    {
        $this->buildForRequest($mapping, 'id');
        $this->buildForRequest($mapping, 'name');
        $this->buildForRequest($mapping, 'currency');
        $this->buildForRequest($mapping, 'locale');
    }

    public function getMappingDirections(): array
    {
        return [
            \Symfony\Component\HttpFoundation\Request::class => \AppBundle\Dto\CurrencyZone::class
        ];
    }
}