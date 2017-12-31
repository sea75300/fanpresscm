<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';

class userRollsTest extends testBase {
    
    /**
     * @var \fpcm\model\users\userRollList
     */
    protected $object;

    public function setUp() {
        $this->className = 'users\\userRollList';
        parent::setUp();
    }

    public function testGetUserRolls() {
        
        $this->createRoll();
        
        $data = $this->object->getUserRolls();
        $this->assertTrue(isset($data[$GLOBALS['objectId']]));
    }
    
    public function testGetUserRollsByIds() {
        $data = $this->object->getUserRollsByIds([$GLOBALS['objectId']]);
        $this->assertTrue(isset($data[$GLOBALS['objectId']]));
    }
    
    public function testGetRollsbyIdsTranslated() {
        $data = $this->object->getRollsbyIdsTranslated([$GLOBALS['objectId']]);
        $this->assertTrue(in_array($GLOBALS['objectId'], $data));
    }
    
    public function testGetUserRollsTranslated() {
        $data = $this->object->getUserRollsTranslated();
        $this->assertTrue(in_array($GLOBALS['objectId'], $data));
        
        $object = new fpcm\model\users\userRoll($GLOBALS['objectId']);
        $object->delete();
    }
    
    private function createRoll() {

        $GLOBALS['rollName']  = 'fpcmTestRoll'.microtime(true);
        
        /* @var $object fpcm\model\users\userRoll */
        $object = new fpcm\model\users\userRoll();
        $object->setRollName($GLOBALS['rollName']);

        $result = $object->save();
        $this->assertGreaterThanOrEqual(4, $result);
        
        $GLOBALS['objectId'] = $object->getId();
    }

}