<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class commentsTest extends testBase {
    
    /**
     * @var \fpcm\model\comments\commentList
     */
    protected $object;

    public function setUp() {
        $this->className = 'comments\\commentList';
        parent::setUp();
    }
    
    public function testCountAllComments() {
        $this->createComment();

        $GLOBALS['countAllComments'] = $this->object->countComments();
        $this->assertArrayHasKey(1, $GLOBALS['countAllComments']);        
        $GLOBALS['countAllComments'] = array_sum($GLOBALS['countAllComments']);
        $this->assertGreaterThanOrEqual(1, $GLOBALS['countAllComments']);
    }

    public function testGetCommentsAll() {

        $data = $this->object->getCommentsAll();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\comments\comment */
        $object = $data[$GLOBALS['objectId']];

        $this->assertInstanceOf('\\fpcm\\model\\comments\\comment', $object);
        $this->assertEquals($GLOBALS['commentName'], $object->getName());
        $this->assertEquals($GLOBALS['commentEmail'], $object->getEmail());
        $this->assertEquals($GLOBALS['commentWebsite'], $object->getWebsite());
        $this->assertEquals($GLOBALS['commentContent'], $object->getText());
        $this->assertEquals(1, $object->getSpammer());
        $this->assertEquals(0, $object->getApproved());
        $this->assertEquals(1, $object->getPrivate());

    }
    
    public function testCountCommentsByArticleIds() {
        $GLOBALS['countCommentsByArticleIds'] = $this->object->countComments([1]);

        $this->assertArrayHasKey(1, $GLOBALS['countCommentsByArticleIds']);
        $this->assertGreaterThanOrEqual(1, $GLOBALS['countCommentsByArticleIds'][1]);

        $data = $this->object->countComments();
        $this->assertGreaterThanOrEqual(array_sum($data), ($GLOBALS['countAllComments'] + 1));

    }
    
    public function testCountUnapprovedPrivateComments() {
        $GLOBALS['countUnapprovedPrivateComments'] = $this->object->countUnapprovedPrivateComments();
        $this->assertArrayHasKey(1, $GLOBALS['countUnapprovedPrivateComments']);
        $this->assertGreaterThanOrEqual(1, array_sum($GLOBALS['countUnapprovedPrivateComments']));
    }

    public function testGetCommentsByCondition() {

        $data = $this->object->getCommentsByCondition(1,1,0,1);

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\comments\comment */
        $object = $data[$GLOBALS['objectId']];
        $this->assertInstanceOf('\\fpcm\\model\\comments\\comment', $object);
        $this->assertEquals($GLOBALS['commentName'], $object->getName());
        $this->assertEquals($GLOBALS['commentEmail'], $object->getEmail());
        $this->assertEquals($GLOBALS['commentWebsite'], $object->getWebsite());
        $this->assertEquals($GLOBALS['commentContent'], $object->getText());
        $this->assertEquals(1, $object->getSpammer());
        $this->assertEquals(0, $object->getApproved());
        $this->assertEquals(1, $object->getPrivate());

    }

    public function testCommentsBySearchCondition() {

        $cond = new \fpcm\model\comments\search();
        $cond->articleid  = 1;
        $cond->private    = 1;
        $cond->spam       = 1;
        $cond->searchtype = 0;
        
        $data = $this->object->getCommentsBySearchCondition($cond);

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\comments\comment */
        $object = $data[$GLOBALS['objectId']];
        $this->assertInstanceOf('\\fpcm\\model\\comments\\comment', $object);
        $this->assertEquals($GLOBALS['commentName'], $object->getName());
        $this->assertEquals($GLOBALS['commentEmail'], $object->getEmail());
        $this->assertEquals($GLOBALS['commentWebsite'], $object->getWebsite());
        $this->assertEquals($GLOBALS['commentContent'], $object->getText());
        $this->assertEquals(1, $object->getSpammer());
        $this->assertEquals(0, $object->getApproved());
        $this->assertEquals(1, $object->getPrivate());

    }

    public function testGetCommentsByLimit() {

        $data = $this->object->getCommentsByLimit(0, 5);
        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(0, count($data));
        $this->assertLessThanOrEqual(5, count($data));
    }

    public function testEditCommentByMass() {
        
        $result = $this->object->editCommentsByMass([$GLOBALS['objectId']], [
            'spammer'    => 0,
            'approved'   => 1,
            'private'    => -1,
            'articleid'  => 2,
        ]);
        
        $this->assertTrue($result);
 
        /* @var $object \fpcm\model\comments\comment */
        $object = new fpcm\model\comments\comment($GLOBALS['objectId']);
        $this->assertEquals($GLOBALS['commentName'], $object->getName());
        $this->assertEquals($GLOBALS['commentEmail'], $object->getEmail());
        $this->assertEquals($GLOBALS['commentWebsite'], $object->getWebsite());
        $this->assertEquals($GLOBALS['commentContent'], $object->getText());
        $this->assertEquals(2, $object->getArticleid());
        $this->assertEquals(0, $object->getSpammer());
        $this->assertEquals(1, $object->getApproved());
        $this->assertEquals(1, $object->getPrivate());
    }

    public function testDeleteComments() {

        $result = $this->object->deleteComments([$GLOBALS['objectId']]);
        $this->assertTrue($result);
    }
    
    private function createComment() {

        /* @var $GLOBALS['commentObj'] \fpcm\model\comments\comment */
        $GLOBALS['commentObj'] = new \fpcm\model\comments\comment();

        $GLOBALS['commentName']    = 'Max Mustermann '.microtime(true);
        $GLOBALS['commentEmail']   = 'max.mustermann'.microtime(true).'@nobody-knows.org';
        $GLOBALS['commentWebsite'] = 'nobody-knows.org';
        $GLOBALS['commentContent'] = 'FPCM UnitTest Article from https://nobody-knows.org!';
        $GLOBALS['commentCreated'] = time();
        
        $GLOBALS['commentObj']->setName($GLOBALS['commentName']);
        $GLOBALS['commentObj']->setText($GLOBALS['commentContent']);
        $GLOBALS['commentObj']->setEmail($GLOBALS['commentEmail']);
        $GLOBALS['commentObj']->setWebsite($GLOBALS['commentWebsite']);
        $GLOBALS['commentObj']->setCreatetime($GLOBALS['commentCreated']);
        $GLOBALS['commentObj']->setArticleid(1);
        $GLOBALS['commentObj']->setPrivate(1);
        $GLOBALS['commentObj']->setSpammer(1);
        $GLOBALS['commentObj']->setApproved(0);
        $GLOBALS['commentObj']->setIpaddress('127.0.0.1');
        $result = $GLOBALS['commentObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);
        
        $GLOBALS['objectId'] = $result;

    }
}