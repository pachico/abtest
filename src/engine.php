<?php

namespace Pachico\Abtest;

use \Pachico\Abtest\Test;

/**
 *
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
	 * @var Memory\MemoryInterface
	 */
	protected $_memory;

	/**
	 *
	 * @var Tracking\TrackingInterface
	 */
	protected $_tracking;

	/**
	 *
	 * @var Engine
	 */
	protected static $_singleton;

	/**
	 *
	 * @param \Pachico\Abtest\Config\ConfiguratorInterface $configurator
	 * @param \Pachico\Abtest\Memory\MemoryInterface $memory
	 * @param \Pachico\Abtest\Tracking\TrackingInterface $tracking
	 */
	public function __construct(Config\ConfiguratorInterface $configurator, Memory\MemoryInterface $memory = null, Tracking\TrackingInterface$tracking = null)
	{
		$this->_configuration = $configurator->getConfiguration();

		$this->_memory = $memory ?
			: new Memory\Cookie(Memory\Cookie::DEFAULT_COOKIE_NAME, null);

		$this->_tracking = $tracking ?
			: new Tracking\GoogleExperiments();

		foreach ($this->_configuration->getTests() as $test)
		{
			/* @var $test Test\Test */
			$test->getVersion();
		}

		$this->_memory->save();

		static::$_singleton = $this;
	}

	/**
	 * 
	 * @return Engine
	 * @throws \RuntimeException
	 */
	public static function getSingleton()
	{
		if (!static::$_singleton instanceof Engine)
		{
			throw new \RuntimeException('Singleton has not been registered since Engine was never instanciated.');
		}

		return static::$_singleton;
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
	 * @return Memory\MemoryInterface
	 */
	public function getMemory()
	{
		return $this->_memory;
	}

	/**
	 *
	 * @return Tracking\TrackingInterface
	 */
	public function getTracking()
	{
		return $this->_tracking->track($this->_configuration->getTests());
	}

}
