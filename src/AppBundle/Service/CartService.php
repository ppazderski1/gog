<?php

namespace AppBundle\Service;

use \AppBundle\ValueObject\CartSearch as CartSearchVo;
use \AppBundle\ValueObject\Cart as CartVo;

/**
 * Class CartService
 * @package AppBundle\Service
 */
class CartService implements CartServiceInterface
{
    const MAX_CART_QUANTITY = 3;

    /** @var \AppBundle\Repository\CartRepositoryInterface  */
    private $cartRepository;

    /** @var \AppBundle\Repository\ProductRepositoryInterface  */
    private $productRepository;

    /**
     * ProductService constructor.
     */
    public function __construct(
        \AppBundle\Repository\CartRepositoryInterface $cartRepository,
        \AppBundle\Repository\ProductRepositoryInterface $productRepository
    )
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param CartSearchVo $cartSearchVo
     * @return \AppBundle\Dto\Cart
     * @throws \Exception
     */
    public function createCart(CartSearchVo $cartSearchVo) : \AppBundle\Dto\Cart
    {
        $userCart = $this->cartRepository->getCart($cartSearchVo);

        if( null !== $userCart ) {
            throw new \Exception('This user has an active cart', 400);
        }

        return $this->cartRepository->createCart($cartSearchVo->userId);
    }

    /**
     * @param CartSearchVo $cartSearchVo
     * @return \AppBundle\Dto\Cart
     * @throws \Exception
     */
    public function getCart(CartSearchVo $cartSearchVo) : \AppBundle\Dto\Cart
    {
        $cart = $this->cartRepository->getCart($cartSearchVo);

        if (null === $cart) {
            throw new \Exception('Cart does not exist', 404);
        }

        return $cart;
    }

    /**
     * @param CartSearchVo $cartSearchVo
     * @return void
     * @throws \Exception
     */
    public function deleteCart(CartSearchVo $cartSearchVo) : void
    {
        $cart = $this->cartRepository->getCart($cartSearchVo);

        if (null === $cart) {
            throw new \Exception('Cart does not exist', 404);
        }

        $this->cartRepository->deleteCart($cartSearchVo);

        return;
    }

    /**
     * @param CartSearchVo $cartSearchVo
     * @param CartVo $cartVo
     * @return void
     * @throws \Exception
     */
    public function updateCart(CartSearchVo $cartSearchVo, CartVo $cartVo) : void
    {
        $cart = $this->cartRepository->getCart($cartSearchVo);

        if (null === $cart) {
            throw new \Exception('Cart does not exist', 404);
        }

        if($cartVo->submitted) {
            $this->cartRepository->submitCart($cartSearchVo);
        }

        return;
    }

    /**
     * @param CartSearchVo $cartSearchVo
     * @param int $productId
     * @throws \Exception
     */
    public function addCartProduct(CartSearchVo $cartSearchVo, int  $productId) : void
    {
        $userCart = $this->cartRepository->getCart($cartSearchVo);

        if( null === $userCart ) {
            throw new \Exception('Cart does not exist', 404);
        }

        foreach ($userCart->products as $product) {
            if( $product->id === $productId) {
                throw new \Exception('Product is already in cart', 400);
            }
        }

        if(count($userCart->products) === self::MAX_CART_QUANTITY) {
            throw new \Exception('You cant add more then ' . self::MAX_CART_QUANTITY . ' products to your cart', 400);
        }

        $product = $this->productRepository->getProductInCurrencyZone($productId, $cartSearchVo->currencyZone->id);

        if (null === $product) {
            throw new \Exception('Product does not exist', 404);
        }

        $this->cartRepository->addProduct($cartSearchVo->id, $productId);

        return;
    }

    /**
     * @param CartSearchVo $cartSearchVo
     * @param int $productId
     * @throws \Exception
     */
    public function removeCartProduct(CartSearchVo $cartSearchVo, int  $productId) : void
    {
        $userCart = $this->cartRepository->getCart($cartSearchVo);

        if( null === $userCart ) {
            throw new \Exception('Cart does not exist', 404);
        }

        $this->cartRepository->removeProduct($cartSearchVo->id, $productId);

        return;
    }

}