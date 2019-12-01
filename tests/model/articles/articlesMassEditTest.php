<?php

require_once dirname(dirname(dirname(__DIR__))) . '/fpcmapi.php';

class articlesMassEditTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    public function setUp()
    {
        $GLOBALS['artids'] = $GLOBALS['artids'] ?? [];        
    }

    public function testMassEditArticles()
    {
        $this->createArticles();
        
        $this->assertTrue(is_array($GLOBALS['artids']));
        $this->assertEquals(2, count($GLOBALS['artids']));
        
        $res = (new fpcm\model\articles\articlelist)->editArticlesByMass(
            $GLOBALS['artids'],
            [
                'categories' => [1],
                'comments' => -1,
                'pinned' => 0,
                'createuser' => 1
            ]
        );
        
        $this->assertTrue($res);

        foreach ($GLOBALS['artids'] as $id) {
            $art = new \fpcm\model\articles\article($id);
            $this->assertTrue($art->exists());
            $this->assertEquals(0, $art->getPinned());
            $this->assertEquals([1], $art->getCategories());
            $this->assertEquals(1, $art->getCreateuser());
            $this->assertTrue(in_array($art->getComments(), [0,1]));
            
            $art->setForceDelete(true);
            $this->assertTrue($art->delete());
        }
        
    }
    
    private function createArticles()
    {
        $obj1 = new \fpcm\model\articles\article;

        $obj1->setTitle('FPCM UnitTest Article ' . microtime(true));
        $obj1->setContent('FPCM UnitTest Article 1 from https://nobody-knows.org!');
        $obj1->setCreatetime(time() - 3600);
        $obj1->setCreateuser(1);
        $obj1->setPinned(1);
        $obj1->setComments(1);
        $obj1->setSources('https://nobody-knows.org');
        $obj1->setCategories(array(1));
        
        $GLOBALS['artids'][] = $obj1->save();

        $obj2 = new \fpcm\model\articles\article;

        $obj2->setTitle('FPCM UnitTest Article ' . microtime(true));
        $obj2->setContent('FPCM UnitTest Article 2 from https://nobody-knows.org!');
        $obj2->setCreatetime(time() - 1800);
        $obj2->setCreateuser(0);
        $obj2->setPinned(0);
        $obj2->setComments(0);
        $obj2->setSources('https://nobody-knows.org');
        $obj2->setCategories(array(1, 2));

        $GLOBALS['artids'][] = $obj1->save();
    }


}
