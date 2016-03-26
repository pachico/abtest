#Pachico/Abtest

[![Build Status](https://travis-ci.org/pachico/abtest.svg?branch=master)](https://travis-ci.org/pachico/abtest)

Flexible AB test suite that accepts custom Segmentation, Split, Memory and Tracking.

It is now in development phase but feel free to check the examples folder, as they are functional.

The package comes with already built in cases but it's very easy to create a new one that will satisfy your technical or marketing requirements.

**Table of Contents**  *generated with [DocToc](http://doctoc.herokuapp.com/)*

## Table of contents

- [Installation](#installation)
- [Simple scenario](#simple-scenario)
- [Main concepts](#main-concepts)
	* [Configurator](#configurator)
	* [Test](#test)
	* [Split](#split)
	* [Memory](#memory)
	* [Segmentation](#segmentation)
	* [Tracking](#tracking)
- [API](#api)
	* [Instantiation and configurators](#instantiation-and-configurators)
		- [From config file](#from-config-file)
		- [From config array](#from-config-array)
		- [Chainable configurator](#chainable-configurator)
- [Examples](#examples)
- [Contact me](#contact-me)

##Installation 
Via composer

	composer require pachico/abtest

## Simple scenario

The package aims to be powerful and fully configurable but in ideal cases, you will just have an implementation like this:

	use \Pachico\Abtest;

	$abtest_engine = new Abtest\Engine(
		new Abtest\Config\FromFile('path/to/config.php')
	);

and interacting with the tests will be as simple as:

	if ($abtest_engine->getTest('your-test')->isParticipant()) 
	{
		...
		if (1 === $abtest_engine->getTest('your-test')->getVersion()) 
		{
			// Variation version code
		} else {
			// Control version code
		}

	}

## Main concepts

Before diving into examples, it's important to describe the main actors in this package.

### Configurator

These classes load the tests and their dependencies.
You can indicate the path to a configuration file, provide an array or set it with a chainable version.

### Test
A **Test** is each individual AB test that will be running in your app. This library allowes you to have as many as you want, each one with unique characteristics.

### Split

**Split** classes are the ones that provide a version for each AB test depending on their own logic to each new user.
This library comes with probability implementations. These can be set as a simple array with **ArrayProbability([50, 50])** or can be fetched from Redis for a more dynamic throttling with **RedisArrayProbability()**.

### Memory

We call **Memory** to the class that will store test versions for an user. 
For most cases, storing values in a cookie will do, however, it's a common case to store it in session.
Most frameworks have their own sessions implementation, reason why this library does not come with a built-in one, since chances are they won't be compatible.
If you want/need a specific session implementation, let me know.

### Segmentation

When running tests, you might not want to run it for all your audience.
You might want to do it only to those surfing with a particular device, or language, or country of origin...
It is not mandatory to specify a segmentation but if you need to, you can use the built-in UserAgent based one **ByDevice()**.
If you need a custom segmentation, you can easily write one that will implement SegmentationInterface and inject it.

### Tracking

AB tests are usless if you cannot track their outcome.
Tracking classes are the ones that take care of registering the AB test for analytical purposes.
This library includes a **GoogleExperiments()** class that returns the tracking code to be included in web pages to be tracked in Google Analytics.
More **Tracking** implementations need to use TrackingInterface and simply be injected.

## API

For the sake of simplicity, we omit the  namespace usage *\Pachico\Abtest* from examples.

	use \Pachico\Abtest;

### Instantiation and configurators

**Engine** class requires to constructed passing a configurator to it:

	/* @var $configurator Config\ConfiguratorInterface  */
	$engine = new Abtest\Engine($configurator);

#### From config file

You can provide the path to a configuration file with the definiton of tests and dependencies

	$engine = new Abtest\Engine(
		new Abtest\Config\FromFile('path/to/config.php')
	);

Where a configuration file could be like this:

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

it might look complicated at a first look, but this level of granularity provides flexibility and capacity to implement your own policies and functionality.
Think of it as some sort of Dependency Injection Container.

#### From config array

You can also pass the contents of a typical config file to the proper configurator:
	
	/* @var $config array */
	$engine = new Abtest\Engine(
		new Abtest\Config\FromArray($config)
	);

The content of a configuration file is the one above, in [From config file](#from-config-file).

#### Chainable configurator

You might find convenient, instead, to create a configuration from a chainable class.

	$configurator = new Abtest\Config\Chainable(
		new Abtest\Memory\Cookie('ABTESTS'), 
		new Abtest\Tracking\GoogleExperiments(true)
	);

	$configurator
		->addTest('test1', new Abtest\Split\ArrayProbability([50, 50]), null, 'test1_tracking_id')
		->addTest('test2', new Abtest\Split\ArrayProbability([50, 50]), null, 'test2_tracking_id');


##Examples

Please check the **examples** folder for real case scenarios.

##Contact me

Feel free to contact me for bug fixes, doubts or requests.

Cheers


