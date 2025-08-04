<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class reminderTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'reminders\\reminder';
        parent::setUp();
    }

    public function testSave()
    {
        /* @var $object fpcm\model\reminders\reminder */
        $object = $this->object;

        $GLOBALS['test_reminder_comment'] = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.';
        $GLOBALS['test_reminder_date'] = time();
        $GLOBALS['test_reminder_user'] = 9999;
        $GLOBALS['test_reminder_oid'] = 9999;
        $GLOBALS['test_reminder_obj'] = 'files';

        $object->setComment($GLOBALS['test_reminder_comment']);
        $object->setTime($GLOBALS['test_reminder_date']);
        $object->setUserID($GLOBALS['test_reminder_user']);
        $object->setOid($GLOBALS['test_reminder_oid']);
        $object->setObjName($GLOBALS['test_reminder_obj']);

        $result = $object->save();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = $object->getId();
    }

    public function testUpdate()
    {
        /* @var $object fpcm\model\reminders\reminder */
        $object = $this->object;

        $object->setTime($GLOBALS['test_reminder_date'] + 3600);
        
        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetReminder()
    {
        /* @var $object fpcm\model\reminders\reminder */
        $object = new fpcm\model\reminders\reminder($GLOBALS['objectId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['test_reminder_comment'], $object->getComment());
        $this->assertEquals($GLOBALS['test_reminder_date'] + 3600, $object->getTime());
        $this->assertEquals($GLOBALS['test_reminder_user'], $object->getUserID());
        $this->assertEquals($GLOBALS['test_reminder_oid'], $object->getOid());
        $this->assertEquals($GLOBALS['test_reminder_obj'], $object->getObjName());

    }

    public function testDelete()
    {
        /* @var $object fpcm\model\reminders\reminder */
        $object = new fpcm\model\reminders\reminder($GLOBALS['objectId']);

        $result = $object->delete();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = null;
    }

}
