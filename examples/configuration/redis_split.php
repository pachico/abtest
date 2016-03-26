<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */
use \Pachico\Abtest\Config,
	\Pachico\Abtest\Split,
	\Pachico\Abtest\Memory,
	\Pachico\Abtest\Segmentation,
	\Pachico\Abtest\Tracking;

return [
	Config\ConfiguratorInterface::TESTS => [
		'image_test' => [
			Config\ConfiguratorInterface::TRACKING_ID => 'image_test',
			Config\ConfiguratorInterface::SPLIT => new Split\RedisArrayProbability('test_key', 'ABTESTS:', ['host' => '127.0.0.1']),
			Config\ConfiguratorInterface::SEGMENTATION => new Segmentation\ByDevice(Segmentation\ByDevice::DEVICE_NOT_MOBILE),
		],
	],
	Config\ConfiguratorInterface::MEMORY => new Memory\Cookie('ABTESTS', 1000000),
	Config\ConfiguratorInterface::TRACKING => new Tracking\GoogleExperiments(true)
];
