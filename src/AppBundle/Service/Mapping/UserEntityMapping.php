<?php

namespace AppBundle\Service\Mapping;

use AppBundle\Entity\Group;
use NSM\Mapper\PropertyAccess\ClosureGetter;

class UserEntityMapping implements \NSM\Mapper\MappingBuilderInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    public function build(\NSM\Mapper\Mapping $mapping): void
    {
        $mapping->forProperty('id', new ClosureGetter(function (\AppBundle\Entity\User $user) {
            return $user->getId();
        }));

        $mapping->forProperty('username', new ClosureGetter(function (\AppBundle\Entity\User $user) {
            return $user->getUsername();
        }));

        $mapping->forProperty('roles', new ClosureGetter(function (\AppBundle\Entity\User $user) {
            return $user->getRoles();
        }));

        $mapping->forProperty('groups', new ClosureGetter(function (\AppBundle\Entity\User $user) {
            $groupsArray = [];
            $groups = $user->getGroups();
            /** @var Group $group */
            foreach ($groups as $group) {
                $groupsArray[] = $group->getName();
            }
            return $groupsArray;
        }));

        $mapping->forProperty('currencyZone', new ClosureGetter(function (\AppBundle\Entity\User $user) {
            return $this->mapper->convert($user->getCurrencyZone(), \AppBundle\Dto\CurrencyZone::class);
        }));

    }

    public function getMappingDirections(): array
    {
        return [
            \AppBundle\Entity\User::class => \AppBundle\Dto\User::class
        ];
    }
}