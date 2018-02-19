<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MapperService extends \NSM\Mapper\Mapper implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function __construct($serviceContainer, $mappings)
    {
        parent::__construct();

        $this->setContainer($serviceContainer);
        $this->registerCustomMappings($mappings);
    }

    private function registerCustomMappings($mappings = [])
    {

        foreach ($mappings as $mapping) {

            /** @var \NSM\Mapper\MappingBuilderInterface $mappingBuilder */
            $mappingBuilder = new $mapping();
            if ($mappingBuilder instanceof Mapping\MapperAwareInterface) {
                $mappingBuilder->setMapper($this);
            }

            if ($mappingBuilder instanceof ContainerAwareInterface) {
                $mappingBuilder->setContainer($this->container);
            }

            $this->registerCustomMapping($mappingBuilder);
        }
    }
}