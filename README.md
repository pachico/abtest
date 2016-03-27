#Pachico/Abtest

[![Build Status](https://travis-ci.org/pachico/abtest.svg?branch=master)](https://travis-ci.org/pachico/abtest) [![Codacy Badge](https://api.codacy.com/project/badge/grade/b8b7e11326e4476d88245668bbb49430)](https://www.codacy.com/app/nanodevel/abtest)

Flexible and framework agnostic AB test library.  
It comes with built-in features but accepts custom behaviour injection.

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
	* [Splitters](#splitters)
		- [Probabilities from array](#probabilities-from-array)
		- [Probabilities from array in Redis](#probabilities-from-array-in-redis)
		- [Custom splitters](#custom-splitters)
	* [Segmentation](#segmentation)
		-  [By device](#by-device)
		- [Custom segmentation](#custom-segmentation)
	* [Memory](#memory)
		- [Cookie](#cookie)
		- [Custom memory](#custom-memory)
	* [Tracking](#tracking)
		- [Google Experiments](#google-experiments)
		- [Custom tracking](#custom-tracking)
	* [Engine and Test](#engine-and-test)
- [Examples](#examples)
- [Contact and contribute](#contact-and-contribute)

##Installation 
Via composer

	composer require pachico/abtest

## Simple scenario

The package aims to be powerful and fully configurable but in ideal cases, you will just need an implementation like this:

```php
use \Pachico\Abtest;

$abtest_engine = new Abtest\Engine(
	new Abtest\Config\FromFile('path/to/config.php')
);
```

and interacting with the tests will be as simple as:

```php
if ($abtest_engine->getTest('your-test')->isParticipant())
{
	...
	if (1 === $abtest_engine->getTest('your-test')->getVersion())
	{
		// Variation version code
	}
	else
	{
		// Control version code
	}
}
```

## Main concepts

Before diving into examples, it's important to describe the main actors in this library.

### Configurator

These classes load the tests and their dependencies and provide a configuration object to **Engine**.
You can indicate the path to a configuration file, provide an array or set it with a chainable version.

### Test

A **Test** is an individual AB test running in your web application. This library allowes you to have as many as you want, each one with unique characteristics.

### Split

**Split** classes are the ones that assign a version for each AB test depending on their own logic to each new user.
This library comes with probability implementations. 
These can be set as a simple array with **ArrayProbability([50, 50])** or can be fetched from Redis for a more dynamic throttling with **RedisArrayProbability()**.

### Memory

We call **Memory** to the class that will store test versions for an user. 
For most cases, storing values in a cookie will do, however, it's a common case to store it in session.
Most frameworks have their own sessions implementation, reason why this library does not come with a built-in one, since chances are they won't be compatible.
If you want/need a specific session implementation, let me know.

### Segmentation

When running tests, you might not want to run it for all your audience but to only to those surfing with a particular device, or language, or country of origin, etc.
It is not mandatory to specify a segmentation but if you need to, you can use the built-in UserAgent based one **ByDevice()**.
If you need a custom segmentation, you can easily write one that will implement **SegmentationInterface** and inject it.

### Tracking

AB tests are usless if you cannot track their outcome.
Tracking classes are the ones that take care of registering the AB test for analytical purposes.
This library includes a **GoogleExperiments()** class that returns the tracking code to be included in web pages to be tracked in Google Analytics.
More **Tracking** implementations need to use **TrackingInterface** and simply be injected.

## API

For the sake of simplicity, we omit the  namespace usage *\Pachico\Abtest* from examples.

```php
use \Pachico\Abtest;
```

### Instantiation and configurators

**Engine** class requires to be constructed passing a configurator to it:

```php
/* @var $configurator Config\ConfiguratorInterface  */
$engine = new Abtest\Engine($configurator);
```

#### From config file

You can provide the path to a configuration file with the definiton of tests and dependencies.

```php
$engine = new Abtest\Engine(
	new Abtest\Config\FromFile('path/to/config.php')
);
```

Where a configuration file could be like this:

```php
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
```

it might look complicated at first, but this level of granularity provides flexibility and the capacity to implement your own policies and functionality.
Think of it as some sort of Dependency Injection Container.

>For your convenience (and to avoid typos), you can find constants in **Config\ConfiguratorInterface** that represent the keys for configuration, like ('tests', 'segmentation', etc.).  

#### From config array

You can also pass the contents of a typical config file to the proper configurator:

```php
/* @var $config array */
$engine = new Abtest\Engine(
	new Abtest\Config\FromArray($config)
);
```

The content of a configuration file is the one above, in [From config file](#from-config-file).

#### Chainable configurator

You might find convenient, instead, to create a configuration with a chainable class.

```php
$configurator = new Abtest\Config\Chainable(
	new Abtest\Memory\Cookie('ABTESTS'), 
	new Abtest\Tracking\GoogleExperiments(true)
);

$configurator
	->addTest('test1', new Abtest\Split\ArrayProbability([50, 50]), null, 'test1_tracking_id')
	->addTest('test2', new Abtest\Split\ArrayProbability([50, 50]), null, 'test2_tracking_id');

$engine = new Abtest\Engine($configurator);
```

### Splitters

#### Probabilities from array

If you want to have a random probability splitting policy you can use **ArrayProbability()** class.

```php
new Abtest\Split\ArrayProbability([50, 50]);
```

Where the probabilities for a user to land in a particular version are determined by the integer numbers assigned to each version.
Control is the first array value, variation 1 is the second value, and so on.
==Yes, this means you can have endless variations, each one with a specific weight.==

The example above, means a test with **control version** and **one variation**, where each one have 50% chances to be selected.

> The same would be with numbers like *[100, 100]* or *[500, 500]* since the probabilities are weighted relatively to the sum of all of the variations.

#### Probabilities from array in Redis

With this class, you can fetch from Redis the probabilities array.
You might want to do this to avoid having to do a release simply to chance splitting policy.

```php
new Split\RedisArrayProbability('test_key', 'ABTESTS:', [
	'host' => '127.0.0.1'
]),
```

Internally, it implements [Credis](https://github.com/colinmollenhour/credis), which allows you to connect to Redis even if you don't have the php extension installed (although it is recommended, for better performance). 
You can indicate a prefix for all your keys in Redis and the connection parameters.
Check phpdoc for more details about parameters to be sent.

Alternatively, instead of a configuration array, you can also inject a Redis connection:

```php
new Split\RedisArrayProbability('test_key', 'ABTESTS:', $redis_connection),
```

#### Custom splitters

You can always create your own splitters. You simply need to implement **Split\SplitInterface** and inject them from configuration/ors.

### Segmentation

Segmentation allows you to select the audience for each AB test.
This library allows you to filter users by their device type.

#### By device

To filter by device simply use:

```php
new Segmentation\ByDevice(Segmentation\ByDevice::DEVICE_NOT_MOBILE)
```

Other filters are:

```php
Segmentation\ByDevice::DEVICE_DESKTOP;
Segmentation\ByDevice::DEVICE_TABLET;
Segmentation\ByDevice::DEVICE_MOBILE;
Segmentation\ByDevice::DEVICE_NOT_DESKTOP;
Segmentation\ByDevice::DEVICE_NOT_MOBILE;

```

It internally uses [Mobile_Detect](https://github.com/serbanghita/Mobile-Detect) library, so it's possible to filter by much more.

#### Custom segmentation

You can always create your own segmentation. You simply need to implement **Segmentation\SegmentatIoninterface** and inject them from configuration/ors.

### Memory

These classes store versions of users in a user space.

#### Cookie

This class will store a cookie in user's browsers with the versions of each test they participate in.

```php
new Memory\Cookie('ABTESTS', 2592000)
```

The first parameter indicates the name of the cookie where versions will be saved, whilst the second is the TTL of the given cookie in seconds. 

#### Custom memory

You can always create your own memory classes. You simply need to implement **Memory\MemoryInterface** and inject them from configuration/ors.

### Tracking

Tracking might return tracking code, save results somewhere or whatever implementation you need.

#### Google Experiments

Google Experiments requires to print JavaScript to match sessions to versions.

```php
new Tracking\GoogleExperiments(true);
``` 

When passing true to the constructor, it will return also the JavaScript source for GoogleExperiments.
> It is required to provide the **tracking_id** parameter to a test when this tracking is used.
> This is the default tracking policy if none is provided.

The outcome will be provided by 

```php
echo $engine->track();
```

and the result will be something like this:
```html
<script src="//www.google-analytics.com/cx/api.js"></script>
<script>cxApi.setChosenVariation(0, 'colour_tracking_id');</script>
<script>cxApi.setChosenVariation(1, 'size_tracking_id');</script>
```

#### Custom tracking
You can always create your own tracking classes. You simply need to implement **Tracking\TrackingInterface** and inject them from configuration/ors.

### Engine and Test
Once Engine has been instanciated, you can access each individual test like this:

```php
$engine->getTest('yourtest');
```
To each test you can ask if, a user is participating in the test:

```php
$engine->getTest('yourtest')->isParticipant();
```

and which version of the test, the user is into:

```php
$abtest_engine->getTest('yourtest')->getVersion();
```

Versions will be integers, where 0 is your control version, 1 is the first variation, etc.

##Examples

Please check the **examples** folder for real case scenarios.

## Contact and contribute

Feel free to contact me at [nanodevel@gmail.com](nanodevel@gmail.com) or in [Github](https://github.com/pachico/abtest) for bug fixes, doubts, requests or contributions.

Cheers
