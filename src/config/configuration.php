<?php

namespace Pachico\Abtest\Config;

use \Pachico\Abtest\Memory;
use \Pachico\Abtest\Tracking;
use \Pachico\Abtest\Test;

/**
 * Description of configuration
 *
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 */
class Configuration
{

	protected $_tests = [];

	/**
	 *
	 * @var Tracking\TrackingInterface
	 */
	protected $_tracking;

	/**
	 *
	 * @param \Pachico\Abtest\Test\Test $test
	 * @return \Pachico\Abtest\Config\Configuration
	 */
	public function addTest(Test\Test $test)
	{
		$this->_tests[$test->getName()] = $test;
		return $this;
	}

	/**
	 *
	 * @param string $name
	 * @return Test\Test
	 */
	public function getTestByName($name)
	{
		if (isset($this->_tests[$name]))
		{
			return $this->_tests[$name];
		}

		return null;
	}

	/**
	 * 
	 * @return array 
	 */
	public function getTests()
	{
		return $this->_tests;
	}

	/**
	 *
	 * @param \Pachico\Abtest\Tracking\TrackingInterface $tracking
	 * @return \Pachico\Abtest\Config\Configuration
	 */
	public function setTracking(Tracking\TrackingInterface $tracking)
	{
		$this->_tracking = $tracking;
		return $this;
	}

}
