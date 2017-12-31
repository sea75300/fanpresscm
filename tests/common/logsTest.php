<?php

require_once dirname(dirname(__DIR__)).'/inc/common.php';

class logsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    public function setUp() {

        $GLOBALS['testMessage'] = 'Unit Test Message';
        
    }
    
    public function testLogError() {
        trigger_error($GLOBALS['testMessage']);
        $this->checkMessage(\fpcm\model\files\logfile::FPCM_LOGFILETYPE_PHP);
    }
    
    public function testLogSystem() {
        $this->assertTrue(fpcmLogSystem($GLOBALS['testMessage']));
        $this->checkMessage(\fpcm\model\files\logfile::FPCM_LOGFILETYPE_SYSTEM);
    }
    
    public function testLogSql() {
        $this->assertTrue(fpcmLogSql($GLOBALS['testMessage']));
        $this->checkMessage(\fpcm\model\files\logfile::FPCM_LOGFILETYPE_SQL);
    }
    
    public function testLogCron() {
        $this->assertTrue(fpcmLogCron($GLOBALS['testMessage']));
        $this->checkMessage(\fpcm\model\files\logfile::FPCM_LOGFILETYPE_CRON);
    }
    
    public function testLogPkgMgr() {
        $this->assertTrue(fpcmLogPackages('unittest', [$GLOBALS['testMessage']]));
        
        $logfile = new \fpcm\model\files\logfile(\fpcm\model\files\logfile::FPCM_LOGFILETYPE_PKGMGR);
        $data    = $logfile->fetchData();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));
        
        $data = array_pop($data);
        $this->assertTrue(is_object($data));
        $this->assertContains($GLOBALS['testMessage'], array_pop($data->text));
        $this->assertContains('unittest', $data->pkgname);
    }
    
    private function checkMessage($type) {

        $logfile = new \fpcm\model\files\logfile($type);
        $data    = $logfile->fetchData();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));
        
        $data = array_pop($data);
        $this->assertTrue(is_object($data));
        $this->assertContains($GLOBALS['testMessage'], $data->text);

    }

}