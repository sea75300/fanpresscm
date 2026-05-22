<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system\testing;

/**
 * Testing1 controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class testing1
extends \fpcm\controller\abstracts\controller
implements 
    \fpcm\controller\interfaces\viewByNamespace,
    \fpcm\controller\interfaces\requestFunctions
{

    private $entries;

    public function isAccessible(): bool
    {
        return \fpcm\classes\baseconfig::debugModeActive();
    }

    /**
     * Controller processing
     * @return bool
     */
    public function process() : bool
    {
        
        $this->view->addButton(
            
                (new \fpcm\view\helper\submitButton('send'))->setText('Check')
                
        );
        
        $this->view->assign('texts', $this->request->fromPOST('text', []));
        
        $this->view->setFormAction('system/testing');
        
        return true;
    }

    protected function onSend() 
    {
        $text = $this->request->fromPOST('text');
        
        $items = explode(PHP_EOL, $text);
        
        $is = [];
        
        foreach ($items as $item) {
            
            
            $res = (new \fpcm\model\users\passCheck($item))->isPowned();
            if (!$res) {
                continue;
            }
            
            $is[] = $item;
        }
        
        
        if (count($is)) {
            $this->view->addErrorMessage("Powned:\n\n%s", [implode(', ', $is) ], true);
            return false;
        }
        
        $this->view->addNoticeMessage("Nothering found!");
        return true;
    }

}
