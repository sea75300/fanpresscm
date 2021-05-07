<?php

require_once dirname(dirname(dirname(__DIR__))) . '/fpcmapi.php';

class fpcmApiTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var \fpcmAPI
     */
    protected $object;

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    protected function setUp() : void
    {
        $this->object = new fpcmAPI();
        parent::setUp();
    }

    public function testLoginExternal()
    {

        $result = $this->object->loginExternal([
            'username' => 'stefan',
            'password' => 'Stefan1'
        ]);

        $this->assertFalse(is_bool($result));
        $this->assertTrue(is_string($result));
        $this->assertNotEmpty($result);

        $GLOBALS['testSessionId'] = $result;
    }

    public function testLoginExternalPing()
    {

        $result = $this->object->loginExternal(['sessionId' => $GLOBALS['testSessionId']]);
        $this->assertTrue($result);
    }

    public function testLogoutExternal()
    {

        $result = $this->object->logoutExternal($GLOBALS['testSessionId']);
        $this->assertTrue($result);

        $result = $this->object->loginExternal(['sessionId' => $GLOBALS['testSessionId']]);
        $this->assertFalse($result);
    }

    public function testFpcmEnCrypt()
    {

        $GLOBALS['testSessionId2'] = $this->object->fpcmEnCrypt($GLOBALS['testSessionId']);
        $this->assertTrue(is_string($GLOBALS['testSessionId2']));
    }

    public function testFpcmDeCrypt()
    {

        $result = $this->object->fpcmDeCrypt($GLOBALS['testSessionId2']);
        $this->assertTrue(is_string($result));
        $this->assertEquals($result, $GLOBALS['testSessionId']);
    }

    public function testCheckLockedIp()
    {
        $ip = new fpcm\model\ips\ipaddress();
        $ip->setIpaddress('127.0.0.1');
        $ip->setNoaccess(1);
        $ip->setIptime(time());
        $ip->setUserid(1);

        $this->assertTrue( $ip->save());
        $this->assertTrue($this->object->checkLockedIp());
        $this->assertTrue($ip->delete());
    }

    public function testSendMail()
    {
        $this->assertTrue($this->object->sendMail([
            'to' => 'sea75300@yahoo.de',
            'subject' => 'Unit-Test ' . __METHOD__,
            'text' => 'Unit-Test ' . __METHOD__,
        ]));
    }

    public function testIsMaintenance()
    {
        \fpcm\classes\loader::getObject('\fpcm\model\system\config')->setMaintenanceMode(true);
        $this->assertEquals(1, \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_maintenance);
        \fpcm\classes\loader::getObject('\fpcm\model\system\config')->init();
        $this->assertTrue($this->object->isMaintenance());

        \fpcm\classes\loader::getObject('\fpcm\model\system\config')->setMaintenanceMode(false);
        $this->assertEquals(0, \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_maintenance);
        \fpcm\classes\loader::getObject('\fpcm\model\system\config')->init();

        $this->assertFalse($this->object->isMaintenance());
    }
}
