<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */
use \Pachico\Abtest\Config;
use \Pachico\Abtest\Split;
use \Pachico\Abtest\Memory;
use \Pachico\Abtest\Tracking;
use \Pachico\Abtest\Segmentation;

return [
	Config\ConfiguratorInterface::TESTS => [
		'TEST_FOO' => [
			Config\ConfiguratorInterface::TRACKING_ID => 'ID_FOO',
			Config\ConfiguratorInterface::SEGMENTATION => new Segmentation\ByDevice(Segmentation\ByDevice::DEVICE_DESKTOP),
			Config\ConfiguratorInterface::SPLIT => new Split\ArrayProbability([50, 50, 50])
		],
		'TEST_BAR' => [
			Config\ConfiguratorInterface::TRACKING_ID => 'ID_BAR',
			Config\ConfiguratorInterface::SEGMENTATION => new Segmentation\ByDevice(Segmentation\ByDevice::DEVICE_MOBILE),
			Config\ConfiguratorInterface::SPLIT => new Split\ArrayProbability([50, 50])
		]
	],
	Config\ConfiguratorInterface::MEMORY => new Memory\Cookie('abtests', null),
	Config\ConfiguratorInterface::TRACKING => new Tracking\GoogleExperiments()
];
