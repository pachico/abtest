<?php

namespace Pachico\Abtest\Config;

class FromArrayTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var FromArray
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$config_array = require TEST_CONFIG_FOLDER . 'config1.php';

		$this->object = new FromArray($config_array);
	}

	/**
	 * @covers Pachico\Abtest\Config\FromArray::__construct
	 */
	public function test__construct()
	{
		$config_array = require TEST_CONFIG_FOLDER . 'config1.php';
		$this->object = new FromArray($config_array);
	}

	/**
	 * @covers Pachico\Abtest\Config\FromArray::getConfiguration
	 */
	public function testGetConfiguration()
	{
		$this->assertInstanceOf(
			'Pachico\Abtest\Config\Configuration', $this->object->getConfiguration()
		);
	}

}
