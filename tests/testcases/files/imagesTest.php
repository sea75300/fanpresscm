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
    
    public function tearDown() : void
    {
        if (!file_exists($GLOBALS['imageObj']->getFullpath())) {
            return;
        }
        
        unlink($GLOBALS['imageObj']->getFullpath());
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
        $this->assertEquals($GLOBALS['imageAltText'], $object->getAltText());
        $this->assertTrue($object->delete());
    }

    public function testGetCropperFilename()
    {
        $filename = $GLOBALS['imageName'];
        \fpcm\model\files\image::getCropperFilename($filename);

        $this->assertNotEquals($GLOBALS['imageName'], $filename);
        $this->assertStringContainsString('_cropped_', $filename);
    }

    public function testGetPropertiesArray()
    {
        $object =  new \fpcm\model\files\image($GLOBALS['imageName']);
        $data = $object->getPropertiesArray('Stefan');
        
        $keys = [
            'filename', 'filetime', 'fileuser',
            'filesize', 'fileresx', 'fileresy',
            'filehash', 'filemime', 'credits'
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $data);
        }

    }
    
    public function testValidateType()
    {
        $files = glob(__DIR__.DIRECTORY_SEPARATOR.'icon.*');
        $this->assertIsArray($files);
        $this->assertCount(3, $files);

        foreach ($files as $file) {
            $ext = fpcm\model\abstracts\file::retrieveFileExtension($file);
            $this->assertTrue(in_array($ext, ['png', 'jpg', 'gif']));
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file);
            $this->assertTrue(\fpcm\model\files\image::isValidType($ext, $mime), 'Mismatched ' . $ext . ' and ' . $mime);    
        }
        
        unset($ext, $file, $files);

        $file = __DIR__.DIRECTORY_SEPARATOR.'failed.bmp';
        $ext = fpcm\model\abstracts\file::retrieveFileExtension($file);
        $this->assertEquals('bmp', $ext);

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file);
        $this->assertFalse(\fpcm\model\files\image::isValidType($ext, $mime));   

    }

    private function createImage()
    {

        $GLOBALS['imageName'] = 'test' . date('Y-m-d_h-m-s') . '.gif';
        $GLOBALS['imageUserId'] = 1;
        $GLOBALS['imageCreated'] = time();
        $GLOBALS['imageAltText'] = 'Test 001';

        /* @var $GLOBALS['imageObj'] \fpcm\model\files\image */
        $GLOBALS['imageObj'] = new \fpcm\model\files\image($GLOBALS['imageName']);
        file_put_contents($GLOBALS['imageObj']->getFullpath(), 'data:image/gif;base64,R0lGODlhDQANAJEAAAAAABAQEOfn5wAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFlgAAACwAAAAADQANAAACJoQxmccj/wZDsErjosibQsdtYfWNpBgpSqpZkcdlF5y8DTk3KlMAACH5BAUKAAAALAAAAAANAA0AAAIlhDGZxyP/BkOwSuOqpdEl+GlaKIKZgnbRGHGZcB5neMnjhKFMAQA7');
        $GLOBALS['imageObj']->setUserid($GLOBALS['imageUserId']);
        $GLOBALS['imageObj']->setFiletime($GLOBALS['imageCreated']);
        $GLOBALS['imageObj']->setAltText($GLOBALS['imageAltText']);
        $result = $GLOBALS['imageObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);
    }

}
