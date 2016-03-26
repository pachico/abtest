<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Memory;

use \Pachico\Abtest\Test,
	\Pachico\Abtest\Split;

/**
 *
 * Memory classes must implement this interface
 */
interface MemoryInterface
{

	/**
	 * 
	 * @param \Pachico\Abtest\Test\Test $test
	 * @param \Pachico\Abtest\Split\SplitInterface $split
	 */
	public function getVersion(Test\Test $test, Split\SplitInterface $split);

	/**
	 * return boolean;
	 */
	public function save();
}
