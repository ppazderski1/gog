<?php

namespace Application\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;

class OAuth extends Fixture
{

    public function getDependencies()
    {
        return [User::class];
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $manager->getClassMetaData(\AppBundle\Entity\Client::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        $oauthClient = new \AppBundle\Entity\Client();
        $oauthClient->setAllowedGrantTypes(['password', 'refresh_token']);
        $oauthClient->setId(1);
        $oauthClient->setRandomId('randomid');
        $oauthClient->setSecret('secret');

        $manager->persist($oauthClient);

        foreach (User::DATA_ARRAY as $data) {
            $user = $this->getReference('user-' . $data['username']);
            if (! $user instanceof \AppBundle\Entity\User) {
                throw new \Exception('invalid user object saved in fixture');
            }

            $userAccessToken = new \AppBundle\Entity\AccessToken();
            $userAccessToken->setToken($data['username']);
            $userAccessToken->setClient($oauthClient);
            $userAccessToken->setUser($user);
            $userAccessToken->setExpiresAt(2147483647);
            $manager->persist($userAccessToken);
        }

        $manager->flush();
    }
}