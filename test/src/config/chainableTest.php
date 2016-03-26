<?php

namespace Pachico\Abtest\Config;

use \Pachico\Abtest\Split,
	\Pachico\Abtest\Segmentation,
	\Pachico\Abtest\Tracking,
	\Pachico\Abtest\Memory;

/**
 * 
 */
class ChainableTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Chainable
	 */
	protected $object;

	/**
	 * 
	 */
	protected function setUp()
	{
		$this->object = new Chainable;
	}

	/**
	 * @covers Pachico\Abtest\Config\Chainable::__construct
	 */
	public function test__construct()
	{
		$this->object = new Chainable();

		$this->assertInstanceOf('Pachico\Abtest\Tracking\GoogleExperiments', $this->object->getConfiguration()->getTracking());

		$this->object = new Chainable(new Memory\Cookie(Memory\Cookie::DEFAULT_COOKIE_NAME));

		$this->object = new Chainable(new Memory\Cookie(Memory\Cookie::DEFAULT_COOKIE_NAME), new Tracking\GoogleExperiments(true));
	}

	/**
	 * @covers Pachico\Abtest\Config\Chainable::addTest
	 */
	public function testAddTest()
	{
		$this->assertInstanceOf(
			'Pachico\Abtest\Config\Chainable', $this->object->addTest('foo', new Split\ArrayProbability([50, 50]), new Segmentation\ByDevice(Segmentation\ByDevice::DEVICE_DESKTOP), 'foo_tracking_id')
		);

		$this->assertInstanceOf(
			'Pachico\Abtest\Config\Chainable', $this->object->addTest('bar', new Split\ArrayProbability([50, 50]))
		);

		$tests = $this->object->getConfiguration()->getTests();

		$this->assertCount(2, $tests);

		foreach ($tests as $test)
		{
			$this->assertInstanceOf('Pachico\Abtest\Test\Test', $test);
		}
	}

	/**
	 * @covers Pachico\Abtest\Config\Chainable::getConfiguration
	 */
	public function testGetConfiguration()
	{
		$this->assertInstanceOf(
			'Pachico\Abtest\Config\Configuration', $this->object->getConfiguration()
		);
	}

}
