<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class userListTest extends testBase {
    
    /**
     * @var \fpcm\model\users\userList
     */
    protected $object;

    public function setUp() {
        $this->className = 'users\\userList';
        parent::setUp();
    }

    public function testGetUserIdByUsername() {
        
        $this->createUser();

        $id = $this->object->getUserIdByUsername($GLOBALS['userName']);
        $this->assertGreaterThanOrEqual(1, $id);
    }
    
    public function testGetUsersForArticles() {
        
        $users = $this->object->getUsersForArticles([
            $GLOBALS['testArticleId']
        ]);

        $this->assertGreaterThanOrEqual(1, count($users));
        $this->assertTrue(isset($users[$GLOBALS['testUserId']]));
        $this->assertEquals($GLOBALS['userName'], $users[$GLOBALS['testUserId']]->getUsername());
        $this->assertEquals($GLOBALS['userEmail'], $users[$GLOBALS['testUserId']]->getEmail());
    }
    
    public function testGetUsersAll() {
        $data = $this->object->getUsersAll();
        $this->assertTrue(isset($data[$GLOBALS['testUserId']]));
    }
    
    public function testGetUsersActive() {
        $data = $this->object->getUsersActive();
        $this->assertTrue(isset($data[$GLOBALS['testUserId']]));
    }
    
    public function testDiableUsers() {
        $result = $this->object->diableUsers([$GLOBALS['testUserId']]);
        $this->assertTrue($result);
    }
    
    public function testGetUsersDisabled() {
        $data = $this->object->getUsersDisabled();
        $this->assertTrue(isset($data[$GLOBALS['testUserId']]));
    }
    
    public function testEnableUsers() {
        $result = $this->object->enableUsers([$GLOBALS['testUserId']]);
        $this->assertTrue($result);
    }
    
    public function testGetUsersByIds() {
        $data = $this->object->getUsersByIds([$GLOBALS['testUserId']]);
        $this->assertTrue(isset($data[$GLOBALS['testUserId']]));
    }
    
    public function testGetEmailByUserId() {
        $data = $this->object->getEmailByUserId($GLOBALS['testUserId']);
        $this->assertEquals($GLOBALS['userEmail'], $data);
    }
    
    public function testGetUsersEmailList() {
        $data = $this->object->getUsersEmailList();
        $this->assertTrue(isset($data[$GLOBALS['userEmail']]));
        $this->assertTrue(in_array($GLOBALS['testUserId'], $data));
    }
    
    public function testGetUsersNameList() {
        $data = $this->object->getUsersNameList();
        $this->assertTrue(isset($data[$GLOBALS['userName']]));
        $this->assertTrue(in_array($GLOBALS['testUserId'], $data));
    }
    
    public function testCountActiveUsers() {
        $data = $this->object->countActiveUsers();
        $this->assertGreaterThanOrEqual(1, $data);
    }

    public function testDeleteItems() {

        $result = $this->object->deleteUsers([$GLOBALS['testUserId']]);
        $this->assertTrue($result);
        
        $data = $this->object->getUsersAll();
        $this->assertFalse(isset($data[$GLOBALS['testUserId']]));
        
        $article = new \fpcm\model\articles\article($GLOBALS['testArticleId']);
        $article->delete();
    }
    
    private function createUser() {

        $GLOBALS['userName']  = 'fpcmTestUser'.microtime(true);
        $GLOBALS['userEmail'] = 'test'.microtime(true).'@nobody-knows.org';

        /* @var $object fpcm\model\users\author */
        $object = new fpcm\model\users\author();
        $object->setDisplayName($GLOBALS['userName']);
        $object->setUserName($GLOBALS['userName']);
        $object->setEmail($GLOBALS['userEmail']);
        $object->setPassword('fpcmTest2017');
        $object->setRegistertime(time());
        $object->setRoll(3);
        $object->setDisabled(0);

        $result = $object->save();
        $this->assertTrue($result);
        
        $GLOBALS['testUserId'] = $object->getId();

        $article = new \fpcm\model\articles\article();
        $article->setTitle('fpcmTestArticle'.microtime(true));
        $article->setContent('fpcmTestArticle'.microtime(true));
        $article->setCreateuser($object->getId());
        $article->setCreatetime(time());
        $article->setCategories([1]);
        
        $res = $article->save();
        $this->assertGreaterThanOrEqual(1, $res);
        $GLOBALS['testArticleId'] = $article->getId();
    }

}