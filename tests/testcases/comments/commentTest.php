<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class commentTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'comments\\comment';
        parent::setUp();
    }

    public function testSave()
    {

        /* @var $object \fpcm\model\comments\comment */
        $object = $this->object;

        $GLOBALS['commentName'] = 'Max Mustermann ' . microtime(true);
        $GLOBALS['commentEmail'] = 'max.mustermann' . microtime(true) . '@nobody-knows.org';
        $GLOBALS['commentWebsite'] = 'nobody-knows.org';
        $GLOBALS['commentContent'] = 'FPCM UnitTest Comment from https://nobody-knows.org!';
        $GLOBALS['commentCreated'] = time();

        $object->setName($GLOBALS['commentName']);
        $object->setText($GLOBALS['commentContent']);
        $object->setEmail($GLOBALS['commentEmail']);
        $object->setWebsite($GLOBALS['commentWebsite']);
        $object->setCreatetime($GLOBALS['commentCreated']);
        $object->setArticleid(1);
        $object->setPrivate(1);
        $object->setSpammer(1);
        $object->setApproved(1);
        $object->setIpaddress('127.0.0.1');
        $result = $object->save();
        $this->assertGreaterThanOrEqual(1, $result);

        $GLOBALS['objectId'] = $result;
    }

    public function testUpdate()
    {

        /* @var $object \fpcm\model\comments\comment */
        $object = $this->object;

        $object->setPrivate(1);
        $object->setSpammer(0);
        $object->setApproved(0);

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetComment()
    {

        /* @var $object \fpcm\model\comments\comment */
        $object = new fpcm\model\comments\comment($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['commentContent'], $object->getText());
        $this->assertEquals($GLOBALS['commentName'], $object->getName());
        $this->assertEquals($GLOBALS['commentEmail'], $object->getEmail());
        $this->assertEquals($GLOBALS['commentWebsite'], $object->getWebsite());
        $this->assertEquals(1, $object->getPrivate());
        $this->assertEquals(0, $object->getSpammer());
        $this->assertEquals(0, $object->getApproved());
        $this->assertEquals(1, $object->getArticleid());
    }
    
    public function testGetElementLink()
    {
        $obj = new fpcm\model\comments\comment($GLOBALS['objectId']);
        $this->assertStringContainsString((new \fpcm\model\system\config)->system_url  . '?module=fpcm/article&id=' . $obj->getArticleid() . '#comments', $obj->getElementLink());
    }
    
    public function testGetMetaDataStatusIcons()
    {
        $icons = (new fpcm\model\comments\comment($GLOBALS['objectId']))->getMetaDataStatusIcons();
        
        foreach ($icons as $icon) {
            $this->assertInstanceOf('\fpcm\view\helper\icon', $icon);
            $this->assertStringContainsString('<span class="fpcm-ui-icon-single fpcm-ui-editor-metainfo', (string) $icon );
        }
        
    }

    public function testDelete()
    {

        /* @var $object \fpcm\model\comments\comment */
        $object = new fpcm\model\comments\comment($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        if ($object->exists()) {
            $this->assertEquals(1, $object->getDeleted());
        } else {
            $this->assertFalse($object->exists());
        }

        $GLOBALS['objectId'] = null;
    }

}
