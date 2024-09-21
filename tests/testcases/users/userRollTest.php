<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class userRollTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'users\\userRoll';
        parent::setUp();
    }

    public function testSave()
    {

        $GLOBALS['rollName'] = 'fpcmTestRoll' . microtime(true);
        $GLOBALS['rollCodex'] = 'fpcmTestRollCodex' . microtime(true);

        /* @var $object fpcm\model\users\userRoll */
        $object = $this->object;
        $object->setRollName($GLOBALS['rollName']);
        $object->setCodex($GLOBALS['rollCodex']);

        $result = $object->save();
        $this->assertGreaterThanOrEqual(4, $result);

        $GLOBALS['objectId'] = $object->getId();
    }

    public function testCopy()
    {

        sleep(2);
        
        /* @var $object fpcm\model\users\userRoll */
        $object = $this->object;
        $res = $object->copy();

        $this->assertGreaterThan(0, $res);
        
        $copy = new fpcm\model\users\userRoll($res);
        $this->assertTrue($copy->exists());
        $this->assertStringContainsString('Kopie von', $copy->getRollNameTranslated());
        $this->assertTrue($copy->getCodex() === $object->getCodex());
        $this->assertTrue($copy->delete());
    }

    public function testUpdate()
    {

        /* @var $object \fpcm\model\users\userRoll */
        $object = $this->object;

        $GLOBALS['rollName'] .= '-UPDATED';
        $GLOBALS['rollCodex'] .= '-UPDATED';

        $object->setRollName($GLOBALS['rollName']);
        $object->setCodex($GLOBALS['rollCodex']);

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetUserRoll()
    {

        /* @var $object \fpcm\model\users\userRoll */
        $object = new fpcm\model\users\userRoll($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['rollName'], $object->getRollName());
        $this->assertEquals($GLOBALS['rollCodex'], $object->getCodex());
    }

    public function testDelete()
    {

        /* @var $object \fpcm\model\users\userRoll */
        $object = new fpcm\model\users\userRoll($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = null;
    }

}
