<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Testing controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class testing extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\viewByNamespace
{

    public function isAccessible(): bool
    {
        return defined('FPCM_DEBUG') && FPCM_DEBUG;
    }

    /**
     * 
     * @return bool
     */
    public function process() : bool
    {       
        $this->view->addJsFiles([
            'testing.js'
        ]);
        
        $intv = new \DateInterval('P1M');
        
        $prev = new \DateTime();
        $prev->sub($intv);

        $next = new \DateTime();
        $next->add($intv);
        
        
        
        $this->view->addButton( (new \fpcm\view\helper\button('prev'))->setText('Zurück')->setData(['month' => $prev->format('Y-m') ])->setOnClick('testing.update') );
        $this->view->addButton( (new \fpcm\view\helper\button('next'))->setText('Weiter')->setData(['month' => $next->format('Y-m') ])->setOnClick('testing.update') );
        
        $this->view->assign('progressbarName', 'testing');
        
        $this->view->setJsModuleFiles(['/calendar.js']);

        return true;
    }

}
