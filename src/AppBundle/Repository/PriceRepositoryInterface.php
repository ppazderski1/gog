<?php

namespace AppBundle\Repository;


/**
 * Interface PriceRepositoryInterface
 * @package AppBundle\Repository
 */
interface PriceRepositoryInterface
{
    /**
     * @param int $productId
     * @param int $zoneId
     * @param \AppBundle\ValueObject\Price $priceVo
     * @return \AppBundle\Dto\Price
     */
    public function createPrice(int $productId, int $zoneId, \AppBundle\ValueObject\Price $priceVo) : \AppBundle\Dto\Price;
}