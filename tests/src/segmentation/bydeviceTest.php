<?php

namespace Pachico\Abtest\Segmentation;

/**
 * 
 */
class ByDeviceTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var ByDevice
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new ByDevice(ByDevice::DEVICE_DESKTOP, null, null);
	}

	/**
	 * @covers Pachico\Abtest\Segmentation\ByDevice::isParticipant
	 */
	public function testIsParticipant()
	{
		$this->object = new ByDevice(ByDevice::DEVICE_DESKTOP, null, 'Mozilla/5.0(iPhone;U;CPUiPhoneOS4_0likeMacOSX;en-us)AppleWebKit/532.9(KHTML,likeGecko)Version/4.0.5Mobile/8A293Safari/6531.22.7');
		$this->assertFalse($this->object->isParticipant());

		$this->object = new ByDevice(ByDevice::DEVICE_TABLET, null, 'Mozilla/5.0 (iPad; CPU OS 7_1 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Version/7.0 Mobile/11D167 Safari/9537.53');
		$this->assertTrue($this->object->isParticipant());
		
		$this->object = new ByDevice(ByDevice::DEVICE_MOBILE, null, 'Mozilla/5.0 (Android; Mobile; rv:24.0) Gecko/24.0 Firefox/24.0');
		$this->assertTrue($this->object->isParticipant());

		$this->object = new ByDevice(ByDevice::DEVICE_NOT_MOBILE, null, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17');
		$this->assertTrue($this->object->isParticipant());

		$this->object = new ByDevice(ByDevice::DEVICE_NOT_DESKTOP, null, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17');
		$this->assertFalse($this->object->isParticipant());
		
		$this->object = new ByDevice('nonexisting rule');
		$this->assertFalse($this->object->isParticipant());
	}

}
