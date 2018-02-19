<?php

namespace AppBundle\Controller;

use AppBundle\ValueObject\Collection;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use /** @noinspection PhpUnusedAliasInspection */
    Nelmio\ApiDocBundle\Annotation\ApiDoc;
use \AppBundle\ValueObject\ProductSearch as ProductSearchVo;
use \AppBundle\ValueObject\CurrencyZone as CurrencyZoneVo;
use \AppBundle\ValueObject\Product as ProductVo;
use \AppBundle\ValueObject\Price as PriceVo;

class ProductController extends AbstractController
{
    /**
     * Insert product to the system
     *
     * Returns:
     *
     *       {
     *          "id": <int>,
     *          "name": <string>,
     *          "stock_size": <int>,
     *          "is_protected": <bool>
     *       }
     *
     * @ApiDoc(
     *     description="Method to add new products to the system",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer admin`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {
     *              "name"="name",
     *              "dataType"="string",
     *              "description"="Unique name of product in system",
     *              "required"=true
     *          },
     *          {
     *              "name"="stock_size",
     *              "dataType"="int",
     *              "description"="Quantity of product in system",
     *              "required"=false
     *          },
     *     },
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     * @Method("POST")
     * @Route("/api/product", name="addProduct")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addProductAction(Request $request)
    {
        $productVo = new ProductVo(null, $request->get('name'), $request->get('stock_size', null));

        $newProductDto = $this->getProductService()->createProduct($productVo);

        return $this->response(200, $newProductDto);
    }

    /**
     * @ApiDoc(
     *     description="Method to update products in the system",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer admin`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {
     *              "name"="name",
     *              "dataType"="string",
     *              "description"="Unique name of product in system",
     *              "required"=true
     *          },
     *          {
     *              "name"="stock_size",
     *              "dataType"="int",
     *              "description"="Quantity of product in system",
     *              "required"=false
     *          },
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
     * @Route("/api/product/{productId}", name="updateProduct")
     * @param int $productId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function updateProductAction(int $productId, Request $request)
    {
        $productVo = new ProductVo($productId, $request->get('name'), $request->get('stock_size', null));

        $this->getProductService()->updateProduct($productVo);

        return $this->response(204, null);
    }

    /**
     * @ApiDoc(
     *     description="Method to delete products from the system",
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
     * @Method("DELETE")
     * @Route("/api/product/{productId}", name="deleteProduct")
     * @param int $productId
     * @return void
     * @throws \Exception
     */
    public function deleteProductAction(int $productId)
    {
        $this->getProductService()->deleteProduct($productId);

        return $this->response(204, null);
    }

    /**
     * Get list of products
     *
     * Returns:
     *
     *      X-Count: <int>
     *      X-Pagination-Limit: <int>
     *      X-Pagination-Page: <int>
     *
     *
     *     [
     *       {
     *          "id": <int>,
     *          "name": <string>,
     *          "stock_size": <int>,
     *          "is_protected": <bool>,
     *          "price" : {
     *              "amount": <int>,
     *              "code": <string>,
     *              "formatted": <string>
     *          }
     *       },
     *       ...
     *     ]
     *
     * @ApiDoc(
     *     description="Method to list products from system",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer client1`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {
     *              "name"="name",
     *              "dataType"="string",
     *              "description"="Name property search value",
     *              "required"=false
     *          },
     *          {
     *              "name"="page",
     *              "dataType"="int",
     *              "description"="Pagination page",
     *              "required"=false
     *          },
     *          {
     *              "name"="limit",
     *              "dataType"="int",
     *              "description"="quantity of items on page",
     *              "required"=false
     *          },
     *          {
     *              "name"="sorts",
     *              "dataType"="array",
     *              "description"="Array of sorts, eg. `name_ASC`",
     *              "required"=false
     *          },
     *     },
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     * @Method("GET")
     * @Route("/api/product", name="getProducts")
     */
    public function getProductsAction(Request $request)
    {
        $currentUserDto = $this->getUserDto();

        $productSearchVo = new ProductSearchVo(
            $request->get('name', null),
            CurrencyZoneVo::createFromDto($currentUserDto->currencyZone),
            $request->get('page', 1),
            $request->get('limit', 3),
            $request->get('sorts', [])
        );
        /** @var Collection $productsCollection */
        $productsCollection = $this->getProductService()->listProducts($productSearchVo);

        return $this->responseLimited(
            200,
            $productsCollection->collection,
            $productsCollection->total,
            $productSearchVo->limit,
            $productSearchVo->page
        );
    }

    /**
     * Add new product price
     *
     * Returns:
     *
     *       {
     *          "id": <int>,
     *          "value": <int>,
     *          "valid_from": <string>,
     *          "valid_from": <string>,
     *          "currency_zone": {
     *              "id": <int>,
     *              "name": <string>,
     *              "currency": <string>,
     *              "locale": <string>
     *          }
     *       }
     *
     * @ApiDoc(
     *     description="Method to add new products to the system",
     *     headers={
     *          {
     *              "name"="Authorization",
     *              "description"="Authorization token, eg. `Bearer admin`",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {
     *              "name"="value",
     *              "dataType"="int",
     *              "description"="Int value of the product in smallest part of currency",
     *              "required"=true
     *          },
     *          {
     *              "name"="valid_from",
     *              "dataType"="string",
     *              "description"="ISO 8601 format datetime, eg. 2018-12-20T23:30:00Z",
     *              "required"=false
     *          },
     *          {
     *              "name"="valid_to",
     *              "dataType"="string",
     *              "description"="ISO 8601 format datetime, eg. 2018-12-20T23:30:00Z",
     *              "required"=false
     *          },
     *     },
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     * @Method("POST")
     * @Route("/api/product/{productId}/zone/{zoneId}/price", name="addProductPrice")
     * @param int $productId
     * @param int $zoneId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addProductPrice(int $productId, int $zoneId, Request $request)
    {
        $priceVo = new PriceVo(
            null,
            $request->get('value'),
            $request->get('valid_from', null),
            $request->get('valid_to', null)
        );

        $newPriceDto = $this->getPriceService()->createPrice($productId, $zoneId, $priceVo);

        return $this->response(200, $newPriceDto);
    }

    /**
     * @return \AppBundle\Service\ProductService
     */
    private function getProductService()
    {
        return  $this->get('product_service');
    }

    /**
     * @return \AppBundle\Service\PriceService
     */
    private function getPriceService()
    {
        return  $this->get('price_service');
    }
}