<?php

namespace Pachico\Abtest\Tracking;

/**
 *
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 */
interface TrackingInterface
{

    public function track(array $tests = []);
}
