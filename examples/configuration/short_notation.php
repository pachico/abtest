<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */
use \Pachico\Abtest\Segmentation,
	\Pachico\Abtest\Split,
	\Pachico\Abtest\Tracking,
	\Pachico\Abtest\Memory;

// Configuration array
return [
	// Array containing each test
	'tests' => [
		// First test name
		'TEST_FOO' => [
			// Segmentation policy 
			// (in this case, only desktop devices)
			'segmentation' => new Segmentation\ByDevice('desktop'),
			// Traffic splitting policy
			// (in this case, random 33% chances each version)
			'split' => new Split\ArrayProbability([50, 50, 50]),
			// Tracking id for Google Experiments
			'tracking_id' => 'ID_FOO',
		],
		// Second test name
		'TEST_BAR' => [
			// Segmentation policy 
			// (in this case, only mobile devices)
			'segmentation' => new Segmentation\ByDevice('mobile'),
			// Traffic splitting policy
			// (in this case, fetches in Redis chances)
			'split' => new Split\RedisArrayProbability('test_bar', 'ABTESTS:', ['host' => '127.0.0.1']),
			// Tracking id for Google Experiments
			'tracking_id' => 'ID_BAR',
		]
	],
	// Storage memory for users
	// (in this case, cookie based)
	'memory' => new Memory\Cookie('abtests'),
	// Tracking class
	// (in this case, Google Experiments)
	'tracking' => new Tracking\GoogleExperiments(true)
];
