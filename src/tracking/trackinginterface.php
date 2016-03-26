<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Tracking;

/**
 *
 * Tracking classes must implement this interface, where track()
 * can output code or save depending on requirement.
 */
interface TrackingInterface
{

	public function track(array $tests = []);
}
