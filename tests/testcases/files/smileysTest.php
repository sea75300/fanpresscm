<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';

class smileysTest extends testBase {

    /**
     * @var fpcm\model\files\smileylist
     */
    protected $object;

    protected function setUp() : void
    {
        $this->className = 'files\\smileylist';

        $GLOBALS['imageCode'] = ':test:';
        $GLOBALS['imageName'] = 'smile.gif';

        parent::setUp();
    }

    public function testCreateSmiley()
    {
        /* @var $GLOBALS['imageObj'] \fpcm\model\files\smiley */
        $GLOBALS['imageObj'] = new \fpcm\model\files\smiley('', false);
        $GLOBALS['imageObj']->setSmileycode($GLOBALS['imageCode']);
        $GLOBALS['imageObj']->setFilename($GLOBALS['imageName']);
        $result = $GLOBALS['imageObj']->save();
        $this->assertGreaterThanOrEqual(1, $result);
        
        $GLOBALS['objectId'] = $result;
    }

    public function testGetDatabaseList()
    {
        $data = $this->object->getDatabaseList();

        $this->assertTrue(is_array($data));
        $this->assertGreaterThanOrEqual(1, count($data));
        
        foreach ($data as $row) {
            
            $this->assertTrue($row instanceof fpcm\model\files\smiley);
            if ($row->getId() !== $GLOBALS['objectId']) {
                continue;
            }

            $this->assertEquals($GLOBALS['imageName'], $row->getFilename());
            $this->assertEquals($GLOBALS['imageCode'], $row->getSmileyCode());
        }

    }

    public function testDeleteSmiley()
    {
        $this->assertTrue($GLOBALS['imageObj']->delete());
    }

}
