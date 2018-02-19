<?php

namespace AppBundle\Dto;


/**
 * Class CurrencyZone
 * @package AppBundle\Dto
 */
class CurrencyZone
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $locale;
}