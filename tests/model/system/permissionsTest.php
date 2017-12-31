<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class permissionsTest extends testBase {
    
    /**
     * @var fpcm\model\system\permissions
     */
    protected $object;

    public function setUp() {
        
    }
    
    public function testUpdatePermissions() {

        $roll = new fpcm\model\users\userRoll();
        $roll->setRollName(__CLASS__.' '. microtime(true));
        $res = $roll->save();
        $this->assertGreaterThan(3, $res);
        
        $GLOBALS['roll_id'] = $res;

        $this->object = new fpcm\model\system\permissions($GLOBALS['roll_id']);
    
        $this->object->setPermissionData([
            'article' => [
                'editall'               => 1,
                'delete'              => 1,
            ]
        ]);
        
        $this->assertTrue($this->object->update());
        
    }
    
    public function testCheckPermissions() {

        $this->object = new fpcm\model\system\permissions($GLOBALS['roll_id']);
        $res = $this->object->check(['article' => ['add', 'edit', 'editall', 'delete']]);
        $this->assertTrue($res);

        $roll = new fpcm\model\users\userRoll($GLOBALS['roll_id']);
        $this->assertTrue($roll->delete());
        
    }


}