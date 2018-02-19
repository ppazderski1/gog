<?php

namespace AppBundle\ValueObject;


/**
 * Class Price
 * @package AppBundle\ValueObject
 */
class Price extends Base
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $value;

    /**
     * @var \DateTime
     */
    protected $validFrom;

    /**
     * @var \DateTime
     */
    protected $validTo;

    /**
     * Price constructor.
     * @param int $id
     * @param int $value
     * @param string $validFrom
     * @param string $validTo
     */
    public function __construct(int $id = null, int $value, string $validFrom = null, string $validTo = null)
    {
        if( null !== $id && !is_int($id) ) {
            throw new \InvalidArgumentException('ID must be of type integer or null');
        }

        if( !is_int($value) && $value < 1 ) {
            throw new \InvalidArgumentException('Value must be integer greater then 0');
        }

        if( null !== $validFrom && !$this->isDateTimeString($validFrom) ) {
            throw new \InvalidArgumentException('ValidFrom must be either null or proper ISO datetime format');
        }

        if( null !== $validTo && !$this->isDateTimeString($validTo) ) {
            throw new \InvalidArgumentException('ValidTo must be either null or proper ISO datetime format');
        }

        if( $validFrom != null ) {
            $validFrom = new \DateTime($validFrom);
        }

        if( $validTo != null ) {
            $validTo = new \DateTime($validTo);
        }

        if($validFrom instanceof \DateTime && $validFrom->getTimestamp() < gmmktime() ) {
            throw new \InvalidArgumentException('ValidFrom must be greater then now (GMT timezone)');
        }

        if($validFrom instanceof \DateTime && $validTo instanceof \DateTime && $validTo <= $validFrom ) {
            throw new \InvalidArgumentException('ValidTo must be greater then ValidFrom');
        }

        $this->id = $id;
        $this->value = $value;
        $this->validFrom = $validFrom;
        $this->validTo = $validTo;

    }

    /**
     * @param $dateTime
     * @return bool
     */
    private function isDateTimeString($dateTime) : bool
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $dateTime, $parts) == true) {

            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($dateTime);

            if ($input_time === false) {
                return false;
            }

            return $input_time == $time;

        } else {

            return false;

        }
    }
}