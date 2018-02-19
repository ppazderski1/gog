<?php

namespace AppBundle\Repository;


/**
 * Interface CartRepositoryInterface
 * @package AppBundle\Repository
 */
interface CartRepositoryInterface
{
    /**
     * @param int $userId
     * @return \AppBundle\Dto\Cart|null
     */
    public function createCart(int $userId) : ?\AppBundle\Dto\Cart;

    /**
     * @param \AppBundle\ValueObject\CartSearch $cartSearchVo
     */
    public function deleteCart(\AppBundle\ValueObject\CartSearch $cartSearchVo) : void;

    /**
     * @param \AppBundle\ValueObject\CartSearch $cartSearchVo
     * @return \AppBundle\Dto\Cart|null
     */
    public function getCart(\AppBundle\ValueObject\CartSearch $cartSearchVo) : ?\AppBundle\Dto\Cart;

    /**
     * @param $cartId
     * @param $productId
     */
    public function addProduct($cartId, $productId) : void;

    /**
     * @param \AppBundle\ValueObject\CartSearch $cartSearchVo
     */
    public function submitCart(\AppBundle\ValueObject\CartSearch $cartSearchVo) : void;

    /**
     * @param $cartId
     * @param $productId
     */
    public function removeProduct($cartId, $productId) : void;
}