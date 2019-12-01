<?php

require_once dirname(dirname(dirname(__DIR__))) . '/fpcmapi.php';

class commentsMassEditTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    public function setUp()
    {
        $GLOBALS['coids'] = $GLOBALS['coids'] ?? [];        
    }

    public function testMassEditArticles()
    {
        $this->createArticles();
        
        $this->assertTrue(is_array($GLOBALS['coids']));
        $this->assertEquals(2, count($GLOBALS['coids']));
        
        $res = (new \fpcm\model\comments\commentList)->editCommentsByMass(
            $GLOBALS['coids'],
            [
                'spammer' => 0,
                'approved' => 1,
                'private' => -1,
                'articleid' => 1
            ]
        );
        
        $this->assertTrue($res);

        foreach ($GLOBALS['coids'] as $id) {
            $testObj = new fpcm\model\comments\comment($id);
            $this->assertTrue($testObj->exists());
            $this->assertEquals(1, $testObj->getArticleid());
            $this->assertEquals(0, $testObj->getSpammer());
            $this->assertEquals(1, $testObj->getApproved());
            $this->assertTrue(in_array($testObj->getPrivate(), [0,1]));
            
            $testObj->setForceDelete(true);
            $this->assertTrue($testObj->delete());
        }
        
    }
    
    private function createArticles()
    {
        $obj1 = new fpcm\model\comments\comment;

        $obj1->setName('Max Mustermann1 ' . microtime(true));
        $obj1->setText('max.mustermann1' . microtime(true) . '@nobody-knows.org');
        $obj1->setEmail('nobody-knows1.org');
        $obj1->setWebsite('FPCM UnitTest Comment 1 from https://nobody-knows.org!');
        $obj1->setCreatetime(time() - 3600);
        $obj1->setArticleid(1);
        $obj1->setPrivate(1);
        $obj1->setSpammer(1);
        $obj1->setApproved(1);
        $obj1->setIpaddress('127.0.0.1');
        $result = $obj1->save();
        $this->assertGreaterThanOrEqual(1, $result);        
        $GLOBALS['coids'][] = $result;

        $obj2 = new fpcm\model\comments\comment;
        
        $obj1->setName('Max Mustermann2 ' . microtime(true));
        $obj1->setText('max.mustermann2' . microtime(true) . '@nobody-knows.org');
        $obj1->setEmail('nobody-knows2.org');
        $obj1->setWebsite('FPCM UnitTest Comment 2 from https://nobody-knows.org!');
        $obj1->setCreatetime(time() - 1800);
        $obj1->setArticleid(2);
        $obj1->setPrivate(0);
        $obj1->setSpammer(0);
        $obj1->setApproved(0);
        $obj1->setIpaddress('127.1.2.3');
        $result = $obj1->save();
        $this->assertGreaterThanOrEqual(1, $result);

        $GLOBALS['coids'][] = $result;
    }


}
