<?php

require_once dirname(dirname(dirname(__DIR__))) . '/fpcmapi.php';

class categoriesMassEditTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    public function setUp()
    {
        $GLOBALS['catids'] = $GLOBALS['catids'] ?? [];        
    }

    public function testMassEditArticles()
    {
        $this->createArticles();
        
        $this->assertTrue(is_array($GLOBALS['catids']));
        $this->assertEquals(2, count($GLOBALS['catids']));
        
        $res = (new \fpcm\model\categories\categoryList)->editCategoriesByMass(
            $GLOBALS['catids'],
            [
                'groups' => -1,
                'iconpath' => 'common.png'
            ]
        );
        
        $this->assertTrue($res);

        foreach ($GLOBALS['catids'] as $id) {
            $testObj = new fpcm\model\categories\category($id);
            $this->assertTrue($testObj->exists());
            $this->assertEquals('common.png', $testObj->getIconPath());            
            $this->assertTrue(in_array($testObj->getGroups(), ['1', '2']));
            $this->assertTrue($testObj->delete());
        }
        
    }
    
    private function createArticles()
    {
        $obj1 = new fpcm\model\categories\category;

        $obj1->setName('UnitTest category 1 ' . microtime(true));
        $obj1->setGroups('2');
        $obj1->setIconPath('icon.jpg');
        $result = $obj1->save();
        $this->assertGreaterThanOrEqual(1, $result);        
        $GLOBALS['catids'][] = $result;

        $obj2 = new fpcm\model\categories\category;
        $obj2->setName('UnitTest category 1 ' . microtime(true));
        $obj2->setGroups('1');
        $obj2->setIconPath('');
        $result = $obj2->save();
        $this->assertGreaterThanOrEqual(1, $result);        
        $GLOBALS['catids'][] = $result;

    }


}
