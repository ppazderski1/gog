<?php

namespace AppBundle\Service\Mapping;

use AppBundle\Dto\PriceSimple;
use Doctrine\Common\Collections\Criteria;
use NSM\Mapper\PropertyAccess\ClosureGetter;

class ProductEntityMapping implements \NSM\Mapper\MappingBuilderInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    public function build(\NSM\Mapper\Mapping $mapping): void
    {

        $mapping->forProperty('id', new ClosureGetter(function (\AppBundle\Entity\Product $product) {
            return $product->getId();
        }));

        $mapping->forProperty('name', new ClosureGetter(function (\AppBundle\Entity\Product $product) {
            return $product->getName();
        }));

        $mapping->forProperty('stockSize', new ClosureGetter(function (\AppBundle\Entity\Product $product) {
            return $product->getStockSize();
        }));

        $mapping->forProperty('isProtected', new ClosureGetter(function (\AppBundle\Entity\Product $product) {
            return $product->getIsProtected();
        }));

        $mapping->forProperty('price', new ClosureGetter(function (\AppBundle\Entity\Product $product) {
            $prices = $product->getPrices()->matching(
                Criteria::create()->andWhere(
                    Criteria::expr()->eq('isActive', true)
                )
            );

            if( 0 === count($prices) ) {
                return null;
            }

            list($price) = $prices;

            return $this->mapper->convert($price, PriceSimple::class);

        }));
    }

    public function getMappingDirections(): array
    {
        return [
            \AppBundle\Entity\Product::class => \AppBundle\Dto\Product::class
        ];
    }
}