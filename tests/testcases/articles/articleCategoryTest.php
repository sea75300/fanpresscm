<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class articleCategoryTest extends \PHPUnit\Framework\TestCase {

    public function testSave()
    {
        $object = new fpcm\model\articles\articleCategory(1, 1);
        $result = $object->save();
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $object = new fpcm\model\articles\articleCategory(1, 1);
        $result = $object->delete();
        $this->assertTrue($result);
    }

    public function testDeleteByArticle()
    {
        $object1 = new fpcm\model\articles\articleCategory(1, 1);
        $result = $object1->save();
        $object2 = new fpcm\model\articles\articleCategory(1, 2);
        $result = $object2->save();
        $object3 = new fpcm\model\articles\articleCategory(1, 3);
        $result = $object3->save();

        $object4 = new fpcm\model\articles\articleCategory(1, 0);
        $result = $object4->deleteByArticle();
        $this->assertTrue($result);
    }

}
