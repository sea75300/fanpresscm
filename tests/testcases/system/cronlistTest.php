<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class cronlistTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'crons\\cronlist';
        parent::setUp();
    }

    public function testGetExecutableCrons()
    {

        /* @var $object fpcm\model\crons\cronlist */
        $object = $this->object;
        
        $crons = $object->getExecutableCrons();
        
        $this->assertTrue(is_array($crons));
        $this->assertGreaterThanOrEqual(0, count($crons));
        
        foreach ($crons as $value) {
            $this->assertInstanceOf('fpcm\model\abstracts\cron', $value);
        }

    }

    public function testGetAllCrons()
    {

        /* @var $object fpcm\model\crons\cronlist */
        $object = $this->object;
        
        $crons = $object->getAllCrons();
        
        $this->assertTrue(is_array($crons));
        $this->assertGreaterThanOrEqual(10, count($crons));
        
        foreach ($crons as $value) {
            $this->assertInstanceOf('fpcm\model\abstracts\cron', $value);
        }

    }


}
