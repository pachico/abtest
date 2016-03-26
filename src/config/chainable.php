<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Config;

use \Pachico\Abtest\Memory,
	\Pachico\Abtest\Tracking,
	\Pachico\Abtest\Split,
	\Pachico\Abtest\Segmentation,
	\Pachico\Abtest\Test;

/**
 * Chainable configurator creates configuration manually on-the-run
 */
class Chainable implements ConfiguratorInterface
{

	/**
	 *
	 * @var Configuration
	 */
	protected $_configuration;

	/**
	 *
	 * @var Tracking\TrackingInterface  
	 */
	protected $_tracking;

	/**
	 * 
	 * @param \Pachico\Abtest\Memory\MemoryInterface $memory
	 * @param \Pachico\Abtest\Tracking\TrackingInterface $tracking
	 */
	public function __construct(Memory\MemoryInterface $memory = null, Tracking\TrackingInterface $tracking = null)
	{
		$tracking = $tracking ?
			: new Tracking\GoogleExperiments(true);

		$memory = $memory ?
			: new Memory\Cookie(Memory\Cookie::DEFAULT_COOKIE_NAME);

		$this->_configuration = new Configuration($memory, $tracking);
	}

	/**
	 * 
	 * @param string $name
	 * @param \Pachico\Abtest\Split\SplitInterface $split
	 * @param \Pachico\Abtest\Segmentation\SegmentatIoninterface $segmentation
	 * @param string $tracking_id
	 */
	public function addTest($name, Split\SplitInterface $split, Segmentation\SegmentatIoninterface $segmentation = null, $tracking_id = null)
	{
		$test_object = new Test\Test($name, $split, $this->_configuration->getMemory(), $segmentation, $tracking_id);

		$this->_configuration->addTest($test_object);

		return $this;
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
