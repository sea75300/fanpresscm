<?php

class fpcmTests extends \PHPUnit\Framework\TestCase {

   public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite();
        
        $files = glob(__DIR__.'/testcases/*/*Test.php');

        if (!is_array($files)) {
            return $suite;
        }
        
        $suite->addTestFiles($files);

        return $suite;
    }

}
