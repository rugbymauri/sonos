<?php

namespace duncan3dc\Sonos\Tracks;

use duncan3dc\DomParser\XmlElement;
use duncan3dc\Sonos\Controller;
use duncan3dc\Sonos\Helper;
use duncan3dc\Sonos\Interfaces\UriInterface;

/**
 * Representation of a stream.
 */
class Stream implements UriInterface
{
    const PREFIX = "x-sonosapi-stream";

    /**
     * @var string $uri The uri of the stream.
     */
    protected $uri = "";

    /**
     * @var string $name The name of the stream.
     */
    protected $name = "";


    /**
     * Create a Stream object.
     *
     * @param string $uri The URI of the stream
     * @param string $name The name of the stream
     */
    public function __construct(string $uri, string $name = "")
    {
        $this->uri = (string) $uri;
        $this->name = (string) $name;
    }


    /**
     * Get the URI for this stream.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }


    /**
     * Get the name for this stream.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Get the metadata xml for this stream.
     *
     * @return string
     */
    public function getMetaData(): string
    {
        return Helper::createMetaDataXml("-1", "-1", [
            "dc:title"          =>  $this->getName() ?: "Stream",
            "upnp:class"        =>  "object.item.audioItem.audioBroadcast",
            "desc"              =>  [
                "_attributes"       =>  [
                    "id"        =>  "cdudn",
                    "nameSpace" =>  "urn:schemas-rinconnetworks-com:metadata-1-0/",
                ],
                "_value"            =>  "SA_RINCON65031_",
            ],
        ]);
    }


    /**
     * Create a stream from an xml element.
     *
     * @param XmlElement $xml The xml element representing the track meta data
     * @param Controller $controller A controller instance to communicate with
     *
     * @return self
     */
    public static function createFromXml(XmlElement $xml, Controller $controller): UriInterface
    {
        return new static($xml->getTag("res")->nodeValue, $xml->getTag("title")->nodeValue);
    }
}
