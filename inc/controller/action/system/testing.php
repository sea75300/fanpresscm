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
        
        $drop = new \fpcm\view\helper\select('calMonths');
        
        $opts = [];
        
        for ($index = -18; $index <= 6; $index++) {
            
            
            $intv = new \DateInterval('P'.abs($index).'M');
            $date = new \DateTime();
            
            if ($index < 0) {
                $date->sub($intv);
            }
            elseif ($index > 0) {
                $date->add($intv);
            }

            $opts[$date->format('F Y')] = $date->format('Y-m');
            
        }
        
        $drop->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED);
        $drop->setOptions($opts);
        $drop->setSelected(date('Y-m'));

        $this->view->addButton($drop);
        
        
        $this->view->assign('progressbarName', 'testing');
        
        $this->view->setJsModuleFiles(['/testing.js']);
        $this->view->addJsVars(['centries' => $this->entries]);

        return true;
    }
    
    private function createEntries()
    {
    
        $intv = new \DateInterval('P1D');
        
        $d = new \DateTime();
        $d->setTime(0, 0, 0);
        
        for ($i = 0; $i < 10; $i++) {

            
            $this->entries[$d->format('Y-n-j')][] = [
                'label' => sprintf('%s: %s', 'Testtermin am', $d->format('d.m.Y')),
                'class' => 'btn-info bg-opacity-75',
                'src' => '',
            ];

            $d->add($intv);

        }
        
        $d2 = new \DateTime('2023-04-06');
        $d2->setTime(0, 0, 0);        
        
        $this->entries[$d2->format('Y-n-j')][] = [
            'label' => sprintf('%s %s', 'Enjoy!', $d2->format('d.m.Y')),
            'class' => 'btn-danger bg-opacity-75',
            'src' => '',
        ];        
        
        
    }

}
