<?php

namespace duncan3dc\SonosTests\Devices;

use duncan3dc\Sonos\Devices\Discovery;
use duncan3dc\Sonos\Interfaces\Devices\FactoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class DiscoveryTest extends TestCase
{
    private $devices;

    public function setUp()
    {
        $factory = Mockery::mock(FactoryInterface::class);
        $this->devices = new Discovery($factory);
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


    public function testGetMulticastAddress()
    {
        $this->assertSame("239.255.255.250", $this->devices->getMulticastAddress());
    }


    public function testSetMulticastAddress()
    {
        $this->assertSame($this->devices, $this->devices->setMulticastAddress("127.0.0.1"));
        $this->assertSame("127.0.0.1", $this->devices->getMulticastAddress());
    }
}
