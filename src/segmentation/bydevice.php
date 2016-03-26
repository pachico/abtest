<?php

namespace Pachico\Abtest\Segmentation;

use \Pachico\Abtest\Util;

/**
 *
 */
class ByDevice implements SegmentatIoninterface
{

	const DEVICE_DESKTOP = 'desktop';
	const DEVICE_MOBILE = 'mobile';
	const DEVICE_TABLET = 'tablet';
	const DEVICE_NOT_MOBILE = 'not_mobile';
	const DEVICE_NOT_DESKTOP = 'not_desktop';

	/**
	 *
	 * @var Util\MobileDetection
	 */
	protected $_mobile_detection;

	/**
	 *
	 * @var string
	 */
	protected $_device_type;

	/**
	 *
	 * @var string
	 */
	protected $_user_agent;

	/**
	 *
	 * @param string $device_type
	 * @param \Pachico\Abtest\Util\MobileDetection $mobile_detection
	 * @param string $user_agent
	 */
	public function __construct($device_type, Util\MobileDetection $mobile_detection = null, $user_agent = null)
	{
		$this->_device_type = $device_type;

		$this->_user_agent = (string) $user_agent;

		$this->_mobile_detection = $mobile_detection ?
			: new Util\MobileDetection(null, $user_agent);
	}

	/**
	 *
	 * @return boolean
	 */
	public function isParticipant()
	{
		switch ($this->_device_type)
		{
			case static::DEVICE_DESKTOP:
				return (!$this->_mobile_detection->isMobile() && !$this->_mobile_detection->isTablet());
			case static::DEVICE_MOBILE:
				return $this->_mobile_detection->isMobile();
			case static::DEVICE_TABLET:
				return $this->_mobile_detection->isTablet();
			case static::DEVICE_NOT_DESKTOP:
				return $this->_mobile_detection->isMobile() || $this->_mobile_detection->isTablet();
			case static::DEVICE_NOT_MOBILE:
				return !$this->_mobile_detection->isMobile();
		}

		return false;
	}

}
