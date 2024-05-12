<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system\testing;

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
        
        for ($index = -36; $index <= 12; $index++) {
            
            
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
    
        $intv = new \DateInterval('P2D');
        
        $d = new \DateTime();

        for ($i = 0; $i < 28; $i++) {
            $ci = new \fpcm\model\calendar\item($d);
            $ci->setLabel('Testtermin am %s', [ $ci->getDate() ], true);
            $this->entries[$ci->getId()][] = $ci;
            $d->add($intv);
        }

        $ci2 = new \fpcm\model\calendar\item('2023-04-06');
        $ci2->setLabel('Enjoy!')->setClass('btn-danger bg-opacity-75');
        
        $this->entries[$ci2->getId()][] = $ci2;
    }

}
