<?php

require_once dirname(dirname(dirname(__DIR__))) . '/fpcmapi.php';

class emailTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    protected function setUp() : void
    {
        
    }

    public function testSubmit()
    {

        $path = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, 'testfile.txt');
        $this->assertNotFalse(file_put_contents($path, 'This is a unit test temp testfile attachement.' . PHP_EOL));

        $email = new fpcm\classes\email('sea75300@yahoo.de', 'FPCM UnitTest test mail ' . microtime(true), '<h1>This is a test...</h1><p>This is as UnitTest test mail on ' . date('d.m.Y H:i:s') . '<p><p>ÖÄÜß!"§$%&/()=?+#*\',.-;:_</p>', 'fanpress@nobody-knows.org.', true);

        $email->setAttachments([
            $path
        ]);

        $this->assertTrue($email->submit());
        $this->assertTrue(unlink($path));
    }

}
