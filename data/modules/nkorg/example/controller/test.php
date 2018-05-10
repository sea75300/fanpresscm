<?php

namespace fpcm\modules\nkorg\example\controller;

final class test extends \fpcm\controller\abstracts\controller {

    protected function getViewPath()
    {
        return 'test';
    }
    
    public function process()
    {
        return true;
    }

    public function hasAccess()
    {
        return true;
    }

}