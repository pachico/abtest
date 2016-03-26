<?php

namespace Pachico\Abtest\Memory;

use \Pachico\Abtest\Test,
	\Pachico\Abtest\Split;

/**
 *
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
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
	 * return $this;
	 */
	public function save();
}
