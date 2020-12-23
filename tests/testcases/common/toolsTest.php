<?php

require_once dirname(dirname(dirname(__DIR__))) .'/inc/common.php';

class toolsTest extends \PHPUnit\Framework\TestCase {

    protected function setUp() : void
    {
        parent::setUp();
    }

    public function testValidateDate()
    {
        $this->assertTrue( \fpcm\classes\tools::validateDateString('2020-01-01') );
        $this->assertTrue( \fpcm\classes\tools::validateDateString('2020-02-29') );
        $this->assertTrue( \fpcm\classes\tools::validateDateString('2020-12-31') );

        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-00-32') );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-01-00') );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-01-32') );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-13-31') );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('20-01-01') );
        
    }

    public function testValidateDateTime()
    {
        $this->assertTrue( \fpcm\classes\tools::validateDateString('2020-01-01 00:00', true) );
        $this->assertTrue( \fpcm\classes\tools::validateDateString('2020-01-01 23:59', true) );
        $this->assertTrue( \fpcm\classes\tools::validateDateString('2020-02-29 00:00', true) );
        $this->assertTrue( \fpcm\classes\tools::validateDateString('2020-12-31 23:59', true) );

        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-00-32 00:00', true) );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-01-00 23:59', true) );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-01-32 00:00', true) );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('2020-13-31 23:59', true) );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('20-01-01 00:00', true) );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('20-01-31 23:59', true) );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('20-01-31 24:00', true) );
        $this->assertFalse( \fpcm\classes\tools::validateDateString('20-01-31 -01:00', true) );
        
    }

}
