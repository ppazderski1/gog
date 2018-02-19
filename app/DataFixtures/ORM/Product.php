<?php

namespace Application\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;

class Product extends Fixture
{
    const DATA_ARRAY = [
        [
            'id' => 1,
            'name' => 'Fallout',
            'isProtected' => true
        ],
        [
            'id' => 2,
            'name' => 'Don’t Starve',
            'isProtected' => true
        ],
        [
            'id' => 3,
            'name' => 'Baldur’s Gate',
            'isProtected' => true
        ],
        [
            'id' => 4,
            'name' => 'Icewind Dale',
            'isProtected' => true
        ],
        [
            'id' => 5,
            'name' => 'Bloodborne',
            'isProtected' => true
        ],
    ];

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $manager->getClassMetaData(\AppBundle\Entity\Product::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach(self::DATA_ARRAY as $productDraft) {
            $product = new \AppBundle\Entity\Product();

            $product->setId($productDraft['id']);
            $product->setName($productDraft['name']);
            $product->setIsProtected($productDraft['isProtected']);

            $manager->persist($product);

            $this->setReference('product-' . $productDraft['id'], $product);
        }

        $manager->flush();
    }
}