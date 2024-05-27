<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class indexerTest extends \PHPUnit\Framework\TestCase {

    public function testAddTestData()
    {
        $art = new fpcm\model\articles\article();
        $art->setTitle('Global Search Article');
        $art->setContent('Global Search Article');
        $art->setCategories([]);
        $r1 = $art->save();
        $this->assertNotFalse($r1);

        $com = new fpcm\model\comments\comment();
        $com->setName('Global Search Comment');
        $com->setText('Global Search Comment');
        $com->setArticleid($r1);
        $r2 = $com->save();
        $this->assertNotFalse($r2);

        $file = new fpcm\model\files\image('global_search_file.jpg');
        $file->setAltText('Global Search File');
        $file->setUserid(1);
        $file->setFiletime(time());
        
        $fpcr = file_put_contents($file->getFullpath(), 'data:image/gif;base64,R0lGODlhDQANAJEAAAAAABAQEOfn5wAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFlgAAACwAAAAADQANAAACJoQxmccj/wZDsErjosibQsdtYfWNpBgpSqpZkcdlF5y8DTk3KlMAACH5BAUKAAAALAAAAAANAA0AAAIlhDGZxyP/BkOwSuOqpdEl+GlaKIKZgnbRGHGZcB5neMnjhKFMAQA7');
        $this->assertNotFalse($fpcr);
        
        $r3 = $file->save();
        $this->assertNotFalse($r3);

        $GLOBALS['fpcm_article_id'] = $r1;
        $GLOBALS['fpcm_comment_id'] = $r2;
        $GLOBALS['fpcm_file_name'] = $file->getFilename();
    }

    public function testGetData()
    {
        $GLOBALS['fpcm']['objects']['fpcm\model\permissions\permissions'] = new fpcm\model\permissions\permissions(1);

        $conditions = new fpcm\model\gsearch\conditions('Global Search');
        $this->assertEquals('Global Search', $conditions->getTerm());

        $indexer = new fpcm\model\gsearch\indexer($conditions);
        $result = $indexer->getData();

        $this->assertInstanceOf('fpcm\model\gsearch\resultSet', $result);
        $this->assertEquals(3, $result->getCount());

        $items = $result->getItems();
        $this->assertCount(3, $items);

        /* @var $item fpcm\model\gsearch\resultItem */
        foreach ($items as $item) {
            $this->assertInstanceOf('fpcm\model\gsearch\resultItem', $item);
            $json = json_encode($item);
            $this->assertStringContainsString('Global Search', $json);
        }
    }
    
    public function testDeleteItems()
    {
        $a = new fpcm\model\articles\article($GLOBALS['fpcm_article_id']);
        $a->setForceDelete(true);
        $r1 = $a->delete();
        $this->assertTrue($r1);

        $c = new fpcm\model\comments\comment($GLOBALS['fpcm_comment_id']);
        $c->setForceDelete(true);
        $r2 = $c->delete();
        $this->assertTrue($r2);

        $r3 = (new fpcm\model\files\image($GLOBALS['fpcm_file_name']))->delete();
        $this->assertTrue($r3);   
    }

}
