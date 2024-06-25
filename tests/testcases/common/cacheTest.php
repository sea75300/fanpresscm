<?php

require_once dirname(dirname(dirname(__DIR__))) .'/inc/common.php';

class cacheTest extends \PHPUnit\Framework\TestCase {

    const CN = 'unit/test';

    const CNV = 'Abc!123';

    private $cache;

    protected function setUp() : void
    {
        parent::setUp();
        $this->cache = new \fpcm\classes\cache();
    }

    public function testWrite()
    {
        $r = $this->cache->write(self::CN, self::CNV, 3600);
        $this->assertTrue($r);
    }

    public function testIsExpired()
    {
        $r = $this->cache->isExpired(self::CN);
        $this->assertFalse($r);
    }

    public function testRead()
    {
        $r1 = $this->cache->read(self::CN);
        $this->assertNotEmpty($r1);
        $this->assertTrue($r1 === self::CNV);
    }

    public function testGetSize()
    {
        $r = $this->cache->getSize();
        $this->assertGreaterThan(0, $r);
    }

    public function testCleanup()
    {
        $r = $this->cache->cleanup(self::CN);
        $this->assertTrue($r);
    }

}
