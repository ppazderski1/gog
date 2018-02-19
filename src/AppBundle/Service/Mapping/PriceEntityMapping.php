<?php

namespace AppBundle\Service\Mapping;


use AppBundle\Dto\CurrencyZone;
use NSM\Mapper\PropertyAccess\ClosureGetter;

class PriceEntityMapping implements \NSM\Mapper\MappingBuilderInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    public function build(\NSM\Mapper\Mapping $mapping): void
    {
        $mapping->forProperty('id', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            return $price->getId();
        }));

        $mapping->forProperty('value', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            return $price->getValue();
        }));

        $mapping->forProperty('validFrom', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            return $price->getValidFrom();
        }));

        $mapping->forProperty('validTo', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            return $price->getValidTo();
        }));

        $mapping->forProperty('currencyZone', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            return $this->mapper->convert($price->getCurrencyZone(), CurrencyZone::class);
        }));

    }

    public function getMappingDirections(): array
    {
        return [
            \AppBundle\Entity\Price::class => \AppBundle\Dto\Price::class
        ];
    }
}