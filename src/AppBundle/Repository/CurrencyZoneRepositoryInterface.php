<?php

namespace AppBundle\Repository;


/**
 * Interface CurrencyZoneRepositoryInterface
 * @package AppBundle\Repository
 */
interface CurrencyZoneRepositoryInterface
{
    /**
     * @param \AppBundle\ValueObject\CurrencyZone $currencyZoneDto
     * @return \AppBundle\Dto\CurrencyZone
     */
    public function createCurrencyZone(\AppBundle\ValueObject\CurrencyZone $currencyZoneDto) : \AppBundle\Dto\CurrencyZone;

    /**
     * @param int $currencyZoneId
     * @return \AppBundle\Dto\CurrencyZone|null
     */
    public function getCurrencyZoneById(int $currencyZoneId) : ?\AppBundle\Dto\CurrencyZone;

    /**
     * @param $currency
     * @param $locale
     * @return \AppBundle\Dto\CurrencyZone|null
     */
    public function getCurrencyZoneByCodeAndLocale($currency, $locale) : ?\AppBundle\Dto\CurrencyZone;

    /**
     * @param $name
     * @return \AppBundle\Dto\CurrencyZone|null
     */
    public function getCurrencyByName($name) : ?\AppBundle\Dto\CurrencyZone;
}