<?php

namespace AppBundle\Service;


class PriceService implements PriceServiceInterface
{

    /** @var \AppBundle\Repository\PriceRepositoryInterface  */
    private $priceRepository;

    /** @var \AppBundle\Repository\ProductRepositoryInterface */
    private $productRepository;

    /** @var \AppBundle\Repository\CurrencyZoneRepositoryInterface */
    private $currencyZoneRepository;
    /**
     * ProductService constructor.
     */
    public function __construct(
        \AppBundle\Repository\PriceRepositoryInterface $priceRepository,
        \AppBundle\Repository\ProductRepositoryInterface $productRepository,
        \AppBundle\Repository\CurrencyZoneRepositoryInterface $currencyZoneRepository
    )
    {
        $this->priceRepository = $priceRepository;
        $this->productRepository = $productRepository;
        $this->currencyZoneRepository = $currencyZoneRepository;
    }

    public function createPrice(int $productId, int $zoneId, \AppBundle\ValueObject\Price $priceVo) : \AppBundle\Dto\Price
    {
        $productDto = $this->productRepository->getProductById($productId);

        if( null === $productDto) {
            throw new \Exception('Product does not exist', 400);
        }

        $currencyZoneDto = $this->currencyZoneRepository->getCurrencyZoneById($zoneId);
        if( null == $currencyZoneDto) {
            throw new \Exception('Currency Zone does not exist', 400);
        }

        return $this->priceRepository->createPrice($productId, $zoneId, $priceVo);
    }

}