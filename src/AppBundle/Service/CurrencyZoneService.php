<?php

namespace AppBundle\Service;


/**
 * Class CurrencyZoneService
 * @package AppBundle\Service
 */
class CurrencyZoneService implements CurrencyZoneServiceInterface
{

    /** @var \AppBundle\Repository\CurrencyZoneRepository  */
    private $currencyZoneRepository;
    /**
     * ProductService constructor.
     */
    public function __construct(\AppBundle\Repository\CurrencyZoneRepositoryInterface $currencyZoneRepository)
    {
        $this->currencyZoneRepository = $currencyZoneRepository;
    }

    /**
     * @param \AppBundle\ValueObject\CurrencyZone $currencyZoneVo
     * @return \AppBundle\Dto\CurrencyZone
     * @throws \Exception
     */
    public function createCurrencyZone(\AppBundle\ValueObject\CurrencyZone $currencyZoneVo) : \AppBundle\Dto\CurrencyZone
    {
        $currencyZoneDto = $this->currencyZoneRepository->getCurrencyZoneByCodeAndLocale($currencyZoneVo->currency, $currencyZoneVo->locale);

        if ( null !== $currencyZoneDto) {
            throw new \Exception('Currency zone already exists', 400);
        }

        $currencyZoneDto = $this->currencyZoneRepository->getCurrencyByName($currencyZoneVo->name);

        if ( null !== $currencyZoneDto) {
            throw new \Exception('Currency zone with name '. $currencyZoneVo->name .' already exists', 400);
        }

        return $this->currencyZoneRepository->createCurrencyZone($currencyZoneVo);
    }

}