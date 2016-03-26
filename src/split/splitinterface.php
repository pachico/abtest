<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Split;

/**
 *
 * Split classes must implement this interface
 */
interface SplitInterface
{

	/**
	 * return int
	 */
	public function createVersion();
}
