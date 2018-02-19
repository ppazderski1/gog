<?php

namespace AppBundle\Service\Mapping;


use NSM\Mapper\PropertyAccess\ClosureGetter;
use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class CartEntityMapping implements \NSM\Mapper\MappingBuilderInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    public function build(\NSM\Mapper\Mapping $mapping): void
    {
        $mapping->forProperty('id', new ClosureGetter(function (\AppBundle\Entity\Cart $cart) {
            return $cart->getId();
        }));

        $mapping->forProperty('products', new ClosureGetter(function (\AppBundle\Entity\Cart $cart) {
            return $this->mapper->convert($cart->getProducts(), \AppBundle\Dto\Product::class);
        }));

        $mapping->forProperty('priceTotal', new ClosureGetter(function (\AppBundle\Entity\Cart $cart) {

            $simplePrice = new \AppBundle\Dto\PriceSimple();

            $currencyZone = $cart->getUser()->getCurrencyZone();

            $products = $cart->getProducts();

            $value = 0;

            /** @var \AppBundle\Entity\Product $product */
            foreach ($products as $product) {
                $prices = $product->getPrices();
                list($price) = $prices;
                $value += $price->getValue();
            }

            $simplePrice->amount = $value;
            $simplePrice->code = $currencyZone->getCurrency();

            $moneyObject = new Money($value, new Currency($currencyZone->getCurrency()));

            $currencies = new ISOCurrencies();

            $numberFormatter = new \NumberFormatter($currencyZone->getLocale(), \NumberFormatter::CURRENCY);
            $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

            $simplePrice->formatted = $moneyFormatter->format($moneyObject);

            return $simplePrice;
        }));

        $mapping->forProperty('createdAt', new ClosureGetter(function (\AppBundle\Entity\Cart $cart) {
            return $cart->getCreatedAt();
        }));

        $mapping->forProperty('updatedAt', new ClosureGetter(function (\AppBundle\Entity\Cart $cart) {
            return $cart->getUpdatedAt();
        }));

        $mapping->forProperty('deletedAt', new ClosureGetter(function (\AppBundle\Entity\Cart $cart) {
            return $cart->getDeletedAt();
        }));

        $mapping->forProperty('submittedAt', new ClosureGetter(function (\AppBundle\Entity\Cart $cart) {
            return $cart->getSubmittedAt();
        }));
    }

    public function getMappingDirections(): array
    {
        return [
            \AppBundle\Entity\Cart::class => \AppBundle\Dto\Cart::class
        ];
    }
}