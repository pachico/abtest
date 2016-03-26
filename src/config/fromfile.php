<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Config;

use \Pachico\Abtest\Exception;

/**
 * Provided a file path, returns
 * a full configuration object
 */
class FromFile implements ConfiguratorInterface
{

	/**
	 *
	 * @var Configuration;
	 */
	protected $_configuration;

	/**
	 *
	 * @param string $config_file_path
	 * @throws Exception\FileNotFoundException
	 * @throws Exception\FileNotReadableException
	 */
	public function __construct($config_file_path)
	{
		if (!is_file($config_file_path) || !is_readable($config_file_path))
		{
			throw new Exception\FileNotFoundException('Config file ' . $config_file_path . ' not found or not readable.');
		}

		$config_file_content = require $config_file_path;

		$from_array_configurator = new FromArray($config_file_content);

		$this->_configuration = $from_array_configurator->getConfiguration();
	}

	/**
	 *
	 * @return Configuration
	 */
	public function getConfiguration()
	{
		return $this->_configuration;
	}

}
