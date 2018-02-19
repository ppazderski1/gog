<?php

namespace AppBundle\Repository;

use \AppBundle\ValueObject\ProductSearch as ProductSearchVo;
use \AppBundle\ValueObject\Product as ProductVo;

/**
 * Interface ProductRepositoryInterface
 * @package AppBundle\Repository
 */
interface ProductRepositoryInterface
{
    /**
     * @param ProductVo $productVo
     * @return \AppBundle\Dto\Product
     */
    public function createProduct(ProductVo $productVo) : \AppBundle\Dto\Product;

    /**
     * @param ProductVo $productVo
     */
    public function updateProduct(ProductVo $productVo) : void;

    /**
     * @param int $productId
     */
    public function deleteProduct(int $productId) : void;

    /**
     * @param int $productId
     */
    public function decreesProductStock(int $productId) : void;

    /**
     * @param int $productId
     * @return \AppBundle\Dto\Product|null
     */
    public function getProductById(int $productId) : ?\AppBundle\Dto\Product;

    /**
     * @param int $productId
     * @param int $currencyZoneId
     * @return \AppBundle\Dto\Product|null
     */
    public function getProductInCurrencyZone(int $productId, int $currencyZoneId) : ?\AppBundle\Dto\Product;

    /**
     * @param ProductSearchVo $productSearch
     * @return array
     */
    public function listProducts(ProductSearchVo $productSearch) : array;

    /**
     * @param ProductSearchVo $productSearch
     * @return int
     */
    public function countProducts(ProductSearchVo $productSearch) : int;

    /**
     * @return int
     */
    public function countActiveProducts() : int;

}