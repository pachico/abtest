<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Tracking;

/**
 * Google Analytics tracking
 * @see https://developers.google.com/analytics/solutions/experiments
 * Test objects require a tracking id, which must be set in GA 
 * experiments tool.
 * Track will retrieve js code to print in web page.
 */
class GoogleExperiments implements TrackingInterface
{

	protected $_include_google_js_source = false;

	/**
	 * 
	 * @param bool $include_google_js_source
	 */
	public function __construct($include_google_js_source = false)
	{
		$this->_include_google_js_source = true === $include_google_js_source;
	}

	/**
	 * 
	 * @param array $tests
	 * @return string
	 */
	public function track(array $tests = [])
	{
		$tracking_code = [];

		if (true === $this->_include_google_js_source)
		{
			$tracking_code[] = '<script src="//www.google-analytics.com/cx/api.js"></script>';
		}

		foreach ($tests as $test)
		{
			/* @var $test \Pachico\Abtest\Test\Test */
			if (false !== $test->getVersion())
			{
				$tracking_code[] = "<script>cxApi.setChosenVariation(" . $test->getVersion() . ", '" . $test->getTrackingId() . "');</script>";
			}
		}

		return implode("\n", $tracking_code);
	}

}
