<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class shareTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'shares\\share';
        parent::setUp();
    }

    public function testSave()
    {
        /* @var $GLOBALS['article'] \fpcm\model\shares\share */
        $GLOBALS['article'] = new \fpcm\model\articles\article();
        $GLOBALS['article']->setTitle('FPCM UnitTest Article ' . microtime(true));
        $GLOBALS['article']->setContent('FPCM UnitTest Article from https://nobody-knows.org!');
        $GLOBALS['article']->setCreatetime(time());
        $GLOBALS['article']->setCreateuser(1);
        $GLOBALS['article']->setPinned(1);
        $GLOBALS['article']->setComments(1);
        $GLOBALS['article']->setSources('https://nobody-knows.org');
        $GLOBALS['article']->setCategories([1]);
        $GLOBALS['articleId'] = $GLOBALS['article']->save();
        $this->assertGreaterThanOrEqual(1, $GLOBALS['articleId']);

        $this->object->setArticleId($GLOBALS['articleId']);
        $this->object->setLastshare(time());
        $this->object->setSharecount(5);
        $this->object->setShareitem('twitter');

        $GLOBALS['objectId'] = $this->object->save();
        $this->assertGreaterThanOrEqual(1, $GLOBALS['objectId']);
    }

    public function testUpdate()
    {
        $this->object = new \fpcm\model\shares\share($GLOBALS['objectId']);
        $this->object->increase();
        $this->object->setLastshare(time());
        $this->assertTrue($this->object->update());
    }

    public function testGetShares()
    {
        $GLOBALS['sharesObj'] = new \fpcm\model\shares\shares();
        
        $objects = $GLOBALS['sharesObj']->getByArticleId($GLOBALS['articleId']);
        $this->assertTrue(is_array($objects));
        $this->assertArrayHasKey('twitter', $objects);
        $this->assertEquals(6, $objects['twitter']->getSharecount());
    }

    public function testGetRegisteredShares()
    {
        $this->assertNotEmpty($GLOBALS['sharesObj']->getRegisteredShares('twitter'));
    }

    public function testDelete()
    {
        $this->assertTrue($this->object->delete());
        (new \fpcm\model\articles\article($GLOBALS['articleId']))->delete();
    }

}
