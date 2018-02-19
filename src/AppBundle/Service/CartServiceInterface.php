<?php

namespace AppBundle\Service;

use \AppBundle\ValueObject\CartSearch as CartSearchVo;
use \AppBundle\ValueObject\Cart as CartVo;


/**
 * Interface CartServiceInterface
 * @package AppBundle\Service
 */
interface CartServiceInterface
{
    /**
     * @param CartSearchVo $cartSearchVo
     * @return \AppBundle\Dto\Cart
     */
    public function createCart(CartSearchVo $cartSearchVo) : \AppBundle\Dto\Cart;

    /**
     * @param CartSearchVo $cartSearchVo
     * @return \AppBundle\Dto\Cart
     */
    public function getCart(CartSearchVo $cartSearchVo) : \AppBundle\Dto\Cart;

    /**
     * @param CartSearchVo $cartSearchVo
     */
    public function deleteCart(CartSearchVo $cartSearchVo) : void;

    /**
     * @param CartSearchVo $cartSearchVo
     * @param CartVo $cartVo
     */
    public function updateCart(CartSearchVo $cartSearchVo, CartVo $cartVo) : void;

    /**
     * @param CartSearchVo $cartSearchVo
     * @param int $productId
     */
    public function addCartProduct(CartSearchVo $cartSearchVo, int  $productId) : void;

    /**
     * @param CartSearchVo $cartSearchVo
     * @param int $productId
     */
    public function removeCartProduct(CartSearchVo $cartSearchVo, int  $productId) : void;
}