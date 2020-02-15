<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class permissionsTest extends testBase {

    /**
     * @var \fpcm\model\permissions\permissions
     */
    protected $object;

    public function setUp()
    {
        
    }

    public function testUpdatePermissions()
    {

        $roll = new fpcm\model\users\userRoll();
        $roll->setRollName(__CLASS__ . ' ' . microtime(true));
        $res = $roll->save();
        $this->assertGreaterThan(3, $roll->getId());

        $GLOBALS['roll_id'] = $roll->getId();

        $this->object = new \fpcm\model\permissions\permissions($GLOBALS['roll_id']);

        $this->object->setPermissionData([
            'article' => [
                'editall' => 1,
                'delete' => 1,
            ]
        ]);

        $this->assertTrue($this->object->update());
    }

    public function testCheckPermissions()
    {
        $this->object = new \fpcm\model\permissions\permissions($GLOBALS['roll_id']);
        $this->assertEquals(1, $this->object->article->editall);
        $this->assertEquals(1, $this->object->article->delete);
        
        $this->assertTrue($this->object->article->add || $this->object->article->edit || $this->object->article->editall || $this->object->article->delete);

        $roll = new fpcm\model\users\userRoll($GLOBALS['roll_id']);
        $this->assertTrue($roll->delete());
    }

}
