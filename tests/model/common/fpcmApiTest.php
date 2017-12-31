<?php

require_once dirname(dirname(dirname(__DIR__))).'/fpcmapi.php';

class fpcmAPiTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var \fpcmAPI
     */
    protected $object;

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    public function setUp() {
        
        $this->object = new fpcmAPI();
        
        parent::setUp();
    }
    
    public function testLoginExternal() {
        
        $result = $this->object->loginExternal([
            'username' => 'stefan',
            'password' => 'Stefan1'
        ]);
        
        $this->assertFalse(is_bool($result));
        $this->assertTrue(is_string($result));
        $this->assertNotEmpty($result);
        
        $GLOBALS['testSessionId'] = $result;
        
    }
    
    public function testLoginExternalPing() {
        
        $result = $this->object->loginExternal(['sessionId' => $GLOBALS['testSessionId']]);
        $this->assertTrue($result);
        
    }
    
    public function testLogoutExternal() {
        
        $result = $this->object->logoutExternal($GLOBALS['testSessionId']);
        $this->assertTrue($result);
        
        $result = $this->object->loginExternal(['sessionId' => $GLOBALS['testSessionId']]);
        $this->assertFalse($result);
        
    }
    
    public function testFpcmEnCrypt() {
        
        $GLOBALS['testSessionId2'] = $this->object->fpcmEnCrypt($GLOBALS['testSessionId']);
        $this->assertTrue(is_string($GLOBALS['testSessionId2']));
        
    }
    
    public function testFpcmDeCrypt() {
        
        $result = $this->object->fpcmDeCrypt($GLOBALS['testSessionId2']);
        $this->assertTrue(is_string($result));
        $this->assertEquals($result, $GLOBALS['testSessionId']);
        
    }
    
}