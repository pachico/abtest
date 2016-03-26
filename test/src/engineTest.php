<?php

namespace Pachico\Abtest;

use \Mockery as m;

/**
 * 
 */
class EngineTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Engine
	 */
	protected $object;

	/**
	 * 
	 */
	protected function setUp()
	{
		$this->object = new Engine(new Config\Chainable());
	}

	/**
	 * @covers Pachico\Abtest\Engine::__construct
	 */
	public function test__construct()
	{
		$configurator = new Config\Chainable();

		$configurator->addTest('bar', new Split\ArrayProbability([50, 50]));

		$this->object = new Engine($configurator);
	}

	/**
	 * @covers Pachico\Abtest\Engine::getTest
	 */
	public function testGetTest()
	{
		$this->assertNull($this->object->getTest('foo'));

		$configurator = new Config\Chainable();

		$configurator->addTest('bar', new Split\ArrayProbability([50, 50]));

		$this->object = new Engine($configurator);

		$retrieved_test = $this->object->getTest('bar');

		$this->assertInstanceOf('Pachico\Abtest\Test\Test', $retrieved_test);

		$this->assertSame('bar', $retrieved_test->getName());
	}

	/**
	 * @covers Pachico\Abtest\Engine::track
	 */
	public function testTrack()
	{
		$tracking = m::mock('Pachico\Abtest\Tracking\TrackingInterface');
		$tracking->shouldReceive('track')->andReturn(true);

		$configurator = new Config\Chainable(null, $tracking);

		$this->object = new Engine($configurator);

		$this->assertTrue($this->object->track());
	}

}
