<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Config;

/**
 * All configurators must implement this interface
 */
interface ConfiguratorInterface
{

	// For commodity in config files to avoid typos
	const TESTS = 'tests';
	const TRACKING_ID = 'tracking_id';
	const SEGMENTATION = 'segmentation';
	const SPLIT = 'split';
	const MEMORY = 'memory';
	const TRACKING = 'tracking';

	/**
	 * Method to implement that returns the configuration file
	 */
	public function getConfiguration();
}
