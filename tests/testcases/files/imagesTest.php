<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class imagesTest extends testBase {

    /**
     * @var fpcm\model\files\imagelist
     */
    protected $object;

    protected function setUp() : void
    {
        $this->className = 'files\\imagelist';
        parent::setUp();
    }

    public function testGetDatabaseListByCondition()
    {

        $this->createImage();

        $cond = new \fpcm\model\files\search();
        $cond->filename = $GLOBALS['imageName'];

        $data = $this->object->getDatabaseListByCondition($cond);

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));

        /* @var $object \fpcm\model\files\image */
        $object = $data[$GLOBALS['imageName']];
        $this->assertInstanceOf('\\fpcm\\model\\files\\image', $object);
        $this->assertEquals($GLOBALS['imageName'], $object->getFilename());
        $this->assertEquals($GLOBALS['imageUserId'], $object->getUserid());
        $this->assertEquals($GLOBALS['imageCreated'], $object->getFiletime());
        $this->assertTrue($object->delete());
    }

    private function createImage()
    {

        $GLOBALS['imageName'] = 'test' . date('Y-m-d_h-m-s') . '.gif';
        $GLOBALS['imageUserId'] = 1;
        $GLOBALS['imageCreated'] = time();

        /* @var $GLOBALS['imageObj'] \fpcm\model\files\image */
        $GLOBALS['imageObj'] = new \fpcm\model\files\image($GLOBALS['imageName']);
        file_put_contents($GLOBALS['imageObj']->getFullpath(), ' data:image/gif;base64,R0lGODlhDQANAJEAAAAAABAQEOfn5wAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFlgAAACwAAAAADQANAAACJoQxmccj/wZDsErjosibQsdtYfWNpBgpSqpZkcdlF5y8DTk3KlMAACH5BAUKAAAALAAAAAANAA0AAAIlhDGZxyP/BkOwSuOqpdEl+GlaKIKZgnbRGHGZcB5neMnjhKFMAQA7');
        $GLOBALS['imageObj']->setUserid($GLOBALS['imageUserId']);
        $GLOBALS['imageObj']->setFiletime($GLOBALS['imageCreated']);
        $result = $GLOBALS['imageObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);
    }

}
