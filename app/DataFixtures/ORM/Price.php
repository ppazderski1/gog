<?php

namespace Application\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;

class Price extends Fixture
{
    const DATA_ARRAY = [
        [
            'id'   => 1,
            'zone' => 'currencyZone-1',
            'product' => 'product-1',
            'value' => 1250,
        ],
        [
            'id'   => 2,
            'zone' => 'currencyZone-1',
            'product' => 'product-2',
            'value' => 1640,
        ],
        [
            'id'   => 3,
            'zone' => 'currencyZone-1',
            'product' => 'product-3',
            'value' => 900,
        ],
        [
            'id'   => 4,
            'zone' => 'currencyZone-1',
            'product' => 'product-4',
            'value' => 1700,
        ],
        [
            'id'   => 5,
            'zone' => 'currencyZone-1',
            'product' => 'product-5',
            'value' => 1450,
        ],
        [
            'id'   => 6,
            'zone' => 'currencyZone-2',
            'product' => 'product-1',
            'value' => 199,
        ],
        [
            'id'   => 7,
            'zone' => 'currencyZone-2',
            'product' => 'product-2',
            'value' => 299,
        ],
        [
            'id'   => 8,
            'zone' => 'currencyZone-2',
            'product' => 'product-3',
            'value' => 399,
        ],
        [
            'id'   => 9,
            'zone' => 'currencyZone-2',
            'product' => 'product-4',
            'value' => 499,
        ],
        [
            'id'   => 10,
            'zone' => 'currencyZone-2',
            'product' => 'product-5',
            'value' => 599,
        ],
    ];

    public function getDependencies()
    {
        return [CurrencyZone::class, Product::class];
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $manager->getClassMetaData(\AppBundle\Entity\Price::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach(self::DATA_ARRAY as $priceDraft) {
            $price = new \AppBundle\Entity\Price();

            $price->setId($priceDraft['id']);
            $price->setCurrencyZone($this->getReference($priceDraft['zone']));
            $price->setProduct($this->getReference($priceDraft['product']));
            $price->setValue($priceDraft['value']);
            $price->setValidFrom( new \DateTime("now"));
            $price->setIsActive(true);

            $manager->persist($price);
        }

        $manager->flush();
    }
}