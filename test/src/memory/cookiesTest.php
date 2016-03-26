<?php

namespace Pachico\Abtest\Memory;

use \Pachico\Abtest\Util,
	\Pachico\Abtest\Split,
	\Mockery as m;

class CookieTest extends \PHPUnit_Framework_TestCase
{

	const COOKIE_NAME = 'abtest_cookie';

	/**
	 * @var Cookie
	 */
	protected $object;

	/**
	 * 
	 */
	protected function setUp()
	{
		$this->object = new Cookie(static::COOKIE_NAME, -1);
	}

	/**
	 * 
	 */
	protected function tearDown()
	{
		parent::tearDown();
		$this->object = null;
	}

	/**
	 * 
	 * @return Pachico\Abtest\Util\Cookie
	 */
	protected function _getMockedCookieHandler()
	{
		return m::mock('Pachico\Abtest\Util\Cookie');
	}

	/**
	 * 
	 * @return Pachico\Abtest\Split\SplitInterface
	 */
	protected function _getMockedSplit()
	{
		return m::mock('Pachico\Abtest\Split\SplitInterface');
	}

	/**
	 * 
	 * @return Pachico\Abtest\Test\Test
	 */
	protected function _getMockedTest()
	{
		return m::mock('Pachico\Abtest\Test\Test');
	}

	/**
	 * @covers Pachico\Abtest\Memory\Cookie::__construct
	 */
	public function test__construct()
	{
		$this->object = $this->object = new Cookie(static::COOKIE_NAME, -1);

		$this->assertInstanceOf(
			'Pachico\Abtest\Memory\Cookie', $this->object
		);

		$this->object = new Cookie(static::COOKIE_NAME, -1, new Util\Cookie(static::COOKIE_NAME));

		$this->assertInstanceOf(
			'Pachico\Abtest\Memory\Cookie', $this->object
		);
	}

	/**
	 * @covers Pachico\Abtest\Memory\Cookie::setCookieHandler
	 */
	public function testSetCookieHandler()
	{
		$this->assertInstanceOf(
			'Pachico\Abtest\Memory\Cookie', $this->object->setCookieHandler($this->_getMockedCookieHandler())
		);
	}

	/**
	 * @covers Pachico\Abtest\Memory\Cookie::getVersion
	 */
	public function testGetVersion()
	{

		$cookie_handler = $this->_getMockedCookieHandler();
		$cookie_handler->shouldReceive('get')->andReturn(null);

		$this->object->setCookieHandler($cookie_handler);

		$test = $this->_getMockedTest();
		$test->shouldReceive('getName')->andReturn('foo');

		$split = $this->_getMockedSplit();
		$split->shouldReceive('createVersion')->andReturn(1);

		$this->assertSame(
			1, $this->object->getVersion($test, $split)
		);

		$this->assertSame(
			1, $this->object->getVersion($test, $split)
		);
	}

	/**
	 * @covers Pachico\Abtest\Memory\Cookie::_getPreviousVersions
	 */
	public function test_GetPreviousVersion()
	{

		$cookie_handler = $this->_getMockedCookieHandler();
		$cookie_handler->shouldReceive('get')->andReturn(json_encode(['foo' => 1]));

		$cookie_memory = new Cookie(static::COOKIE_NAME, 100, $cookie_handler);

		$test = $this->_getMockedTest();
		$test->shouldReceive('getName')->andReturn('foo');

		$split = $this->_getMockedSplit();
		$split->shouldReceive('createVersion')->andReturn(1);

		$this->assertSame(
			1, $cookie_memory->getVersion($test, $split)
		);
	}

	/**
	 * @covers Pachico\Abtest\Memory\Cookie::save
	 */
	public function testSave()
	{

		$cookie_handler = $this->_getMockedCookieHandler();
		$cookie_handler->shouldReceive('save')->andReturn(true);

		$this->object->setCookieHandler($cookie_handler);

		$this->assertTrue($this->object->save());
	}

}
