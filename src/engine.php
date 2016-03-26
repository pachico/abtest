<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest;

use \Pachico\Abtest\Test;

/**
 * AB test engine.
 * @see examples folder
 */
class Engine
{

	/**
	 *
	 * @var Config\Configuration
	 */
	protected $_configuration;

	/**
	 *
	 * @param \Pachico\Abtest\Config\ConfiguratorInterface $configurator
	 */
	public function __construct(Config\ConfiguratorInterface $configurator)
	{
		$this->_configuration = $configurator->getConfiguration();

		foreach ($this->_configuration->getTests() as $test)
		{
			/* @var $test Test\Test */
			$test->getVersion();
		}

		$this->_configuration->getMemory()->save();
	}

	/**
	 *
	 * @param string $test_name
	 * @return mixed Test\Test|null
	 */
	public function getTest($test_name)
	{
		return $this->_configuration->getTestByName($test_name);
	}

	/**
	 *
	 * @return Tracking\TrackingInterface
	 */
	public function track()
	{
		return $this->_configuration->getTracking()->track($this->_configuration->getTests());
	}

}
