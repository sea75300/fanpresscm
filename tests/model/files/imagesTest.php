<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class imagesTest extends testBase {
    
    /**
     * @var fpcm\model\files\imagelist
     */
    protected $object;

    public function setUp() {
        $this->className = 'files\\imagelist';
        parent::setUp();
    }

    public function testGetDatabaseListByCondition() {

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

    private function createImage() {

        $GLOBALS['imageName']    = 'test'.microtime(true).'.jpg';
        $GLOBALS['imageUserId']  = 1;
        $GLOBALS['imageCreated'] = time();

        /* @var $GLOBALS['imageObj'] \fpcm\model\files\image */
        $GLOBALS['imageObj']     = new \fpcm\model\files\image($GLOBALS['imageName']);
        $GLOBALS['imageObj']->setUserid($GLOBALS['imageUserId']);
        $GLOBALS['imageObj']->setFiletime($GLOBALS['imageCreated']);
        $result = $GLOBALS['imageObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);

    }

}