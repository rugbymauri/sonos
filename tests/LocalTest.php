<?php

namespace duncan3dc\SonosTests;

use Doctrine\Common\Cache\ArrayCache;
use duncan3dc\ObjectIntruder\Intruder;
use duncan3dc\Sonos\Device;
use duncan3dc\Sonos\Network;
use duncan3dc\Sonos\Speaker;
use duncan3dc\Sonos\Tracks\Track;
use PHPUnit\Framework\TestCase;

class LocalTest extends TestCase
{
    protected $network;
    protected $controller;
    protected $queue;
    protected $state;


    public function setUp()
    {
        if (!$_ENV["SONOS_LOCAL_TESTS"]) {
            $this->markTestSkipped("Ignoring local tests");
            return;
        }

        $cache = new ArrayCache;

        $this->network = new Network($cache);

        $network = new Intruder($this->network);
        $network->speakers = [
            new Speaker(new Device("localhost", $cache)),
        ];

        $this->controller = $this->network->getController();
        $this->queue = $this->controller->getQueue();
    }


    public function testAddTrack()
    {
        $uri = "x-file-cifs://TEST/music/artist/album/01-Song.mp3";
        $this->queue->addTrack($uri);

        $tracks = $this->queue->getTracks();

        $this->assertSame(1, count($tracks));

        $track = $tracks[0];
        $this->assertInstanceOf(Track::class, $track);
        $this->assertSame($uri, $track->uri);
    }


    public function testAddTracks()
    {
        $uris = [
            "x-file-cifs://TEST/music/artist/album/01-Song.mp3",
            "x-file-cifs://TEST/music/artist/album/02-Song.mp3",
        ];
        $this->queue->addTracks($uris);

        $tracks = $this->queue->getTracks();

        $this->assertSame(2, count($tracks));

        $this->assertContainsOnlyInstancesOf(Track::class, $tracks);
        foreach ($tracks as $key => $track) {
            $this->assertSame($uris[$key], $track->uri);
        }
    }


    public function testRemoveTracks()
    {
        $uris = [
            "x-file-cifs://TEST/music/artist/album/01-Song.mp3",
            "x-file-cifs://TEST/music/artist/album/02-Song.mp3",
        ];
        $this->queue->addTracks($uris);

        $this->queue->removeTrack(0);

        $tracks = $this->queue->getTracks();

        $this->assertSame(1, count($tracks));

        $track = $tracks[0];
        $this->assertInstanceOf(Track::class, $track);
        $this->assertSame($uris[1], $track->uri);
    }


    public function testClear()
    {
        $uris = [
            "x-file-cifs://TEST/music/artist/album/01-Song.mp3",
            "x-file-cifs://TEST/music/artist/album/02-Song.mp3",
        ];
        $this->queue->addTracks($uris);

        $this->queue->clear();

        $tracks = $this->queue->getTracks();

        $this->assertSame(0, count($tracks));
    }
}
