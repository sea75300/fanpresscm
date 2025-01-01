<?php

require_once dirname(dirname(__FILE__)).'/inc/common.php';

class ops extends \PHPUnit\Framework\TestCase {

    public function testCopyRecursive()
    {
        $res = fpcm\model\files\ops::copyRecursive(dirname(__DIR__), sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fpcmtest');
        $this->assertTrue($res);
        $this->assertTrue(file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fpcmtest'));
    }

    public function testDeleteRecursive()
    {
        $res = fpcm\model\files\ops::deleteRecursive(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fpcmtest');
        $this->assertTrue($res);
        $this->assertFalse(file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fpcmtest'));
    }

    public function testRemoveBaseDir()
    {
        $res = fpcm\model\files\ops::removeBaseDir(__DIR__);
        $this->assertStringNotContainsString(DIRECTORY_SEPARATOR . 'fanpress', $res);
        $this->assertStringEndsWith($res, __DIR__);
    }

    public function testRealpathNoExists()
    {
        $res = fpcm\model\files\ops::realpathNoExists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'testfile.txt');

        $dest = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR .  'testfile.txt';
        $this->assertTrue($dest === $res);
    }

    public function testIsValidDataFolder()
    {
        $res1 = fpcm\model\files\ops::isValidDataFolder(
            dirname(dirname((__DIR__))) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'phplog.txt',
            \fpcm\classes\dirs::DATA_LOGS
        );

        $this->assertTrue($res1);

        $res2 = fpcm\model\files\ops::isValidDataFolder(
            sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fpcmtest',
            \fpcm\classes\dirs::DATA_LOGS
        );

        $this->assertFalse($res2);

        $res3 = fpcm\model\files\ops::isValidDataFolder(
            dirname(dirname((__DIR__))) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . '...' . DIRECTORY_SEPARATOR . '...' . DIRECTORY_SEPARATOR . 'phplog.txt',
            \fpcm\classes\dirs::DATA_LOGS
        );

        $this->assertFalse($res3);

    }

    public function testHashFile()
    {

        $hash1 = fpcm\model\files\ops::hashFile(__FILE__);
        $hash2 = hash_file('sha256', __FILE__);

        $this->assertTrue($hash1 === $hash2);

    }


}
