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
    
    private $entries;

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
        
        $this->createEntries();
        
        $intv = new \DateInterval('P1M');
        
        $prev = new \DateTime();
        $prev->sub($intv);

        $next = new \DateTime();
        $next->add($intv);
        
        
        
        $this->view->addButton( (new \fpcm\view\helper\button('prev'))->setText('Zurück')->setData(['month' => $prev->format('Y-m') ])->setOnClick('testing.update') );
        $this->view->addButton( (new \fpcm\view\helper\button('next'))->setText('Weiter')->setData(['month' => $next->format('Y-m') ])->setOnClick('testing.update') );
        
        $this->view->assign('progressbarName', 'testing');
        
        $this->view->setJsModuleFiles(['/calendar.js']);
        $this->view->addJsVars(['centries' => $this->entries]);

        return true;
    }
    
    private function createEntries()
    {
    
        $intv = new \DateInterval('P1D');
        
        $d = new \DateTime();
        $d->setTime(0, 0, 0);
        
        for ($i = 0; $i < 10; $i++) {

            
            $this->entries[$d->format('Y-n-d')][] = [
                'label' => sprintf('%s: %s', 'Testtermin am', $d->format('d.m.Y')),
                'class' => 'btn-info bg-opacity-75',
                'src' => '',
            ];

            $d->add($intv);

        }
        
        
    }

}
