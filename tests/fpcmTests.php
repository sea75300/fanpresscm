<?php

class fpcmTests extends \PHPUnit_Framework_TestCase {

   public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite();
        
        $files = glob(__DIR__.'/*/*/*Test.php');

        if (!is_array($files)) {
            return $suite;
        }
        
        $suite->addTestFiles($files);

        return $suite;
    }

}
