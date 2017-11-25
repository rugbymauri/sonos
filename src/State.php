<?php

namespace duncan3dc\Sonos;

use duncan3dc\DomParser\XmlElement;
use duncan3dc\Sonos\Interfaces\ControllerInterface;
use duncan3dc\Sonos\Interfaces\StateInterface;
use duncan3dc\Sonos\Interfaces\TrackInterface;
use duncan3dc\Sonos\Tracks\Stream;
use duncan3dc\Sonos\Tracks\Track;
use duncan3dc\Sonos\Utils\Time;

/**
 * Representation of the current state of a controller.
 */
class State extends Track implements StateInterface
{
    /**
     * @var string|null $stream The name of the stream currently currently playing (or null if we are not on a stream).
     */
    private $stream;

    /**
     * @var Time $duration The duration of the currently active track.
     */
    private $duration;

    /**
     * @var Time $position The position of the currently active track.
     */
    private $position;


    /**
     * Update the track properties using an xml element.
     *
     * @param XmlElement $xml The xml element representing the track meta data.
     * @param ControllerInterface $controller A controller instance on the playlist's network
     *
     * @return self
     */
    public static function createFromXml(XmlElement $xml, ControllerInterface $controller): TrackInterface
    {
        $track = parent::createFromXml($xml, $controller);

        return $track;
    }


    /**
     * Create a new instance.
     *
     * @param string $uri The URI of the track
     */
    public function __construct(string $uri = "")
    {
        parent::__construct($uri);

        $this->duration = Time::inSeconds(0);
        $this->position = Time::start();
    }


    /**
     * Check if we are currently playing a stream.
     *
     * @return bool
     */
    public function isStreaming(): bool
    {
        return (bool) $this->stream;
    }


    /**
     * Set the stream object in use.
     *
     * @param Stream $stream The stream
     *
     * @return StateInterface
     */
    public function setStream(Stream $stream): StateInterface
    {
        $this->stream = $stream;

        return $this;
    }


    /**
     * Get the stream object in use (or null if we are not on a stream).
     *
     * @return TrackInterface|null
     */
    public function getStream()
    {
        return $this->stream;
    }


    /**
     * Set the duration of the currently active track.
     *
     * @param Time $duration The duration
     *
     * @return StateInterface
     */
    public function setDuration(Time $duration): StateInterface
    {
        $this->duration = $duration;
        return $this;
    }


    /**
     * Get the duration of the currently active track.
     *
     * @return Time
     */
    public function getDuration(): Time
    {
        return $this->duration;
    }


    /**
     * Set the position of the currently active track.
     *
     * @param Time $position The position
     *
     * @return StateInterface
     */
    public function setPosition(Time $position): StateInterface
    {
        $this->position = $position;
        return $this;
    }


    /**
     * Get the position of the currently active track.
     *
     * @return Time
     */
    public function getPosition(): Time
    {
        return $this->position;
    }
}
