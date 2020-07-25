<?php

require_once dirname(dirname(__FILE__)).'/inc/common.php';

class testBase extends \PHPUnit\Framework\TestCase {

    /**
     * @var string
     */
    protected $className;
    
    /**
     * @var \fpcm\model\abstracts\model
     */
    protected $object;

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    protected function setUp() : void
    {        
        $class = '\\fpcm\\model\\'.$this->className;
        
        if (!isset($GLOBALS['objectId'])) {
            $GLOBALS['objectId'] = null;
        }
        
        $this->object = new $class($GLOBALS['objectId']);
        $this->name   = __CLASS__;
    }

    public function tearDown() : void {

    }

}
