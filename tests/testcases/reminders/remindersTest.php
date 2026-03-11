<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class remindersTest extends testBase {

    protected function setUp() : void
    {
        $this->className = 'reminders\\reminders';
        parent::setUp();
    }

    public function testGetRemindersForDatasets()
    {
        $this->createReminder();
        /* @var $object fpcm\model\reminders\reminders */
        $object = $this->object;
        
        $test1 = $object->getRemindersForDatasets(
            \fpcm\model\files\mediaFile::class,
            time(),
            [ $GLOBALS['test_reminder_oid'] ],
            $GLOBALS['test_reminder_user']
        );
        
        $this->assertIsArray($test1);
        $this->assertTrue(isset($test1[$GLOBALS['test_reminder_oid']]));

        $test2 = $object->getRemindersForDatasets(
            \fpcm\model\files\mediaFile::class,
            0,
            [],
            $GLOBALS['test_reminder_user']
        );
        
        $this->assertIsArray($test2);
        $this->assertTrue(isset($test2[$GLOBALS['test_reminder_oid']]));
        
        $del = new fpcm\model\reminders\reminder($GLOBALS['objectId']);
        $this->assertTrue($del->delete());
        
    }

    public function testDeleteReminders()
    {
        $object = new fpcm\model\reminders\reminders();
        $result = $object->removeByObject(\fpcm\model\files\mediaFile::class, [ $GLOBALS['test_reminder_oid'] ]);
        $this->assertTrue($result);
    }
    
    private function createReminder()
    {
        $object = new fpcm\model\reminders\reminder();
        
        $GLOBALS['test_reminder_comment'] = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.';
        $GLOBALS['test_reminder_date'] = time()-60;
        $GLOBALS['test_reminder_user'] = 999;
        $GLOBALS['test_reminder_oid'] = 999;
        $GLOBALS['test_reminder_obj'] = \fpcm\model\files\mediaFile::class;

        $object->setComment($GLOBALS['test_reminder_comment']);
        $object->setTime($GLOBALS['test_reminder_date']);
        $object->setUserID($GLOBALS['test_reminder_user']);
        $object->setOid($GLOBALS['test_reminder_oid']);
        $object->setObjName($GLOBALS['test_reminder_obj']);

        $result = $object->save();
        $this->assertTrue($result);

        $GLOBALS['objectId'] = $object->getId();    
    }

}
