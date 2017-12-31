<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class categoryListTest extends testBase {

    public function setUp() {
        $this->className = 'categories\\categoryList';
        parent::setUp();
    }

    public function testGetCategoriesAll() {
        
        $this->createCategory();

        $data = $this->object->getCategoriesAll();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $GLOBALS['categoryObj'] \fpcm\model\categories\category */
        $GLOBALS['categoryObj'] = $data[$GLOBALS['objectId']];

        $this->assertInstanceOf('\\fpcm\\model\\categories\\category', $GLOBALS['categoryObj']);
        $this->assertEquals($GLOBALS['categoryGroups'], $GLOBALS['categoryObj']->getGroups());
        $this->assertEquals($GLOBALS['categoryName'], $GLOBALS['categoryObj']->getName());
        $this->assertEquals($GLOBALS['categoryIcon'], $GLOBALS['categoryObj']->getIconPath());

    }

    public function testGetCategoriesNameListAll() {

        $data = $this->object->getCategoriesNameListAll();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));
        $this->assertTrue(isset($data[$GLOBALS['categoryObj']->getName()]));
        $this->assertEquals($GLOBALS['categoryObj']->getId(), $data[$GLOBALS['categoryObj']->getName()]);

    }

    public function testGetCategoriesByGroup() {

        $data = $this->object->getCategoriesByGroup(1);

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $GLOBALS['categoryObj'] \fpcm\model\categories\category */
        $GLOBALS['categoryObj'] = $data[$GLOBALS['objectId']];
        $this->assertInstanceOf('\\fpcm\\model\\categories\\category', $GLOBALS['categoryObj']);
        $this->assertEquals($GLOBALS['categoryGroups'], $GLOBALS['categoryObj']->getGroups());
        $this->assertEquals($GLOBALS['categoryName'], $GLOBALS['categoryObj']->getName());
        $this->assertEquals($GLOBALS['categoryIcon'], $GLOBALS['categoryObj']->getIconPath());

    }

    public function testGetCategoriesCurrentUser() {

        $data = $this->object->getCategoriesCurrentUser();
        $this->assertTrue(is_array($data));
        $this->assertEquals(0, count($data));
    }

    public function testGetCategoriesNameListCurrent() {

        $data = $this->object->getCategoriesNameListCurrent();
        $this->assertTrue(is_array($data));
        $this->assertEquals(0, count($data));
    }

    public function testCategorieExists() {

        $result = $this->object->categorieExists($GLOBALS['categoryName']);
        $this->assertTrue($result);
        
        
        $result = $GLOBALS['categoryObj']->delete();
        $this->assertTrue($result);
    }
    
    private function createCategory() {

        /* @var $GLOBALS['categoryObj'] \fpcm\model\categories\category */
        $GLOBALS['categoryObj'] = new \fpcm\model\categories\category();

        $GLOBALS['categoryName']   = 'UnitTest category '.microtime(true);
        $GLOBALS['categoryIcon']   = 'icon.pjg';
        $GLOBALS['categoryGroups'] = '1;2;3';
        
        $GLOBALS['categoryObj']->setName($GLOBALS['categoryName']);
        $GLOBALS['categoryObj']->setGroups($GLOBALS['categoryGroups']);
        $GLOBALS['categoryObj']->setIconPath($GLOBALS['categoryIcon']);
        $result = $GLOBALS['categoryObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);
        
        $GLOBALS['objectId'] = $GLOBALS['categoryObj']->getId();

    }
}