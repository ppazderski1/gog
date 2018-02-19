<?php

namespace AppBundle\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use /** @noinspection PhpUnusedAliasInspection */
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use /** @noinspection PhpUnusedAliasInspection */
    Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CurrencyZoneController extends AbstractController
{
    /**
     * Add new currency zone
     *
     * Returns:
     *
     *       {
     *          "id": <int>,
     *          "name": <string>,
     *          "currency": <string>,
     *          "locale": <string>
     *       }
     *
     * @ApiDoc(
     *     description="Method to add new currency zones to the system",
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
     *              "description"="Unique name of currency zone in system",
     *              "required"=true
     *          },
     *          {
     *              "name"="currency",
     *              "dataType"="string",
     *              "description"="ISO 4217",
     *              "required"=true
     *          },
     *          {
     *              "name"="locale",
     *              "dataType"="string",
     *              "description"="Locale format string, eg. `pl_PL`",
     *              "required"=true
     *          }
     *     },
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Bad Request",
     *          401="Unauthorized Request",
     *     }
     * )
     *
     *
     * @Method("POST")
     * @Route("/api/zone", name="addCurrencyZone")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addCurrencyZoneAction(Request $request)
    {
        /** @var \AppBundle\Dto\CurrencyZone $currencyZoneDto */
        $currencyZoneDto = $this->getMapperService()->convert($request, \AppBundle\Dto\CurrencyZone::class);

        $currencyZoneVo = \AppBundle\ValueObject\CurrencyZone::createFromDto($currencyZoneDto);

        $newCurrencyZoneDto = $this->getCurrencyZoneService()->createCurrencyZone($currencyZoneVo);

        return $this->response(200, $newCurrencyZoneDto);
    }

    /**
     * @return \AppBundle\Service\CurrencyZoneService
     */
    private function getCurrencyZoneService()
    {
        return $this->get('currency_zone_service');
    }

}