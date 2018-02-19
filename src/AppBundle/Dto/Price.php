<?php

namespace AppBundle\Dto;


/**
 * Class Price
 * @package AppBundle\Dto
 */
class Price
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var \AppBundle\Dto\CurrencyZone
     */
    public $currencyZone;

    /**
     * @var string
     */
    public $value;

    /**
     * @var \DateTime
     */
    public $validFrom;

    /**
     * @var \DateTime
     */
    public $validTo;
}