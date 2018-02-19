<?php

namespace AppBundle\Dto;


/**
 * Class Cart
 * @package AppBundle\Dto
 */
class Cart
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Product[]
     */
    public $products;

    /**
     * @var PriceSimple
     */
    public $priceTotal;

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var \DateTime
     */
    public $updatedAt;

    /**
     * @var \DateTime
     */
    public $submittedAt;

    /**
     * @var \DateTime
     */
    public $deletedAt;
}