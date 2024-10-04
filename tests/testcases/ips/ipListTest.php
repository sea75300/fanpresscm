<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class ipListTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'ips\\iplist';
        parent::setUp();
    }

    public function testGetIpAll()
    {
        $this->createTestItem();

        $data = $this->object->getIpAll();
        $count = count($data);
        if ($count == 0) {
            $this->markTestSkipped('No ip items available in db');
        }

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, $count);
        $this->assertArrayHasKey($GLOBALS['objectId'], $data);
        $this->assertEquals($GLOBALS['test_ip_address'], $data[$GLOBALS['objectId']]->getIpaddress());
    }

    public function testIpIsLocked()
    {
        $this->assertTrue($this->object->ipIsLocked());
        $this->assertTrue($this->object->ipIsLocked('nocomments'));
        $this->assertTrue($this->object->ipIsLocked('nologin'));
    }

    public function testDeleteIpAdresses()
    {
        $this->assertTrue($this->object->deleteIpAdresses([
            $GLOBALS['objectId']
        ]));
    }

    private function createTestItem()
    {
        $GLOBALS['test_ip_address'] = '127.0.0.1';
        $GLOBALS['test_ctime'] = time();

        $object = new fpcm\model\ips\ipaddress();
        $object->setIpaddress($GLOBALS['test_ip_address']);
        $object->setIptime($GLOBALS['test_ctime']);
        $object->setNoaccess(1);
        $object->setNocomments(1);
        $object->setNologin(1);
        $object->setUserid(1);

        $result = $object->save();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = $object->getId();
    }

}
