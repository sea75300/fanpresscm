<?php

require_once dirname(dirname(dirname(__DIR__))).'/inc/common.php';

class cryptTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    public function setUp() {

        $GLOBALS['data'] = [
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
            'sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.'
        ];
        
    }
    
    public function testEncrypt() {

        $crypt = new \fpcm\classes\crypt();
        $GLOBALS['crypted'] = $crypt->encrypt($GLOBALS['data']);
        
        $this->assertTrue(($GLOBALS['crypted'] !== false));
    }
    
    public function testDecrypt() {

        $crypt = new \fpcm\classes\crypt();
        $GLOBALS['uncrypted'] = $crypt->decrypt($GLOBALS['crypted']);

        $this->assertTrue(($GLOBALS['uncrypted'] !== false));

    }
    
    public function testResult() {

        $this->assertEquals(
            hash('sha256', json_encode($GLOBALS['data'])),
            hash('sha256', $GLOBALS['uncrypted'])
        );

    }

}