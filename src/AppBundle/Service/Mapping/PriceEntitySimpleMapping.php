<?php

namespace AppBundle\Service\Mapping;

use NSM\Mapper\PropertyAccess\ClosureGetter;
use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class PriceEntitySimpleMapping implements \NSM\Mapper\MappingBuilderInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    public function build(\NSM\Mapper\Mapping $mapping): void
    {

        $mapping->forProperty('amount', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            return $price->getValue();
        }));


        $mapping->forProperty('code', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            $currencyZone = $price->getCurrencyZone();
            return $currencyZone->getCurrency();
        }));

        $mapping->forProperty('formatted', new ClosureGetter(function (\AppBundle\Entity\Price $price) {
            $currencyZone = $price->getCurrencyZone();

            $moneyObject = new Money($price->getValue(), new Currency($currencyZone->getCurrency()));

            $currencies = new ISOCurrencies();

            $numberFormatter = new \NumberFormatter($currencyZone->getLocale(), \NumberFormatter::CURRENCY);
            $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

            return $moneyFormatter->format($moneyObject);
        }));

    }

    public function getMappingDirections(): array
    {
        return [
            \AppBundle\Entity\Price::class => \AppBundle\Dto\PriceSimple::class
        ];
    }
}