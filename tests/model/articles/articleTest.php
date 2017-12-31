<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class articleTest extends testBase {

    public function setUp() {
        $this->className = 'articles\\article';
        parent::setUp();
    }

    public function testSave() {
        
        /* @var $object \fpcm\model\articles\article */
        $object = $this->object;

        $GLOBALS['article_title']   = 'FPCM UnitTest Article '.microtime(true);
        $GLOBALS['article_content'] =  'FPCM UnitTest Article from https://nobody-knows.org!';
        $GLOBALS['article_created'] = time();
        
        $object->setTitle($GLOBALS['article_title']);
        $object->setContent($GLOBALS['article_content']);
        $object->setCreatetime($GLOBALS['article_created']);
        $object->setCreateuser(1);
        $object->setPinned(1);
        $object->setComments(1);
        $object->setSources('https://nobody-knows.org');
        $object->setCategories(array(1));

        $result = $object->save();
        $this->assertGreaterThanOrEqual(1, $result);
        
        $GLOBALS['objectId'] = $result;
    }

    public function testUpdate() {
        
        /* @var $object \fpcm\model\articles\article */
        $object = $this->object;

        $object->setChangeuser(time());
        $object->setChangeuser(1);
        $object->setCategories(array(1));

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetArticle() {
        
        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals($GLOBALS['article_content'], $object->getContent());
        $this->assertEquals(1, $object->getPinned());
        $this->assertEquals(1, $object->getComments());
        $this->assertEquals(1, $object->getCreateuser());
    }

    public function testCreateRevision() {

        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['objectId']);
        $result = $object->createRevision(time());

        $this->assertTrue($result);
    }

    public function testGetRevisions() {

        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['objectId']);
        $revisions = $object->getRevisions();

        $this->assertTrue(is_array($revisions));
        $this->assertGreaterThanOrEqual(1, count($revisions));
    }

    public function testDelete() {

        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['objectId']);

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
