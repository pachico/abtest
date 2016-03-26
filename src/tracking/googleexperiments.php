<?php

namespace Pachico\Abtest\Tracking;

/**
 * Description of googleexperiments
 *
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 */
class GoogleExperiments implements TrackingInterface
{

    public function getTrackingCode()
    {
        return 'foo bar';
    }
}
