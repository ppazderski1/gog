<?php

namespace AppBundle\Repository;

use AppBundle\Exception\EntityValidationException;

class AbstractRepositoryBase extends \Doctrine\ORM\EntityRepository
{
    /** @var \AppBundle\Service\MapperService  */
    protected $mapperService;

    /** @var \Symfony\Component\Validator\Validator\ValidatorInterface */
    protected $validator;

    /**
     * @param \AppBundle\Service\MapperService $mapperService
     */
    public function setMapperService(\AppBundle\Service\MapperService $mapperService) {
        $this->mapperService = $mapperService;
    }

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function setValidator(\Symfony\Component\Validator\Validator\ValidatorInterface $validator) {
        $this->validator = $validator;
    }

    /**
     * @return \AppBundle\Service\MapperService
     * @throws \Exception
     */
    public function getMapperService()
    {
        if (!$this->mapperService instanceof \AppBundle\Service\MapperService) {
            throw new \Exception('Mapper service is not loaded', 400);
        }
        return $this->mapperService;
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     * @throws \Exception
     */
    public function getValidator()
    {
        if (!$this->validator instanceof \Symfony\Component\Validator\Validator\ValidatorInterface) {
            throw new \Exception('Validator service is not loaded', 400);
        }
        return $this->validator;
    }

    /**
     * @param $entity
     * @throws \Exception
     */
    public function validateEntity($entity) : void
    {
        $errors = $this->getValidator()->validate($entity);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }

            throw new EntityValidationException(json_encode($messages), 400);
        }
    }
}