<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pachico\Abtest\Config;

use \Pachico\Abtest\Test;
use \Pachico\Abtest\Segmentation;

/**
 * Description of array
 *
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 */
class FromArray implements ConfiguratorInterface
{

    /**
     *
     * @var Configuration
     */
    protected $_configuration;

    /**
     *
     * @param array $configuration_array
     */
    public function __construct(array $configuration_array)
    {
        $configuration_object = new Configuration();

        if (!empty($configuration_array[ConfiguratorInterface::TESTS]) && is_array($configuration_array[ConfiguratorInterface::TESTS])) {
            foreach ($configuration_array[ConfiguratorInterface::TESTS] as $name => $test) {
                $test_object = new Test\Test(
                    $name, $test[ConfiguratorInterface::SPLIT], $configuration_array[ConfiguratorInterface::MEMORY], ($test[ConfiguratorInterface::SEGMENTATION] instanceof Segmentation\SegmentatIoninterface)
                        ? $test[ConfiguratorInterface::SEGMENTATION]
                        : null, !is_null($test[ConfiguratorInterface::TRACKING_ID])
                        ? $test[ConfiguratorInterface::TRACKING_ID]
                        : null
                );

                $configuration_object->addTest($test_object);
            }
        }

        $configuration_object
            ->setTracking($configuration_array[ConfiguratorInterface::TRACKING])
        ;

        $this->_configuration = $configuration_object;
    }

    /**
     *
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->_configuration;
    }
}
