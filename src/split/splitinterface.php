<?php

namespace Pachico\Abtest\Split;

/**
 *
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 */
interface SplitInterface
{

	/**
	 * return int
	 */
	public function createVersion();
}
