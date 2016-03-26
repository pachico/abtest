<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Split;

use \Pachico\Abtest\Util,
	Pachico\Abtest\Exception;

/**
 * As ArrayProbability but, rather than the split being passed
 * in constructor, it fetches them from Redis.
 * 
 * Particularily helpful if you want to modify split without having
 * to do releases
 */
class RedisArrayProbability implements SplitInterface
{

	/**
	 * String prefix used by default in Redis
	 */
	const DEFAULT_REDIS_PREFIX_KEY = 'ABTEST:';

	/**
	 *
	 * @var Util\RedisConnector
	 */
	protected $_redis_connection;

	/**
	 *
	 * @var string
	 */
	protected $_redis_abtest_key;

	/**
	 *
	 * @var string
	 */
	protected $_redis_prefix_key;

	/**
	 * 
	 * @param string $abtest_redis_key
	 * @param string $redis_prefix_key
	 * @param mixed array|\Pachico\Abtest\Util\RedisConnector $redis_connection
	 */
	public function __construct($abtest_redis_key, $redis_prefix_key = null, $redis_connection = null)
	{
		$this->_redis_abtest_key = (string) $abtest_redis_key;

		$this->_redis_prefix_key = $redis_prefix_key?
			: static::DEFAULT_REDIS_PREFIX_KEY;

		// Redis connection has been passed, then register id
		if ($redis_connection instanceof Util\RedisConnector)
		{
			$this->_redis_connection = $redis_connection;
		}

		if (!$this->_redis_connection && is_array($redis_connection))
		{
			
			
			$this->_redis_connection = new Util\RedisConnector(
				//Is host set?
				isset($redis_connection['host'])
					? $redis_connection['host']
					: null,
				// Is port set
				isset($redis_connection['port'])
					? $redis_connection['port']
					: 6379,
				// Is timout set?
				isset($redis_connection['timeout'])
					? $redis_connection['timeout']
					: null,
				// Is persistency set?
				isset($redis_connection['persistent'])
					? $redis_connection['persistent']
					: null,
				// Is db number set?
				isset($redis_connection['db'])
					? isset($redis_connection['db'])
					: null);
		}

		if (!$this->_redis_connection)
		{
			$this->_redis_connection = new Util\RedisConnector('127.0.0.1');
		}
	}

	/**
	 * 
	 * @return int
	 */
	public function createVersion()
	{

		$row_data_redis = $this->_redis_connection->get($this->_redis_prefix_key . $this->_redis_abtest_key);

		$split_array = @json_decode($row_data_redis);

		if (empty($split_array))
		{
			return null;
		}

		$arrayprobability = new ArrayProbability($split_array);

		return $arrayprobability->createVersion();
	}

}
