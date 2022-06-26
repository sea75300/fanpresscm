<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class articleTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'articles\\article';
        parent::setUp();
    }

    public function testSave()
    {

        /* @var $object \fpcm\model\articles\article */
        $object = $this->object;

        $GLOBALS['article_title'] = 'FPCM UnitTest Article ' . microtime(true);
        $GLOBALS['article_content'] = 'FPCM UnitTest Article from https://nobody-knows.org!';
        $GLOBALS['article_created'] = time();

        $object->setTitle($GLOBALS['article_title']);
        $object->setContent($GLOBALS['article_content']);
        $object->setCreatetime($GLOBALS['article_created']);
        $object->setCreateuser(1);
        $object->setPinned(1);
        $object->setComments(1);
        $object->setSources('https://nobody-knows.org');
        $object->setCategories(array(1));
        $object->setImagepath('test.jpg');

        $result = $object->save();
        $this->assertGreaterThanOrEqual(1, $result);

        $GLOBALS['objectId'] = $result;
    }

    public function testUpdate()
    {

        /* @var $object \fpcm\model\articles\article */
        $object = $this->object;

        $object->setChangeuser(time());
        $object->setChangeuser(1);
        $object->setCategories(array(1));

        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetArticle()
    {

        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals($GLOBALS['article_content'], $object->getContent());
        $this->assertEquals(1, $object->getPinned());
        $this->assertEquals(1, $object->getComments());
        $this->assertEquals(1, $object->getCreateuser());
    }

    public function testCreateRevision()
    {

        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['objectId']);
        $result = $object->createRevision(time());

        $this->assertTrue($result);
    }

    public function testGetRevisions()
    {

        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['objectId']);
        $revisions = $object->getRevisions();

        $this->assertTrue(is_array($revisions));
        $this->assertGreaterThanOrEqual(1, count($revisions));
    }
    
    public function testGetElementLink()
    {
        $link = (new fpcm\model\articles\article($GLOBALS['objectId']))->getElementLink();
        $this->assertStringContainsString((new \fpcm\model\system\config)->system_url  . '?module=fpcm/article&id=' . $GLOBALS['objectId'], $link);
    }
    
    public function testGetArticleImage()
    {
        $img = (new fpcm\model\articles\article($GLOBALS['objectId']))->getArticleImage();
        $this->assertStringContainsString('<img loading="lazy" class="fpcm-pub-article-image" src="test.jpg" alt="'.$GLOBALS['article_title'].'" title="'.$GLOBALS['article_title'].'" role="presentation">', $img);
    }
    
    public function testGetMetaDataStatusIcons()
    {
        $icons = (new fpcm\model\articles\article($GLOBALS['objectId']))->getMetaDataStatusIcons(true, true, true);
        
        foreach ($icons as $icon) {
            $this->assertInstanceOf('\fpcm\view\helper\icon', $icon);
            $this->assertStringContainsString('<span class="fpcm-ui-icon-single fpcm-ui-editor-metainfo', (string) $icon );
        }
        
    }

    public function testDelete()
    {

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
