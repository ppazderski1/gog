<?php

namespace AppBundle\Service;


/**
 * Interface PriceServiceInterface
 * @package AppBundle\Service
 */
interface PriceServiceInterface
{
    /**
     * @param int $productId
     * @param int $zoneId
     * @param \AppBundle\ValueObject\Price $priceVo
     * @return \AppBundle\Dto\Price
     */
    public function createPrice(int $productId, int $zoneId, \AppBundle\ValueObject\Price $priceVo) : \AppBundle\Dto\Price;
}