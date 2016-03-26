<?php

namespace Pachico\Abtest\Config;

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 */
interface ConfiguratorInterface
{

    const TESTS = 'tests';
    const TRACKING_ID = 'tracking_id';
    const SEGMENTATION = 'segmentation';
    const SPLIT = 'split';
    const MEMORY = 'memory';
    const TRACKING = 'tracking';

    public function getConfiguration();
}
