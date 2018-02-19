<?php

namespace AppBundle\Dto;


/**
 * Class User
 * @package AppBundle\Dto
 */
class User
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string[]
     */
    public $roles;

    /**
     * @var string[]
     */
    public $groups;

    /**
     * @var CurrencyZone
     */
    public $currencyZone;

}