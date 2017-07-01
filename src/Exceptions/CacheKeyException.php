<?php

namespace duncan3dc\Sonos\Exceptions;

use Psr\SimpleCache\InvalidArgumentException;

class CacheKeyException extends SonosException implements InvalidArgumentException
{
}
