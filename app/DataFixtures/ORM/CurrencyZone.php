<?php

namespace Application\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;

class CurrencyZone extends Fixture
{
    const DATA_ARRAY = [
        [
            'id'    => 1,
            'locale' => 'pl_PL',
            'currency' => 'PLN',
            'name' => 'Polska',

        ],
        [
            'id'    => 2,
            'locale' => 'en_US',
            'currency' => 'USD',
            'name' => 'USA',

        ],
    ];

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $manager->getClassMetaData(\AppBundle\Entity\CurrencyZone::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach (self::DATA_ARRAY as $zone) {
            $currencyZone = new \AppBundle\Entity\CurrencyZone();

            $currencyZone->setId($zone['id']);
            $currencyZone->setLocale($zone['locale']);
            $currencyZone->setCurrency($zone['currency']);
            $currencyZone->setName($zone['name']);

            $manager->persist($currencyZone);

            $this->setReference('currencyZone-' . $zone['id'], $currencyZone);
        }

        $manager->flush();
    }
}