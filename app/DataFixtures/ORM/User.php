<?php

namespace Application\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;

class User extends Fixture
{

    const DATA_ARRAY = [
        [
            'username' => 'admin',
            'group' => [\AppBundle\Entity\User::GROUP_ADMIN],
            'currencyZoneRef' => 'currencyZone-2'
        ],
        [
            'username' => 'client1',
            'group' => [\AppBundle\Entity\User::GROUP_CLIENT],
            'currencyZoneRef' => 'currencyZone-2'
        ],
        [
            'username' => 'client2',
            'group' => [\AppBundle\Entity\User::GROUP_CLIENT],
            'currencyZoneRef' => 'currencyZone-1'
        ],
    ];

    public function getDependencies()
    {
        return [CurrencyZone::class];
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $manager->getClassMetaData(\AppBundle\Entity\User::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        $this->loadGroup($manager, \AppBundle\Entity\User::GROUP_ADMIN,  [\AppBundle\Entity\User::ROLE_ADMIN]);
        $this->loadGroup($manager, \AppBundle\Entity\User::GROUP_CLIENT, [\AppBundle\Entity\User::ROLE_CLIENT]);

        foreach (self::DATA_ARRAY as $data) {
            $this->loadUser($manager, $data['username'], true, [], $data['group'], $data['currencyZoneRef']);
        }

        $manager->flush();
    }

    private function loadGroup(\Doctrine\Common\Persistence\ObjectManager $manager, string $name, array $roles): void
    {
        $group = new \AppBundle\Entity\Group($name);
        $group->setRoles($roles);
        $manager->persist($group);

        $this->addReference($name, $group);
    }

    private function loadUser(
        \Doctrine\Common\Persistence\ObjectManager $manager,
        string $username,
        bool $isEnabled,
        array $roles,
        array $groups,
        string $currencyZoneRef
    ) {
        $user = new \AppBundle\Entity\User();
        $user->setUsername($username);
        $user->setPlainPassword($username);
        $user->setEmail($username . '@' . $username . '.loc');
        $user->setEnabled($isEnabled);


        $user->setRoles($roles);

        foreach ($groups as $group) {
            $groupRef = $this->getReference($group);
            $user->addGroup($groupRef);
        }

        $user->setCurrencyZone($this->getReference($currencyZoneRef));

        $manager->persist($user);

        $this->addReference('user-' . $username, $user);
    }
}