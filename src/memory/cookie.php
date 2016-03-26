<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Memory;

use Pachico\Abtest\Test,
	Pachico\Abtest\Split,
	Pachico\Abtest\Util;

/**
 * To store variations in cookies
 */
class Cookie implements MemoryInterface
{

	/**
	 * The name of the cookie the variations are stored into
	 */
	const DEFAULT_COOKIE_NAME = 'abtests';

	/**
	 *
	 * @var Util\Cookie 
	 */
	protected $_cookie_handler;

	/**
	 *
	 * @var string
	 */
	protected $_cookie_name;

	/**
	 *
	 * @var int In seconds
	 */
	protected $_ttl;

	/**
	 *
	 * @var array All tests variation
	 */
	protected $_versions = [];

	/**
	 * 
	 * @param string $cookie_name
	 * @param int $ttl
	 * @param \Pachico\Abtest\Util\Cookie $cookie_handler
	 */
	public function __construct($cookie_name, $ttl = null, Util\Cookie $cookie_handler = null)
	{
		$this->_cookie_name = (string) $cookie_name;

		$this->_cookie_handler = $cookie_handler ?
			: new Util\Cookie($this->_cookie_name);

		$this->_versions = $this->_getPreviousVersions();

		$this->_ttl = is_numeric($ttl)
			? (int) $ttl
			: null;
	}

	/**
	 * 
	 * @param Util\Cookie $cookie_handler
	 * @return \Pachico\Abtest\Memory\Cookie
	 */
	public function setCookieHandler($cookie_handler)
	{
		$this->_cookie_handler = $cookie_handler;
		return $this;
	}

	/**
	 * 
	 * @param \Pachico\Abtest\Test\Test $test
	 * @param \Pachico\Abtest\Split\SplitInterface $split
	 * @return int
	 */
	public function getVersion(Test\Test $test, Split\SplitInterface $split)
	{

		if (isset($this->_versions[$test->getName()]))
		{
			return $this->_versions[$test->getName()];
		}

		$created_version = $split->createVersion();

		$this->_versions[$test->getName()] = $created_version;

		return $this->_versions[$test->getName()];
	}

	/**
	 *
	 * @return array
	 */
	protected function _getPreviousVersions()
	{
		$raw_cookie_value = $this->_cookie_handler->get();

		$deserialized = @json_decode($raw_cookie_value, true);

		if (empty($deserialized))
		{
			return [];
		}

		return $deserialized;
	}

	/**
	 * 
	 * @return boolean
	 * @throws \RuntimeException
	 */
	public function save()
	{
		return $this->_cookie_handler->save(json_encode($this->_versions), $this->_ttl);
	}

}
