<?php

namespace Pachico\Abtest\Split;

/**
 * 
 */
class ArrayProbabilityTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var ArrayProbability
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new ArrayProbability([50, 50]);
	}

	/**
	 * @covers Pachico\Abtest\Split\ArrayProbability::createVersion
	 */
	public function testCreateVersion()
	{
		$this->object = new ArrayProbability([50, 50], 1);

		$this->assertSame(0, $this->object->createVersion());

		$this->object = new ArrayProbability([50, 50], 60);

		$this->assertSame(1, $this->object->createVersion());

		$this->object = new ArrayProbability([50, 50, 50], 110);

		$this->assertSame(2, $this->object->createVersion());
		
		$this->object = new ArrayProbability([50, 50, 50], 200);

		$this->assertSame(0, $this->object->createVersion());
	}

}
