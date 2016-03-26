<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pachico\Abtest\Config;

/**
 * Description of array
 *
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 */
class FromArray implements ConfiguratorInterface
{

	/**
	 *
	 * @var Chainable
	 */
	protected $_chainable_configurator;

	/**
	 *
	 * @param array $configuration_array
	 */
	public function __construct(array $configuration_array)
	{

		$memory = null;

		if (isset($configuration_array[ConfiguratorInterface::MEMORY]))
		{
			$memory = $configuration_array[ConfiguratorInterface::MEMORY];
		}

		$tracking = null;

		if (isset($configuration_array[ConfiguratorInterface::TRACKING]))
		{
			$tracking = $configuration_array[ConfiguratorInterface::TRACKING];
		}

		$this->_chainable_configurator = new Chainable($memory, $tracking);

		if (!empty($configuration_array[ConfiguratorInterface::TESTS]) && is_array($configuration_array[ConfiguratorInterface::TESTS]))
		{
			foreach ($configuration_array[ConfiguratorInterface::TESTS] as $name => $test)
			{
				$this->_chainable_configurator->addTest(
					// Test name
					$name,
					// Splitting policy
					$test[ConfiguratorInterface::SPLIT],
					// Segmentation
					isset($test[ConfiguratorInterface::SEGMENTATION])
						? $test[ConfiguratorInterface::SEGMENTATION]
						: null,
					// Tracking id
					!is_null($test[ConfiguratorInterface::TRACKING_ID])
						? $test[ConfiguratorInterface::TRACKING_ID]
						: null);
			}
		}
	}

	/**
	 *
	 * @return Configuration
	 */
	public function getConfiguration()
	{
		return $this->_chainable_configurator->getConfiguration();
	}

}
