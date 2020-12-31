<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class passCheckTest extends PHPUnit\Framework\TestCase {

    protected function setUp() : void
    {
        parent::setUp();
    }

    public function testUnsafePassword()
    {
        /* @var $conf \fpcm\model\system\config */
        $conf = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        if (!$conf->system_passcheck_enabled) {
            $this->markTestSkipped('Password check is not enabled, skip test.');
        }
        
        $obj = new \fpcm\model\users\passCheck('password');
        $this->assertTrue($obj->isPowned());
    }

    public function testSafePassword()
    {
        /* @var $conf \fpcm\model\system\config */
        $conf = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        if (!$conf->system_passcheck_enabled || !defined('FPCM_TEST_PASS')) {
            $this->markTestSkipped('Password check is not enabled, skip test.');
        }
     
        $this->assertNotEmpty(FPCM_TEST_PASS);
        
        $obj = new \fpcm\model\users\passCheck(FPCM_TEST_PASS);
        $this->assertFalse($obj->isPowned());
    }


}
