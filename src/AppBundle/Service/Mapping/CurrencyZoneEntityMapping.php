<?php

namespace AppBundle\Service\Mapping;


use NSM\Mapper\PropertyAccess\ClosureGetter;

class CurrencyZoneEntityMapping implements \NSM\Mapper\MappingBuilderInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    public function build(\NSM\Mapper\Mapping $mapping): void
    {
        $mapping->forProperty('id', new ClosureGetter(function (\AppBundle\Entity\CurrencyZone $currencyZone) {
            return $currencyZone->getId();
        }));

        $mapping->forProperty('name', new ClosureGetter(function (\AppBundle\Entity\CurrencyZone $currencyZone) {
            return $currencyZone->getName();
        }));

        $mapping->forProperty('locale', new ClosureGetter(function (\AppBundle\Entity\CurrencyZone $currencyZone) {
            return $currencyZone->getLocale();
        }));

        $mapping->forProperty('currency', new ClosureGetter(function (\AppBundle\Entity\CurrencyZone $currencyZone) {
            return $currencyZone->getCurrency();
        }));
    }

    public function getMappingDirections(): array
    {
        return [
            \AppBundle\Entity\CurrencyZone::class => \AppBundle\Dto\CurrencyZone::class
        ];
    }
}