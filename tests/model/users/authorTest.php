<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class authorTest extends testBase {

    public function setUp() {
        $this->className = 'users\\author';
        parent::setUp();
    }

    public function testSave() {

        $GLOBALS['userName']  = 'fpcmTestUser'.microtime(true);
        $GLOBALS['userEmail'] = 'test@nobody-knows.org';
        
        /* @var $object fpcm\model\users\author */
        $object = $this->object;
        $object->setDisplayName($GLOBALS['userName']);
        $object->setUserName($GLOBALS['userName']);
        $object->setEmail($GLOBALS['userEmail']);
        $object->setPassword('fpcmTest2017');
        $object->setRegistertime(time());
        $object->setRoll(3);
        $object->setDisabled(0);
        $object->setUserMeta([]);
        $object->setUsrinfo($GLOBALS['userName']);

        $result = $object->save();
        $this->assertTrue($result);
        
        $GLOBALS['objectId'] = $object->getId();
    }

    public function testUpdate() {
        
        /* @var $object \fpcm\model\users\author */
        $object = $this->object;

        $object->setDisabled(1);
        $object->setEmail($GLOBALS['userEmail']);
        $object->setPassword('fpcmTest2017');
        $object->setUserMeta([]);

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetUser() {
        
        /* @var $object \fpcm\model\users\author */
        $object = new fpcm\model\users\author($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['userName'], $object->getUsername());
        $this->assertEquals($GLOBALS['userName'], $object->getDisplayname());
        $this->assertEquals($GLOBALS['userEmail'], $object->getEmail());
        $this->assertEquals($GLOBALS['userName'], $object->getUsrinfo());
        $this->assertEquals(1, $object->getDisabled());
        $this->assertEquals(3, $object->getRoll());
    }
    
    public function getAuthorImage() {

        /* @var $object \fpcm\model\users\author */
        $object = new fpcm\model\users\author($GLOBALS['objectId']);

        $img = \fpcm\model\users\author::getAuthorImageDataOrPath($object);
        $this->assertEmpty($img);

    }

    public function testDelete() {

        /* @var $object \fpcm\model\users\author */
        $object = new fpcm\model\users\author($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = null;
        
    }

}
