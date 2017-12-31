<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class categoryTest extends testBase {

    public function setUp() {
        $this->className = 'categories\\category';
        parent::setUp();
    }

    public function testSave() {
        
        /* @var $object \fpcm\model\categories\category */
        $object = $this->object;

        $GLOBALS['categoryName']   = 'UnitTest category '.microtime(true);
        $GLOBALS['categoryIcon']   = 'icon.pjg';
        $GLOBALS['categoryGroups'] = '1;2;3';
        
        $object->setName($GLOBALS['categoryName']);
        $object->setGroups($GLOBALS['categoryGroups']);
        $object->setIconPath($GLOBALS['categoryIcon']);
        $result = $object->save();
        $this->assertGreaterThanOrEqual(1, $result);
        
        $GLOBALS['objectId'] = $object->getId();
    }

    public function testUpdate() {
        
        /* @var $object \fpcm\model\categories\category */
        $object = $this->object;

        $GLOBALS['categoryGroups'] = '1;2';
        $object->setGroups($GLOBALS['categoryGroups']);

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetcategory() {
        
        /* @var $object \fpcm\model\categories\category */
        $object = new fpcm\model\categories\category($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['categoryGroups'], $object->getGroups());
        $this->assertEquals($GLOBALS['categoryName'], $object->getName());
        $this->assertEquals($GLOBALS['categoryIcon'], $object->getIconPath());
    }

    public function testDelete() {

        /* @var $object \fpcm\model\categories\category */
        $object = new fpcm\model\categories\category($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);
        
        $object = new fpcm\model\categories\category($GLOBALS['objectId']);
        $this->assertFalse($object->exists());
        
        $GLOBALS['objectId'] = null;
        
    }

}
