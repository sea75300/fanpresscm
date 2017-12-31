<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class textsTest extends testBase {

    public function setUp() {
        $this->className = 'wordban\\items';
        parent::setUp();
    }

    public function testGetItems() {
        
        $this->createTextItem();

        $data = $this->object->getItems();
        
        $count = count($data);        
        if ($count == 0) {
            $this->markTestSkipped('No wordband items available in db');
        }
        
        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testDeleteItems() {

        $result = $this->object->deleteItems([$GLOBALS['objectId']]);
        $this->assertTrue($result);
        
        $data = $this->object->getItems();
        $this->assertFalse(isset($data[$GLOBALS['objectId']]));
    }
    
    private function createTextItem() {

        $object = new fpcm\model\wordban\item();

        $object->setSearchtext('UnitTestSearch ABC');
        $object->setReplacementtext('UnitTestReplace DEF');
        $object->setReplaceTxt(1);
        $object->setLockArticle(1);
        $object->setCommentApproval(1);

        $result = $object->save();
        $this->assertTrue($result);
        
        $GLOBALS['objectId'] = $object->getId();
    }

}