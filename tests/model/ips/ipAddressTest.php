<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class ipAddressTest extends testBase {

    public function setUp()
    {
        $this->className = 'ips\\ipaddress';
        parent::setUp();
    }

    public function testSave()
    {
        /* @var $object fpcm\model\ips\ipaddress */
        $object = $this->object;

        $GLOBALS['test_ip_address'] = '32.64.128.255';
        $GLOBALS['test_ctime'] = time();

        $object->setIpaddress($GLOBALS['test_ip_address']);
        $object->setIptime($GLOBALS['test_ctime']);
        $object->setNoaccess(1);
        $object->setNocomments(0);
        $object->setNologin(0);
        $object->setUserid(1);

        $result = $object->save();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = $object->getId();
    }

    public function testUpdate()
    {
        /* @var $object fpcm\model\ips\ipaddress */
        $object = $this->object;

        $object->setNoaccess(1);
        $object->setNocomments(1);
        $object->setNologin(1);
        
        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetItem()
    {
        /* @var $object fpcm\model\ips\ipaddress */
        $object = new fpcm\model\ips\ipaddress($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['test_ip_address'], $object->getIpaddress());
        $this->assertEquals($GLOBALS['test_ctime'], $object->getIptime());
        $this->assertEquals(1, $object->getNoaccess());
        $this->assertEquals(1, $object->getNocomments());
        $this->assertEquals(1, $object->getNologin());
    }

    public function testDelete()
    {
        /* @var $object fpcm\model\ips\ipaddress */
        $object = new fpcm\model\ips\ipaddress($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = null;
    }

}
