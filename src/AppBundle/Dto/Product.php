<?php

namespace AppBundle\Dto;


/**
 * Class Product
 * @package AppBundle\Dto
 */

class Product
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
     * @var integer
     */
    public $stockSize;

    /**
     * @var boolean
     */
    public $isProtected;

    /**
     * @var \AppBundle\Dto\PriceSimple
     */
    public $price;
}