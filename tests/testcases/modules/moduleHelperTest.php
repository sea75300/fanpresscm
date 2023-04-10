<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class moduleHelperTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    protected function setUp() : void
    {
        
    }

    public function testValidateModuleKey()
    {
        $this->assertTrue(\fpcm\module\module::validateKey('nkorg/example'));
        $this->assertTrue(\fpcm\module\module::validateKey('nkorg/example_1'));
        $this->assertTrue(\fpcm\module\module::validateKey('nkorg/12example'));
        $this->assertTrue(\fpcm\module\module::validateKey('nkorg/example_test'));
        $this->assertFalse(\fpcm\module\module::validateKey('nkorg/-example'));
        $this->assertFalse(\fpcm\module\module::validateKey('12nkorg/-example'));
        $this->assertFalse(\fpcm\module\module::validateKey('/-example'));
        $this->assertFalse(\fpcm\module\module::validateKey('nk-org/example'));
        $this->assertFalse(\fpcm\module\module::validateKey('12nkorg\-example'));
        $this->assertFalse(\fpcm\module\module::validateKey('test/nkorg/-example'));
    }

    public function testGetLanguageVarPrefixed()
    {
        $this->assertEquals('MODULE_NKORGEXAMPLE_',  \fpcm\module\module::getLanguageVarPrefixed('nkorg/example'));
    }

    public function testGetModuleUrlFromKey()
    {
        $this->assertStringContainsString('/data/modules/nkorg/example',  \fpcm\module\module::getModuleUrlFromKey('nkorg/example'));
    }

    public function testGetModuleBasePathFromKey()
    {
        $this->assertStringContainsString('/data/modules/nkorg/example',  \fpcm\module\module::getModuleBasePathFromKey('nkorg/example'));
    }

    public function testGetLanguageFileByKey()
    {
        $this->assertStringContainsString('/data/modules/nkorg/example/lang/de.php',  \fpcm\module\module::getLanguageFileByKey('nkorg/example', 'de'));
    }

    public function testGetStyleDirByKey()
    {
        $this->assertStringContainsString('/data/modules/nkorg/example/style/test.css',  \fpcm\module\module::getStyleDirByKey('nkorg/example', 'test.css'));
    }

    public function testGetJsDirByKey()
    {
        $this->assertStringContainsString('/data/modules/nkorg/example/js/test.js',  \fpcm\module\module::getJsDirByKey('nkorg/example', 'test.js'));
    }

    public function testGetTemplateDirByKey()
    {
        $this->assertStringContainsString('/data/modules/nkorg/example/templates/test.php',  \fpcm\module\module::getTemplateDirByKey('nkorg/example', 'test.php'));
    }

    public function testGetConfigByKey()
    {
        $this->assertStringContainsString('/data/modules/nkorg/example/config/test.yml',  \fpcm\module\module::getConfigByKey('nkorg/example', 'test.yml'));
    }

    public function testGetMigrationNamespace()
    {
        $this->assertStringContainsString('\fpcm\modules\nkorg\example\migrations\test123',  \fpcm\module\module::getMigrationNamespace('nkorg/example', 'test123'));
    }

    public function testGetCronNamespace()
    {
        $this->assertStringContainsString('\fpcm\modules\nkorg\example\crons\test123',  \fpcm\module\module::getCronNamespace('nkorg/example', 'test123'));
    }

    public function testGetControllerNamespace()
    {
        $this->assertStringContainsString('\fpcm\modules\nkorg\example\controller\test123',  \fpcm\module\module::getControllerNamespace('nkorg/example', 'test123'));
    }

    public function testGetEventNamespace()
    {
        $this->assertStringContainsString('\fpcm\modules\nkorg\example\events\test123',  \fpcm\module\module::getEventNamespace('nkorg/example', 'test123'));
    }

    public function testGetKeyFromClass()
    {
        $this->assertStringContainsString('nkorg/example',  \fpcm\module\module::getKeyFromClass('\fpcm\modules\nkorg\example\events\test123'));
    }

    public function testGetKeyFromFilename()
    {
        $this->assertStringContainsString('nkorg/example',  \fpcm\module\module::getKeyFromFilename('nkorg_example_version1.2.3.zip'));
    }

    public function testGetKeyFromPath()
    {
        $base = dirname(dirname(dirname(__DIR__)));
        
        $this->assertStringContainsString('nkorg/example',  \fpcm\module\module::getKeyFromPath($base . '/data/modules/nkorg/example'));
    }
    
}
