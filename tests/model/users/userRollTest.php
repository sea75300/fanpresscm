<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class userRollTest extends testBase {

    public function setUp() {
        $this->className = 'users\\userRoll';
        parent::setUp();
    }

    public function testSave() {

        $GLOBALS['rollName']  = 'fpcmTestRoll'.microtime(true);
        
        /* @var $object fpcm\model\users\userRoll */
        $object = $this->object;
        $object->setRollName($GLOBALS['rollName']);

        $result = $object->save();
        $this->assertGreaterThanOrEqual(4, $result);
        
        $GLOBALS['objectId'] = $object->getId();
    }

    public function testUpdate() {
        
        /* @var $object \fpcm\model\users\userRoll */
        $object = $this->object;

        $GLOBALS['rollName'] .= '-UPDATED';
        
        $object->setRollName($GLOBALS['rollName']);

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetUserRoll() {
        
        /* @var $object \fpcm\model\users\userRoll */
        $object = new fpcm\model\users\userRoll($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['rollName'], $object->getRollName());
    }

    public function testDelete() {

        /* @var $object \fpcm\model\users\userRoll */
        $object = new fpcm\model\users\userRoll($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = null;
        
    }

}
