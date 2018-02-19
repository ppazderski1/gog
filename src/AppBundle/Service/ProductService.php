<?php

namespace AppBundle\Service;

use AppBundle\ValueObject\Collection;
use \AppBundle\ValueObject\ProductSearch as ProductSearchVo;
use \AppBundle\ValueObject\Product as ProductVo;

/**
 * Class ProductService
 * @package AppBundle\Service
 */
class ProductService implements ProductServiceInterface
{

    const MIN_ACTIVE_PRODUCTS = 5;

    /** @var \AppBundle\Repository\ProductRepositoryInterface  */
    private $productRepository;

    /**
     * ProductService constructor.
     */
    public function __construct(\AppBundle\Repository\ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductVo $productVo
     * @return \AppBundle\Dto\Product
     */
    public function createProduct(ProductVo $productVo) : \AppBundle\Dto\Product
    {
        return $this->productRepository->createProduct($productVo);
    }

    /**
     * @param ProductVo $productVo
     * @throws \Exception
     */
    public function updateProduct(ProductVo $productVo) : void
    {
        $productDto = $this->productRepository->getProductById($productVo->id);

        if( null === $productDto) {
            throw new \Exception('Product does not exist', 404);
        }

        $this->productRepository->updateProduct($productVo);

        return;
    }

    /**
     * @param int $productId
     * @throws \Exception
     */
    public function deleteProduct(int $productId) : void
    {
        if( self::MIN_ACTIVE_PRODUCTS >= $this->productRepository->countActiveProducts() )
        {
            throw new \Exception('There must be at least '. self::MIN_ACTIVE_PRODUCTS . ' products in system', 400);
        }

        $productDto = $this->productRepository->getProductById($productId);


        if( null === $productDto) {
            throw new \Exception('Product does not exist', 404);
        }

        if( true === $productDto->isProtected) {
            throw new \Exception('Product is protected from deletion', 400);
        }

        $this->productRepository->deleteProduct($productId);

        return;
    }

    /**
     * @param ProductSearchVo $productSearch
     * @return Collection
     */
    public function listProducts(ProductSearchVo $productSearch) : Collection
    {
        $collection = [];

        $collectionCount = $this->productRepository->countProducts($productSearch);

        if( 0 !== $collectionCount ) {
            $collection = $this->productRepository->listProducts($productSearch);
        }

        return new Collection($collectionCount, $collection);
    }
}