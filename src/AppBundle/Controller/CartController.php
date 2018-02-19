<?php

namespace AppBundle\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use /** @noinspection PhpUnusedAliasInspection */
    Nelmio\ApiDocBundle\Annotation\ApiDoc;
use \AppBundle\ValueObject\CartSearch as CartSearchVo;
use \AppBundle\ValueObject\Cart as CartVo;

class CartController extends AbstractController
{
    /**
     * Create new cart
     *
     * Returns:
     *
     *       {
     *          "id": <int>,
     *          "products": [],
     *          "price_total": {
     *              "amount": <int>,
     *              "code": <string>,
     *              "formatted": <string>
     *           },
     *          "created_at": <string>,
     *          "updated_at: <string>
     *       }
     *
     * @ApiDoc(
     *     description="Method to create new cart for logged user",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer client1`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *     },
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     * @Method("POST")
     * @Route("/api/cart", name="addCart")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addCartAction()
    {
        $currentUserDto = $this->getUserDto();

        $cartSearchVo = new CartSearchVo(
            null,
            $currentUserDto->id,
            \AppBundle\ValueObject\CurrencyZone::createFromDto($currentUserDto->currencyZone)
        );

        $cartDto = $this->getCartService()->createCart($cartSearchVo);

        return $this->response(200, $cartDto);
    }

    /**
     * Get users cart
     *
     * Returns:
     *
     *       {
     *          "id": <int>,
     *          "products": [
     *             {
     *               "id": <int>,
     *               "name": <string>,
     *               "stock_size": <int>,
     *               "is_protected": <bool>,
     *               "price" : {
     *                  "amount": <int>,
     *                  "code": <string>,
     *                  "formatted": <string>
     *               }
     *             },
     *             ...
     *           ],
     *          "price_total": {
     *              "amount": <int>,
     *              "code": <string>,
     *              "formatted": <string>
     *           },
     *          "created_at": <string>,
     *          "updated_at: <string>
     *       }
     *
     * @ApiDoc(
     *     description="Method to get cart by its id",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer client1`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *     },
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     * @Method("GET")
     * @Route("/api/cart/{cartId}", name="getCart")
     * @param int $cartId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getCartAction(int $cartId)
    {
        $currentUserDto = $this->getUserDto();

        $cartSearchVo = new CartSearchVo(
            $cartId,
            $currentUserDto->id,
            \AppBundle\ValueObject\CurrencyZone::createFromDto($currentUserDto->currencyZone)
        );

        $cartDto = $this->getCartService()->getCart($cartSearchVo);

        return $this->response(200, $cartDto);
    }

    /**
     * @ApiDoc(
     *     description="Method to delete cart",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer admin`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *     },
     *     statusCodes={
     *          204="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     *
     * @Method("DELETE")
     * @Route("/api/cart/{cartId}", name="deleteCart")
     * @param int $cartId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteCartAction(int $cartId)
    {
        $currentUserDto = $this->getUserDto();

        $cartSearchVo = new CartSearchVo(
            $cartId,
            $currentUserDto->id,
            \AppBundle\ValueObject\CurrencyZone::createFromDto($currentUserDto->currencyZone)
        );

        $this->getCartService()->deleteCart($cartSearchVo);

        return $this->response(204, null);
    }

    /**
     * @ApiDoc(
     *     description="Method to update (submit) a cart",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer client1`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {
     *              "name"="submitted",
     *              "dataType"="bool",
     *              "description"="Flag for subbmiting the cart",
     *              "required"=true
     *          }
     *     },
     *     statusCodes={
     *          204="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     *
     * @Method("PUT")
     * @Route("/api/cart/{cartId}", name="updateCart")
     * @param int $cartId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function updateCartAction(int $cartId, Request $request)
    {
        $currentUserDto = $this->getUserDto();

        $cartSearchVo = new CartSearchVo(
            $cartId,
            $currentUserDto->id,
            \AppBundle\ValueObject\CurrencyZone::createFromDto($currentUserDto->currencyZone)
        );

        $cartVo = new CartVo(
            $cartId,
            $request->get('submitted')
        );

        $this->getCartService()->updateCart($cartSearchVo, $cartVo);

        return $this->response(204, null);
    }

    /**
     * @ApiDoc(
     *     description="Method to add product into cart",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer client1`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *     },
     *     statusCodes={
     *          204="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     *
     * @Method("POST")
     * @Route("/api/cart/{cartId}/product/{productId}", name="addCartProduct")
     * @param int $cartId
     * @param int $productId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addCartProductAction(int $cartId, int $productId)
    {
        $currentUserDto = $this->getUserDto();

        $cartSearchVo = new CartSearchVo(
            $cartId,
            $currentUserDto->id,
            \AppBundle\ValueObject\CurrencyZone::createFromDto($currentUserDto->currencyZone)
        );

        $this->getCartService()->addCartProduct($cartSearchVo, $productId);

        return $this->response(204, null);
    }

    /**
     * @ApiDoc(
     *     description="Method to remove product from the cart",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer client1`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *     },
     *     statusCodes={
     *          204="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     *
     * @Method("DELETE")
     * @Route("/api/cart/{cartId}/product/{productId}", name="removeCartProduct")
     * @param int $cartId
     * @param int $productId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function removeCartProductAction(int $cartId, int $productId)
    {
        $currentUserDto = $this->getUserDto();

        $cartSearchVo = new CartSearchVo(
            $cartId,
            $currentUserDto->id,
            \AppBundle\ValueObject\CurrencyZone::createFromDto($currentUserDto->currencyZone)
        );

        $this->getCartService()->removeCartProduct($cartSearchVo, $productId);

        return $this->response(204, null);
    }


    /**
     * @return \AppBundle\Service\CartService
     */
    private function getCartService()
    {
        return $this->get('cart_service');
    }

}