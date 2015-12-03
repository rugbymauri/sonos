<?php

namespace duncan3dc\SonosTests;

use duncan3dc\Sonos\DeviceCollection;

class DeviceCollectionTest extends \PHPUnit_Framework_TestCase
{
    private $devices;

    public function setUp()
    {
        $this->devices = new DeviceCollection;
    }


    public function testGetNetworkInterface()
    {
        $this->assertNull($this->devices->getNetworkInterface());
    }


    public function testSetNetworkInterfaceString()
    {
        $this->devices->setNetworkInterface("eth0");
        $this->assertSame("eth0", $this->devices->getNetworkInterface());
    }


    public function testSetNetworkInterfaceInteger()
    {
        $this->devices->setNetworkInterface(0);
        $this->assertSame(0, $this->devices->getNetworkInterface());
    }


    public function testSetNetworkInterfaceEmptyString()
    {
        $this->devices->setNetworkInterface("");
        $this->assertSame("", $this->devices->getNetworkInterface());
    }
}
