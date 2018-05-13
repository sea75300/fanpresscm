<?php

require_once dirname(dirname(__DIR__)) . '/inc/common.php';

class moduleTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    public function setUp()
    {
        
    }

    public function testInstall()
    {
        $module = new fpcm\modules\module('nkorg/example');
        $success = $module->install();

        $this->assertTrue($success);
        $this->assertTrue($module->isInstalled());
        $this->assertGreaterThanOrEqual(1, $module->getId());

        $config = new fpcm\model\system\config();
        $this->assertEquals(1, $config->module_nkorgexample_opt1);
        $this->assertEquals(2, $config->module_nkorgexample_opt2);
        $this->assertEquals(3, $config->module_nkorgexample_opt3);
        $this->assertEquals(5, $config->module_nkorgexample_opt5);
        $this->assertEquals(6, $config->module_nkorgexample_opt6);

        /* @var $db \fpcm\classes\database */
        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab1', '*')), 'Fetch from table module_nkorgexample_tab1 failed');
        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab2', '*')), 'Fetch from table module_nkorgexample_tab2 failed');
    }

    public function testUpdate()
    {
        /* @var $db \fpcm\classes\database */
        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $dbResult = $db->delete(\fpcm\classes\database::tableConfig, "config_name IN ('module_nkorgexample_opt1', 'module_nkorgexample_opt2', 'module_nkorgexample_opt3')");
        $this->assertTrue($dbResult);

        $dbResult = $db->drop('module_nkorgexample_tab1');
        $this->assertTrue($dbResult);

        $module = new fpcm\modules\module('nkorg/example');
        $success = $module->update();

        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab1', '*')), 'Fetch from table module_nkorgexample_tab1 failed');
    }

    public function testGetInstalledModules()
    {
        $list = new \fpcm\modules\modules();
        $modules = $list->getInstalledModules();

        $key = 'nkorg/example';

        $this->assertTrue(is_array($modules));
        $this->assertArrayHasKey($key, $modules);

        /* @var $module fpcm\modules\module */
        $module = $modules[$key];
        $this->assertEquals($key, $module->getConfig()->key);
    }

//    public function testUninstall()
//    {
//        $module = new fpcm\modules\module('nkorg/example');
//        $success = $module->uninstall();
//        
//        $this->assertTrue($success);
//        $this->assertFalse($module->isInstalled());
//        $this->assertFalse($module->isActive());
//
//        $config = new fpcm\model\system\config();
//        $this->assertFalse($config->module_nkorgexample_opt1);
//        $this->assertFalse($config->module_nkorgexample_opt2);
//        $this->assertFalse($config->module_nkorgexample_opt3);
//        $this->assertFalse($config->module_nkorgexample_opt5);
//        $this->assertFalse($config->module_nkorgexample_opt6);
//
//        /* @var $db \fpcm\classes\database */
//        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
//        
//        $db->getTableStructure('module_nkorgexample_tab1');
//        $this->assertCount(0, $db->getTableStructure('module_nkorgexample_tab1'));
//    }
}
