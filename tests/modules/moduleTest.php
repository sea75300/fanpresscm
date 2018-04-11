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
        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab1', '*')));
    }

    public function testUninstall()
    {
        $module = new fpcm\modules\module('nkorg/example');
        $success = $module->uninstall();
        
        $this->assertTrue($success);
        $this->assertFalse($module->isInstalled());
        $this->assertFalse($module->isActive());

        $config = new fpcm\model\system\config();
        $this->assertFalse($config->module_nkorgexample_opt1);
        $this->assertFalse($config->module_nkorgexample_opt2);
        $this->assertFalse($config->module_nkorgexample_opt3);
        $this->assertFalse($config->module_nkorgexample_opt5);
        $this->assertFalse($config->module_nkorgexample_opt6);

        /* @var $db \fpcm\classes\database */
        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        
        $db->getTableStructure('module_nkorgexample_tab1');
        $this->assertCount(0, $db->getTableStructure('module_nkorgexample_tab1'));
    }

}
