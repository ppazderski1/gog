<?php

namespace AppBundle\ValueObject;


/**
 * Class Cart
 * @package AppBundle\ValueObject
 */
class Cart extends Base
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var bool
     */
    protected $submitted;

    /**
     * Cart constructor.
     * @param $id
     * @param $submitted
     */
    public function __construct(int $id, $submitted)
    {
        if( !is_int($id) ) {
            throw new \InvalidArgumentException('ID must be of type integer or null');
        }

        if ( $submitted == 'False' || $submitted == 'false' || $submitted == '0' || $submitted === 0 ) {
            $submitted = false;
        } else if ( $submitted == 'True' || $submitted == 'true' || $submitted == '1' ||  $submitted === 1 ) {
            $submitted = true;
        } else {
            throw new \InvalidArgumentException('Submitted must be of type bool');
        }
        $this->id = $id;
        $this->submitted = $submitted;
    }


}