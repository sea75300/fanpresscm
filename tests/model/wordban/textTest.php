<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class textTest extends testBase {

    public function setUp() {
        $this->className = 'wordban\\item';
        parent::setUp();
    }

    public function testSave() {

        /* @var $object fpcm\model\wordban\item */
        $object = $this->object;

        $GLOBALS['text_search']   = 'UnitTestSearch ABC';
        $GLOBALS['text_replace'] =  'UnitTestReplace DEF';
        
        $object->setSearchtext($GLOBALS['text_search']);
        $object->setReplacementtext($GLOBALS['text_replace']);
        $object->setReplaceTxt(1);
        $object->setLockArticle(1);
        $object->setCommentApproval(1);

        $result = $object->save();
        $this->assertTrue($result);
        
        $GLOBALS['objectId'] = $object->getId();
    }

    public function testUpdate() {
        
        /* @var $object \fpcm\model\wordban\item */
        $object = $this->object;

        $object->setCommentApproval(0);

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetItem() {
        
        /* @var $object \fpcm\model\wordban\item */
        $object = new fpcm\model\wordban\item($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['text_search'], $object->getSearchtext());
        $this->assertEquals($GLOBALS['text_replace'], $object->getReplacementtext());
        $this->assertEquals(1, $object->getReplaceTxt());
        $this->assertEquals(1, $object->getLockArticle());
        $this->assertEquals(0, $object->getCommentApproval());
    }

    public function testReplacement() {
        
        $this->createArticle();

        $this->assertContains($GLOBALS['text_replace'], $GLOBALS['articleObj']->getTitle());
        $this->assertContains($GLOBALS['text_replace'], $GLOBALS['articleObj']->getContent());
        $this->assertEquals(1, $GLOBALS['articleObj']->getApproval());

        $GLOBALS['articleObj']->delete();
        
    }

    public function testDelete() {

        /* @var $object \fpcm\model\wordban\item */
        $object = new fpcm\model\wordban\item($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = null;
        
    }

    private function createArticle() {

        /* @var $GLOBALS['articleObj'] \fpcm\model\articles\article */
        $GLOBALS['articleObj'] = new \fpcm\model\articles\article();
        
        $GLOBALS['articleObj']->setTitle('FPCM UnitTest Article '.microtime(true).' '.$GLOBALS['text_search']);
        $GLOBALS['articleObj']->setContent('FPCM UnitTest Article from https://nobody-knows.org!'.PHP_EOL.$GLOBALS['text_search']);
        $GLOBALS['articleObj']->setCreatetime(time());
        $GLOBALS['articleObj']->setCreateuser(1);
        $GLOBALS['articleObj']->setCategories([1]);

        $result = $GLOBALS['articleObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);

    }

}
