<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class fileOptionTest extends testBase {

    /**
     * @var fpcm\model\files\fileOption
     */
    protected $object;

    protected function setUp() : void
    {
        $GLOBALS['objectId'] = 'tests/'.__CLASS__;
        
        $this->className = 'files\\fileOption';
        parent::setUp();
    }
    
    public function testWrite()
    {
        $res = $this->object->write(__FILE__);
        $this->assertTrue($res);
    }

    public function testRead()
    {
        $res = $this->object->read();
        $this->assertTrue($res === __FILE__);
    }
    
    public function testRemove()
    {
        $res = $this->object->remove();
        $this->assertTrue($res);
    }

}
