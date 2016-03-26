<?php

namespace Pachico\Abtest\Config;

class FromFileTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var FromFile
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new FromFile(TEST_CONFIG_FOLDER . 'config1.php');
	}

	/**
	 * @covers Pachico\Abtest\Config\FromFile::getConfiguration
	 */
	public function testGetConfiguration()
	{
		$this->assertInstanceOf(
			'Pachico\Abtest\Config\Configuration', $this->object->getConfiguration()
		);
	}

	/**
	 * @covers Pachico\Abtest\Config\FromFile::__construct
	 * @expectedException Pachico\Abtest\Exception\FileNotFoundException
	 */
	public function test__constructWrongConfigFile()
	{
		$this->object = new FromFile('nonexisting_file.php');

		$this->assertNull(
			$this->object->getConfiguration()
		);
	}

}
