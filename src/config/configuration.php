<?php

namespace Pachico\Abtest\Config;

use \Pachico\Abtest\Memory,
	\Pachico\Abtest\Tracking,
	\Pachico\Abtest\Test;

/**
 * 
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
	 * @var Memory\MemoryInterface
	 */
	protected $_memory;

	/**
	 * 
	 * @param \Pachico\Abtest\Memory\MemoryInterface $memory
	 * @param \Pachico\Abtest\Tracking\TrackingInterface $tracking
	 */
	public function __construct(Memory\MemoryInterface $memory, Tracking\TrackingInterface $tracking)
	{
		$this->_memory = $memory;
		$this->_tracking = $tracking;
	}

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
	 * @return \Pachico\Abtest\Tracking\TrackingInterface
	 */
	public function getTracking()
	{
		return $this->_tracking;
	}

	/**
	 * 
	 * @return Memory\MemoryInterface
	 */
	public function getMemory()
	{
		return $this->_memory;
	}

}
