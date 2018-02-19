<?php

namespace AppBundle\ValueObject;


/**
 * Class Product
 * @package AppBundle\ValueObject
 */

class Product extends Base
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $stockSize;

    /**
     * Product constructor.
     * @param int $id
     * @param string $name
     * @param int $stockSize
     */
    public function __construct(int $id = null, string $name, int $stockSize = null)
    {
        if( null !== $id && !is_int($id) ) {
            throw new \InvalidArgumentException('ID must be of type integer or null');
        }

        if( !is_string($name) && 0 === strlen(trim($name)) ) {
            throw new \InvalidArgumentException('Name must be nonempty string');
        }

        if( (null !== $stockSize && !is_int($stockSize)) || (is_int($stockSize) && $stockSize < 0) ) {
            throw new \InvalidArgumentException('Stock Size must be of type unsigned int or null');
        }

        $this->id = $id;
        $this->name = $name;
        $this->stockSize = $stockSize;
    }


}