<?php

namespace AppBundle\ValueObject;


class CartSearch extends Base
{
    protected $id;

    protected $userId;

    protected $currencyZone;

    public function __construct(int $id = null, int $userId, CurrencyZone $currencyZone)
    {
        if( null !== $id && !is_int($id) ) {
            throw new \InvalidArgumentException('ID must be of type integer or null');
        }

        if( !is_int($userId) ) {
            throw new \InvalidArgumentException('UserId must be of type integer');
        }

        $this->id = $id;
        $this->userId = $userId;
        $this->currencyZone = $currencyZone;

    }

}