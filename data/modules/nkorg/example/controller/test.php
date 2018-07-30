<?php

namespace fpcm\modules\nkorg\example\controller;

final class test extends \fpcm\controller\abstracts\controller {

    protected function getViewPath(): string
    {
        return 'test';
    }

    public function hasAccess()
    {
        return true;
    }

    public function process()
    {
        $this->view->addNoticeMessage('MODULE_NKORGEXAMPLE_DESCRIPTION');
        return true;
    }

}