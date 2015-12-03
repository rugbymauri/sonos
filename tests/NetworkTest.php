<?php

namespace duncan3dc\SonosTests;

use duncan3dc\Sonos\Network;

class NetworkTest extends \PHPUnit_Framework_TestCase
{
    private $network;

    public function setUp()
    {
        $this->network = new Network;
    }
}
