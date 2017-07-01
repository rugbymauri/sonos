<?php

namespace duncan3dc\SonosTests\Utils;

use duncan3dc\Sonos\Exceptions\CacheKeyException;
use duncan3dc\Sonos\Utils\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    private $cache;


    public function setUp()
    {
        $this->cache = new Cache;
    }


    public function testGet1()
    {
        $this->assertSame(null, $this->cache->get("snarky puppy"));
    }
    public function testGet2()
    {
        $this->assertSame("shofukan", $this->cache->get("snarky puppy", "shofukan"));
    }
    public function testGet3()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid cache key: 404");
        $this->cache->get(404);
    }


    public function testSet1()
    {
        $result = $this->cache->set("snarky puppy", "culcha vulcha");
        $this->assertTrue($result);
        $this->assertSame("culcha vulcha", $this->cache->get("snarky puppy", "shofukan"));
    }
    public function testSet2()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid cache key: 404");
        $this->cache->set(404, "value");
    }


    public function testDelete1()
    {
        $this->cache->set("snarky puppy", "culcha vulcha");
        $result = $this->cache->delete("snarky puppy");
        $this->assertTrue($result);
        $this->assertFalse($this->cache->has("snarky puppy"));
    }
    public function testDelete2()
    {
        $result = $this->cache->delete("does-not-exist");
        $this->assertTrue($result);
        $this->assertFalse($this->cache->has("does-not-exist"));
    }
    public function testDelete3()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid cache key: 404");
        $this->cache->delete(404, "value");
    }


    public function testClear()
    {
        $this->cache->set("one", 1);
        $this->cache->set("two", 2);

        $this->assertTrue($this->cache->clear());

        $this->assertFalse($this->cache->has("one"));
        $this->assertFalse($this->cache->has("two"));
    }


    public function testGetMultiple1()
    {
        $this->cache->set("one", 1);
        $this->cache->set("three", 3);

        $result = $this->cache->getMultiple(["one", "two", "three"]);

        $this->assertSame([
            "one"   =>  1,
            "two"   =>  null,
            "three" =>  3,
        ], $result);
    }
    public function testGetMultiple2()
    {
        $this->cache->set("one", 1);
        $this->cache->set("two", 2);

        $result = $this->cache->getMultiple(["one", "two", "three"], "DEFAULT");

        $this->assertSame([
            "one"   =>  1,
            "two"   =>  2,
            "three" =>  "DEFAULT",
        ], $result);
    }
    public function testGetMultiple3()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid keys, must be iterable");
        $this->cache->getMultiple(new \DateTime);
    }
    public function testGetMultiple4()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid cache key: 77");
        $this->cache->getMultiple(["ok", 77]);
    }


    public function testSetMultiple1()
    {
        $result = $this->cache->setMultiple([
            "one"   =>  1,
            "three" =>  3,
        ]);
        $this->assertTrue($result);

        $this->assertSame(1, $this->cache->get("one"));
        $this->assertSame(3, $this->cache->get("three"));
    }
    public function testSetMultiple2()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid keys, must be iterable");
        $this->cache->setMultiple(new \DateTime);
    }
    public function testSetMultiple3()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid cache key: 77");
        $this->cache->setMultiple(["ok" => 1, 77 => 2]);
    }


    public function testDeleteMultiple1()
    {
        $this->cache->setMultiple([
            "one"   =>  1,
            "two"   =>  2,
            "three" =>  3,
        ]);

        $result = $this->cache->deleteMultiple(["one", "three"]);
        $this->assertTrue($result);

        $this->assertFalse($this->cache->has("one"));
        $this->assertSame(2, $this->cache->get("two"));
        $this->assertFalse($this->cache->has("three"));
    }
    public function testDeleteMultiple2()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid keys, must be iterable");
        $this->cache->DeleteMultiple(new \DateTime);
    }
    public function testDeleteMultiple3()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid cache key: 77");
        $this->cache->DeleteMultiple(["ok", 77]);
    }


    public function testHas1()
    {
        $result = $this->cache->has("no-such-key");
        $this->assertFalse($result);
    }
    public function testHas2()
    {
        $this->cache->set("haken", "affinity");
        $result = $this->cache->has("haken");
        $this->assertTrue($result);
    }
    public function testHas3()
    {
        $this->cache->set("afi", "decemberunderground");
        $this->assertTrue($this->cache->has("afi"));
        $this->cache->delete("afi");
        $result = $this->cache->has("afi");
        $this->assertFalse($result);
    }
    public function testHas4()
    {
        $this->cache->set("periphery", "clear");
        $this->assertTrue($this->cache->has("periphery"));
        $this->cache->clear();
        $result = $this->cache->has("periphery");
        $this->assertFalse($result);
    }
    public function testHas5()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid cache key: 123");
        $this->cache->has(123);
    }
}
