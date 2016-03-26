<?php

namespace Pachico\Abtest\Split;

use \Mockery as m;

/**
 * 
 */
class RedisArrayProbabilityTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var RedisArrayProbability
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new RedisArrayProbability('foo', RedisArrayProbability::DEFAULT_REDIS_PREFIX_KEY);
	}

	/**
	 * @covers Pachico\Abtest\Split\RedisArrayProbability::__construct
	 */
	public function test__construct()
	{
		$this->object = new RedisArrayProbability('foo', RedisArrayProbability::DEFAULT_REDIS_PREFIX_KEY);

		$this->object = new RedisArrayProbability('foo', RedisArrayProbability::DEFAULT_REDIS_PREFIX_KEY, [
			'host' => '127.0.0.1',
			'port' => 6379,
			'timeout' => 2,
			'persistent' => 'abtest',
			'db' => 0
		]);

		$mocked_redis_connection = m::mock('Pachico\Abtest\Util\RedisConnector');
		$mocked_redis_connection->shouldReceive('close')->andReturn(true);

		$this->object = new RedisArrayProbability('foo', RedisArrayProbability::DEFAULT_REDIS_PREFIX_KEY, $mocked_redis_connection);
	}

	/**
	 * @covers Pachico\Abtest\Split\RedisArrayProbability::createVersion
	 */
	public function testCreateVersion()
	{
		$mocked_redis_connection = m::mock('Pachico\Abtest\Util\RedisConnector');
		$mocked_redis_connection->shouldReceive('get')->once()->andReturn(json_encode([100, 0]));
		$mocked_redis_connection->shouldReceive('get')->once()->andReturn(json_encode([0, 100]));
		$mocked_redis_connection->shouldReceive('get')->once()->andReturn('not an array');
		$mocked_redis_connection->shouldReceive('get')->once()->andReturn(null);
		$mocked_redis_connection->shouldReceive('get')->once()->andReturn(false);
		$mocked_redis_connection->shouldReceive('close')->andReturn(true);


		$split = new RedisArrayProbability('foo', RedisArrayProbability::DEFAULT_REDIS_PREFIX_KEY, $mocked_redis_connection);

		$this->assertSame(0, $split->createVersion());

		$this->assertSame(1, $split->createVersion());

		$this->assertSame(null, $split->createVersion());
		$this->assertSame(null, $split->createVersion());
		$this->assertSame(null, $split->createVersion());
	}

}
