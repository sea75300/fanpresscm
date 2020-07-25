<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class moduleTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    protected function setUp() : void
    {
        
    }

    public function testUpdateFromFilesystem()
    {
        $res = (new fpcm\module\modules())->updateFromFilesystem();
        $this->assertTrue($res);
    }

    public function testInstall()
    {
        $GLOBALS['module'] = new \fpcm\module\module('nkorg/example');
        $success = $GLOBALS['module']->install(true);

        $this->assertTrue($success);
        $this->assertTrue($GLOBALS['module']->isInstalled());

        $config = new fpcm\model\system\config(false);
        $this->assertEquals(1, $config->module_nkorgexample_opt1);
        $this->assertEquals(2, $config->module_nkorgexample_opt2);
        $this->assertEquals(3, $config->module_nkorgexample_opt3);
        $this->assertEquals(5, $config->module_nkorgexample_opt5);
        $this->assertEquals(6, $config->module_nkorgexample_opt6);

        /* @var $db \fpcm\classes\database */
        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab1', '*')), 'Fetch from table module_nkorgexample_tab1 failed');
        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab2', '*')), 'Fetch from table module_nkorgexample_tab2 failed');
        $this->assertGreaterThanOrEqual(1, $db->count(fpcm\classes\database::tableCronjobs, '*', 'modulekey = ?', ['nkorg/example']));

    }
    
    public function testSetOptions()
    {
        $this->assertTrue($GLOBALS['module']->hasConfigure());
        $testModOptions = $GLOBALS['module']->getOptions();
        
        $this->assertTrue($GLOBALS['module']->setOptions([
            'module_nkorgexample_opt1' => $testModOptions['module_nkorgexample_opt1'] * 10,
            'module_nkorgexample_opt2' => $testModOptions['module_nkorgexample_opt2'] * 100
        ]));

        $config = new fpcm\model\system\config(false);
        $this->assertEquals(10, $config->module_nkorgexample_opt1);
        $this->assertEquals(200, $config->module_nkorgexample_opt2);
    }

    public function testUpdate()
    {
        /* @var $db \fpcm\classes\database */
        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $dbResult = $db->delete(\fpcm\classes\database::tableConfig, "config_name IN ('module_nkorgexample_opt1', 'module_nkorgexample_opt2', 'module_nkorgexample_opt3')");
        $this->assertTrue($dbResult);

        $dbResult = $db->drop('module_nkorgexample_tab1');
        $this->assertTrue($dbResult, 'Unable to delete module_nkorgexample_tab1');

        $success = $GLOBALS['module']->update();

        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab1', '*')), 'Fetch from table module_nkorgexample_tab1 failed');
        $this->assertGreaterThanOrEqual(1, $db->count(fpcm\classes\database::tableCronjobs, '*', 'modulekey = ?', ['nkorg/example']));
    }

    public function testGetInstalledModules()
    {
        $testModList = (new fpcm\module\modules())->getInstalledDatabase();

        $testModKey = 'nkorg/example';

        $this->assertTrue(is_array($testModList));
        $this->assertArrayHasKey($testModKey, $testModList);

        $moduleObj = $testModList[$testModKey];
        $this->assertEquals($testModKey, $moduleObj->getConfig()->key);
    }

    public function testUninstall()
    {
        
        $this->markTestSkipped();
        
        $success = $GLOBALS['module']->uninstall();
        
        $this->assertTrue($success);
        $this->assertFalse($GLOBALS['module']->isInstalled());
        $this->assertFalse($GLOBALS['module']->isActive());

        $config = new fpcm\model\system\config();
        $this->assertFalse($config->module_nkorgexample_opt1);
        $this->assertFalse($config->module_nkorgexample_opt2);
        $this->assertFalse($config->module_nkorgexample_opt3);
        $this->assertFalse($config->module_nkorgexample_opt5);
        $this->assertFalse($config->module_nkorgexample_opt6);

        $config = new fpcm\model\system\config();
        $config->remove('module_nkorgexample_opt5');
        $config->remove('module_nkorgexample_opt5');
        
        /* @var $db \fpcm\classes\database */
        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->assertCount(0, $db->getTableStructure('module_nkorgexample_tab1', false, false));
        $this->assertEquals(0, $db->count(fpcm\classes\database::tableCronjobs, '*', 'modulekey = ?', ['nkorg/example']));
    }
}
