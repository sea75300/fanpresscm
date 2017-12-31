<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class configTest extends testBase {
    
    /**
     * @var fpcm\model\system\config
     */
    protected $object;

    public function setUp() {
        $this->object = new fpcm\model\system\config(false, false);
    }
    
    public function testAddKey() {
        $GLOBALS['newKey']   = 'config_option_unittest';
        $GLOBALS['newValue'] = '1234567890';

        $GLOBALS['newKey2']   = 'config_option_unittest2';
        $GLOBALS['newValue2'] = '1234567890';

        $result = $this->object->add($GLOBALS['newKey'], $GLOBALS['newValue']);
        $result = $this->object->add($GLOBALS['newKey2'], $GLOBALS['newValue2']);
        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertEquals($GLOBALS['newValue'], $this->object->{$GLOBALS['newKey']});
        
    }
    
    public function testUpdateKey() {

        $GLOBALS['newValue'] = '9876543210';
        $GLOBALS['newValue'] = 'This is a test...';

        $this->object->setNewConfig([
            $GLOBALS['newKey'] => $GLOBALS['newValue'],
            $GLOBALS['newKey2'] => $GLOBALS['newValue2']
        ]);
        
        $this->object->prepareDataSave();

        $this->assertTrue($this->object->update());
        $this->assertEquals($GLOBALS['newValue'], $this->object->{$GLOBALS['newKey']});
        $this->assertEquals($GLOBALS['newValue2'], $this->object->{$GLOBALS['newKey2']});

    }
    
    public function testSetMaintenanceMode() {

        $this->assertTrue($this->object->setMaintenanceMode(1));
        $this->assertEquals(1, $this->object->system_maintenance);

        $this->assertTrue($this->object->setMaintenanceMode(0));
        $this->assertEquals(0, $this->object->system_maintenance);

    }
    
    public function testRemoveKey() {
        $this->assertTrue($this->object->remove($GLOBALS['newKey']));
        $this->assertTrue($this->object->remove($GLOBALS['newKey2']));
        $this->object->init();

        $this->object = new fpcm\model\system\config(false, false);
        $this->assertFalse($this->object->{$GLOBALS['newKey']});
        $this->assertFalse($this->object->{$GLOBALS['newKey2']});

    }

}