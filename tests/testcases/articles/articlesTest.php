<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class articlesTest extends testBase {

    /**
     * @var \fpcm\model\articles\articlelist
     */
    protected $object;

    protected function setUp() : void
    {
        $this->className = 'articles\\articlelist';
        parent::setUp();
    }

    public function testGetArticlesDraft()
    {

        $this->createArticle();

        $data = $this->object->getArticlesDraft();

        $count = count($data);
        if ($count == 0) {
            $this->markTestSkipped('No articles available in db');
        }

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, $count);

        /* @var $object \fpcm\model\articles\article */
        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals(0, $object->getDeleted());
        $this->assertEquals(1, $object->getDraft());

        $GLOBALS['articleObj']->setApproval(0);
        $GLOBALS['articleObj']->setDraft(0);
        $this->assertTrue($GLOBALS['articleObj']->update());
    }

    public function testGetArticlesPostponed()
    {

        $data = $this->object->getArticlesPostponed();

        $this->assertIsArray($data);
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\articles\article */
        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals(1, $object->getPostponed());
        $this->assertEquals(0, $object->getDeleted());
        $this->assertEquals(0, $object->getDraft());
        $this->assertEquals(0, $object->getApproval());

        $res = (new fpcm\model\crons\postponedArticles())->run();
        $this->assertTrue($res);

        $this->assertEquals(0, (new \fpcm\model\articles\article($GLOBALS['articleId']))->getPostponed());

        usleep(1000);
    }

    public function testCheckArticlesPinnedUntil()
    {
        $res = $this->object->checkArticlesPinnedUntil();
        $this->assertTrue($res);

        $this->assertEquals(0, (new \fpcm\model\articles\article($GLOBALS['articleId']))->getPinned());
        usleep(1000);
    }

    public function testGetArticlesAll()
    {

        $data = $this->object->getArticlesAll();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
    }

    public function testGetArticlesActive()
    {

        $data = $this->object->getArticlesActive();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\articles\article */
        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals(0, $object->getDraft());
        $this->assertEquals(0, $object->getArchived());
        $this->assertEquals(0, $object->getDeleted());
    }

    public function testGetArticlesByCondition()
    {

        $cond = new \fpcm\model\articles\search();
        $cond->user = 1;
        $cond->deleted = 0;
        $cond->limit = [1, 0];
        $cond->orderby = ['id DESC'];

        $data = $this->object->getArticlesByCondition($cond);

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\articles\article */
        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
    }

    public function testGetArticlesByCategoryCondition()
    {

        $cond = new \fpcm\model\articles\search();
        $cond->category = 1;

        $data = $this->object->getArticlesByCondition($cond);

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\articles\article */
        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
    }

    public function testGetArticleIDsByUser()
    {

        $data = $this->object->getArticleIDsByUser(1);

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));
        $this->assertTrue(in_array($GLOBALS['articleId'], $data));
    }

    public function testGetMinMaxDate()
    {

        $data = $this->object->getMinMaxDate();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, $data['maxDate']);
        $this->assertGreaterThanOrEqual(1, $data['maxDate']);
    }

    public function testArchiveArticles()
    {

        $result = $this->object->editArticlesByMass([$GLOBALS['articleId']], [
            'archived' => 1
        ]);

        $this->assertTrue($result);
    }

    public function testMoveArticlesToUser()
    {
        $result = $this->object->moveArticlesToUser(1, 2);
        $this->assertTrue($result);
    }

    public function testGetArticlesArchived()
    {

        $data = $this->object->getArticlesArchived();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\articles\article */
        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals(1, $object->getArchived());
        $this->assertEquals(0, $object->getDeleted());
        $this->assertEquals(2, $object->getCreateuser());
    }

    public function testEditArticlesByMass()
    {

        $result = $this->object->editArticlesByMass([$GLOBALS['articleId']], [
            'categories' => json_encode([1, 2]),
            'archived' => 0,
            'pinned' => -1
        ]);

        $this->assertTrue($result);

        /* @var $object \fpcm\model\articles\article */
        $object = new fpcm\model\articles\article($GLOBALS['articleId']);
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals(0, $object->getArchived());
        $this->assertEquals(0, $object->getDeleted());
        $this->assertEquals(2, $object->getCreateuser());
    }

    public function testGetRelatedItemsCount()
    {
        $result = $this->object->getRelatedItemsCount([ $GLOBALS['articleId'] ]);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result[$GLOBALS['articleId']]);
        $this->assertInstanceOf('fpcm\model\articles\relatedCountItem', $result[$GLOBALS['articleId']]);

        /* @var $obj fpcm\model\articles\relatedCountItem */
        $obj = $result[$GLOBALS['articleId']];
        $this->assertEquals($GLOBALS['articleId'], $obj->getArticleId());
        $this->assertGreaterThanOrEqual(0, $obj->getComments());
        $this->assertGreaterThanOrEqual(0, $obj->getPrivateUnapprovedComments());
        $this->assertGreaterThanOrEqual(5, $obj->getShares());
    }

    public function testDeleteArticles()
    {
        $result = $this->object->deleteArticles([$GLOBALS['articleId']]);
        $this->assertTrue($result);
    }

    public function testDeleteArticlesByUser()
    {
        $db = new \fpcm\classes\database();
        if ($db->getDbtype() === \fpcm\classes\database::DBTYPE_POSTGRES) {
            $this->markTestSkipped('Deletes all articles while running on Postgres');
        }

        $result = $this->object->deleteArticlesByUser(2);
        $this->assertTrue($result);
    }

    public function testGetArticlesDeleted()
    {
        $data = $this->object->getArticlesDeleted();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\articles\article */
        $object = $data[$GLOBALS['articleId']];
        $this->assertInstanceOf('\\fpcm\\model\\articles\\article', $object);
        $this->assertEquals($GLOBALS['article_title'], $object->getTitle());
        $this->assertEquals(1, $object->getDeleted());
    }

    public function testEmptyTrash()
    {

        $result = $this->object->emptyTrash();
        $this->assertTrue($result);

        $data = $this->object->getArticlesDeleted();

        $this->assertTrue(is_array($data));
        $this->assertEquals(0, count($data));
    }

    private function createArticle()
    {

        /* @var $GLOBALS['articleObj'] \fpcm\model\articles\article */
        $GLOBALS['articleObj'] = new \fpcm\model\articles\article();

        $GLOBALS['article_title'] = 'FPCM UnitTest Article ' . microtime(true);
        $GLOBALS['article_content'] = 'FPCM UnitTest Article from https://nobody-knows.org!';
        $GLOBALS['article_created'] = time() - 600;

        $GLOBALS['articleObj']->setTitle($GLOBALS['article_title']);
        $GLOBALS['articleObj']->setContent($GLOBALS['article_content']);
        $GLOBALS['articleObj']->setCreatetime($GLOBALS['article_created']);
        $GLOBALS['articleObj']->setCreateuser(1);
        $GLOBALS['articleObj']->setPinned(1);
        $GLOBALS['articleObj']->setPinnedUntil(time()-3600*24);
        $GLOBALS['articleObj']->setComments(1);
        $GLOBALS['articleObj']->setSources('https://nobody-knows.org');
        $GLOBALS['articleObj']->setCategories([1]);
        $GLOBALS['articleObj']->setDraft(1);
        $GLOBALS['articleObj']->setPostponed(1);

        $result = $GLOBALS['articleObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);

        $GLOBALS['articleId'] = $result;

        $share = new fpcm\model\shares\share();
        $share->setArticleId($GLOBALS['articleId']);
        $share->setSharecount(5);
        $share->setShareitem('likebutton');
        $share->setLastshare(time());
        $this->assertGreaterThanOrEqual(1, $share->save());

    }

}
