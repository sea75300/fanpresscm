<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class categoryTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'categories\\category';
        parent::setUp();
    }

    public function testSave()
    {

        /* @var $object \fpcm\model\categories\category */
        $object = $this->object;

        $GLOBALS['categoryName'] = 'UnitTest category ' . microtime(true);
        $GLOBALS['categoryIcon'] = 'icon.jpg';
        $GLOBALS['categoryGroups'] = '1;2;3';

        $object->setName($GLOBALS['categoryName']);
        $object->setGroups($GLOBALS['categoryGroups']);
        $object->setIconPath($GLOBALS['categoryIcon']);
        $result = $object->save();
        $this->assertGreaterThanOrEqual(1, $result);

        $GLOBALS['objectId'] = $object->getId();
        $this->assertGreaterThanOrEqual(fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY, $object->save());
    }

    public function testUpdate()
    {

        /* @var $object \fpcm\model\categories\category */
        $object = $this->object;

        $GLOBALS['categoryGroups'] = '1;2';
        $object->setGroups($GLOBALS['categoryGroups']);

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testCopy()
    {

        sleep(2);
        
        /* @var $object \fpcm\model\categories\category */
        $object = $this->object;
        $res = $object->copy();

        $this->assertGreaterThan(0, $res);
        
        $copy = new \fpcm\model\categories\category($res);
        $this->assertTrue($copy->exists());
        $this->assertStringContainsString('Kopie von', $copy->getName());
        $this->assertTrue($copy->getIconPath() === $object->getIconPath());
        $this->assertTrue($copy->getGroups() === $object->getGroups());
        $this->assertTrue($copy->delete());
    }

    public function testGetcategory()
    {

        /* @var $object \fpcm\model\categories\category */
        $object = new fpcm\model\categories\category($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['categoryGroups'], $object->getGroups());
        $this->assertEquals($GLOBALS['categoryName'], $object->getName());
        $this->assertEquals($GLOBALS['categoryIcon'], $object->getIconPath());
    }
    
    public function testGetCategoryImage()
    {
        $img = (new fpcm\model\categories\category($GLOBALS['objectId']))->getCategoryImage();
        $this->assertStringContainsString('<img src="icon.jpg" alt="'.$GLOBALS['categoryName'].'" title="'.$GLOBALS['categoryName'].'" class="fpcm-pub-category-icon">', $img);
    }

    public function testDelete()
    {

        /* @var $object \fpcm\model\categories\category */
        $object = new fpcm\model\categories\category($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        $object = new fpcm\model\categories\category($GLOBALS['objectId']);
        $this->assertFalse($object->exists());

        $GLOBALS['objectId'] = null;
    }

}
