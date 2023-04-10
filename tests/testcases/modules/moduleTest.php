<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class moduleTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;
    
    protected $testMkey = 'nkorg/example';

    protected function setUp() : void
    {
        
    }

    public function testUpdateFromFilesystem()
    {
        $res = (new fpcm\module\modules())->updateFromFilesystem();
        $this->assertTrue($res);
    }

    public function testInstallDownload()
    {
        if ((new \fpcm\module\module($this->testMkey))->isInstalled()) {
            $this->markTestSkipped($this->testMkey . ' already installed! Skip downloading');
        }

        $pkg = new \fpcm\model\packages\module($this->testMkey);
            
        $this->assertTrue( $pkg->download() );
        $this->assertTrue( $pkg->checkPackage() );
        $this->assertTrue( $pkg->extract() );
        $this->assertTrue( $pkg->copy() );
        $this->assertTrue( $pkg->updateLog() );
        $this->assertTrue( $pkg->cleanup() );
        
    }

    public function testInstall()
    {
        /* @var fpcm\module\module $GLOBALS['module'] */
        $GLOBALS['module'] = new \fpcm\module\module($this->testMkey);
        $this->assertDirectoryExists($GLOBALS['module']->getConfig()->basePath);
        $this->assertFalse($GLOBALS['module']->isInstalled(), sprintf('%s is already installed on this test instance', $this->testMkey));
        
        $success = $GLOBALS['module']->install();

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

        $this->assertGreaterThanOrEqual(1, $db->count(fpcm\classes\database::tableCronjobs, '*', 'modulekey = ?', [$this->testMkey]));

        $struct1 = $db->getTableStructure('module_nkorgexample_tab1');
        $this->assertIsArray($struct1);
        $this->assertGreaterThan(0, count($struct1));

        $tab1 = $db->selectFetch( (new \fpcm\model\dbal\selectParams('module_nkorgexample_tab1')) );        
        $this->assertNotFalse($tab1);

        $struct2 = $db->getTableStructure('module_nkorgexample_tab2');
        $this->assertIsArray($struct2);
        $this->assertGreaterThan(0, count($struct2));

        $tab2 = $db->selectFetch( (new \fpcm\model\dbal\selectParams('module_nkorgexample_tab2')) );
        $this->assertNotFalse($tab2);
        
        $this->assertTrue($GLOBALS['module']->enable());
        $this->assertStringContainsString('/data/module_nkorgexample', $GLOBALS['module']->getDataPath());
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

        (new \fpcm\classes\cache)->cleanup();

        $success = $GLOBALS['module']->update();

        $this->assertNotFalse($db->fetch($db->select('module_nkorgexample_tab1', '*')), 'Fetch from table module_nkorgexample_tab1 failed');
        $this->assertGreaterThanOrEqual(1, $db->count(fpcm\classes\database::tableCronjobs, '*', 'modulekey = ?', [$this->testMkey]));

    }

    public function testGetFromDatabase()
    {
        $testModList = (new fpcm\module\modules())->getFromDatabase();

        $testModKey = $this->testMkey;

        $this->assertTrue(is_array($testModList));
        $this->assertArrayHasKey($testModKey, $testModList);

        $moduleObj = $testModList[$testModKey];
        $this->assertEquals($testModKey, $moduleObj->getConfig()->key);        
    }

    public function testGetInstalledDatabase()
    {
        $testModList = (new fpcm\module\modules())->getInstalledDatabase();

        $testModKey = $this->testMkey;

        $this->assertTrue(is_array($testModList));
        $this->assertArrayHasKey($testModKey, $testModList);

        $moduleObj = $testModList[$testModKey];
        $this->assertEquals($testModKey, $moduleObj->getConfig()->key);        
    }

    public function testGetEnabledDatabase()
    {
        $testModList = (new fpcm\module\modules())->getEnabledDatabase();

        $testModKey = $this->testMkey;

        $this->assertTrue(is_array($testModList));
        $this->assertContains($testModKey, $testModList);  
    }

    public function testGetFromRepository()
    {
        $testModList = (new fpcm\module\modules())->getFromRepository();

        $testModKey = $this->testMkey;

        $this->assertTrue(is_array($testModList));
        $this->assertArrayHasKey($testModKey, $testModList);  
    }

    public function testUninstall()
    {
        unset($GLOBALS['fpcm']['objects']['fpcm\classes\database'], $GLOBALS['module']);
        
        $GLOBALS['module'] = new fpcm\module\module($this->testMkey);
        $success = $GLOBALS['module']->uninstall();
        
        $this->assertTrue($success, 'Error while uninstalling module.');
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
        $this->assertEquals(0, $db->count(fpcm\classes\database::tableCronjobs, '*', 'modulekey = ?', [$this->testMkey]));
    }
}
