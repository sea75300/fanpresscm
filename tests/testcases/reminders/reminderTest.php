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
        $GLOBALS['test_reminder_date'] = mktime(23, 59, 59, 12, 31, date('Y'));
        $GLOBALS['test_reminder_user'] = 9999;
        $GLOBALS['test_reminder_oid'] = 9999;
        $GLOBALS['test_reminder_obj'] = \fpcm\model\files\mediaFile::class;

        $object->setComment($GLOBALS['test_reminder_comment']);
        $object->setTime($GLOBALS['test_reminder_date']);
        $object->setUserID($GLOBALS['test_reminder_user']);
        $object->setOid($GLOBALS['test_reminder_oid']);
        $object->setObjName($GLOBALS['test_reminder_obj']);

        $result = $object->save();
        $this->assertTrue($result);

        $GLOBALS['remOId'] = $object->getId();
    }

    public function testUpdate()
    {
        $object = new fpcm\model\reminders\reminder($GLOBALS['remOId']);
        $object->setTime(mktime(0, 0, 0, 1, 10, date('Y')+1));
        
        $result = $object->update();
        $this->assertTrue($result);
    }

    public function testGetReminder()
    {        
        /* @var $object fpcm\model\reminders\reminder */
        $object = new fpcm\model\reminders\reminder($GLOBALS['remOId']);

        $this->assertTrue($object->exists());
        $this->assertEquals($GLOBALS['test_reminder_comment'], $object->getComment());
        $this->assertEquals(mktime(0, 0, 0, 1, 10, date('Y')+1), $object->getTime());
        $this->assertEquals($GLOBALS['test_reminder_user'], $object->getUserID());
        $this->assertEquals($GLOBALS['test_reminder_oid'], $object->getOid());
        $this->assertEquals($GLOBALS['test_reminder_obj'], $object->getObjName());
    }

    public function testDelete()
    {
        /* @var $object fpcm\model\reminders\reminder */
        $object = new fpcm\model\reminders\reminder($GLOBALS['remOId']);

        $result = $object->delete();
        $this->assertTrue($result);
    }

    public function testDeleteReminders()
    {
        $object = new fpcm\model\reminders\reminders();
        $result = $object->removeByObject(\fpcm\model\files\mediaFile::class, [ $GLOBALS['test_reminder_oid'] ]);
        $this->assertTrue($result);
    }

}
