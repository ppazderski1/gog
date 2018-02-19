<?php

namespace AppBundle\Service;


/**
 * Interface CurrencyZoneServiceInterface
 * @package AppBundle\Service
 */
interface CurrencyZoneServiceInterface
{
    /**
     * @param \AppBundle\ValueObject\CurrencyZone $currencyZoneVo
     * @return \AppBundle\Dto\CurrencyZone
     */
    public function createCurrencyZone(\AppBundle\ValueObject\CurrencyZone $currencyZoneVo) : \AppBundle\Dto\CurrencyZone;
}