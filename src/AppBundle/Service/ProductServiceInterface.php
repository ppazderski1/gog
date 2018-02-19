<?php

namespace AppBundle\Service;

use AppBundle\ValueObject\Collection;
use \AppBundle\ValueObject\ProductSearch as ProductSearchVo;
use \AppBundle\ValueObject\Product as ProductVo;

/**
 * Interface ProductServiceInterface
 * @package AppBundle\Service
 */
interface ProductServiceInterface
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
     * @param ProductSearchVo $productSearch
     * @return Collection
     */
    public function listProducts(ProductSearchVo $productSearch) : Collection;
}