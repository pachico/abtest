<?php

namespace Pachico\Abtest\Test;

use \Pachico\Abtest\Split,
	\Mockery as m;

/**
 * 
 */
class TestTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Test
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$mocked_memory = m::mock('\Pachico\Abtest\Memory\MemoryInterface');

		$this->object = new Test('foo', new Split\ArrayProbability([50, 50]), $mocked_memory, null, 'tracking_id');
	}

	protected function tearDown()
	{
		parent::tearDown();
		m::close();
	}

	/**
	 * @covers Pachico\Abtest\Test\Test::getName
	 */
	public function testGetName()
	{
		$this->assertSame('foo', $this->object->getName());
	}

	/**
	 * @covers Pachico\Abtest\Test\Test::isParticipant
	 */
	public function testIsParticipant()
	{
		$this->assertTrue($this->object->isParticipant());

		$mocked_memory = m::mock('\Pachico\Abtest\Memory\MemoryInterface');

		$mocked_segmentation = m::mock('\Pachico\Abtest\Segmentation\ByDevice');
		$mocked_segmentation->shouldReceive('isParticipant')->andReturn(true);

		$this->object = new Test('foo', new Split\ArrayProbability([50, 50]), $mocked_memory, $mocked_segmentation);

		$this->assertTrue($this->object->isParticipant());
	}

	/**
	 * @covers Pachico\Abtest\Test\Test::getVersion
	 * @covers Pachico\Abtest\Test\Test::__construct
	 */
	public function testGetVersion()
	{
		$mocked_memory = m::mock('\Pachico\Abtest\Memory\MemoryInterface');
		$mocked_memory->shouldReceive('getVersion')->andReturn(1);

		$mocked_segmentation = m::mock('\Pachico\Abtest\Segmentation\ByDevice');
		$mocked_segmentation->shouldReceive('isParticipant')->once()->andReturn(true);
		$mocked_segmentation->shouldReceive('isParticipant')->once()->andReturn(false);

		$this->object = new Test('foo', new Split\ArrayProbability([50, 50]), $mocked_memory, $mocked_segmentation);

		$this->assertSame(1, $this->object->getVersion());
		$this->assertSame(false, $this->object->getVersion());
	}

	/**
	 * @covers Pachico\Abtest\Test\Test::getTrackingId
	 */
	public function testGetTracking_id()
	{
		$this->assertSame($this->object->getTrackingId(), 'tracking_id');
	}

}
