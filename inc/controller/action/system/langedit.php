<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Language file editor
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class langedit extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible, \fpcm\controller\interfaces\requestFunctions {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options && FPCM_DEBUG;
    }

    protected function getViewPath() : string
    {
        return 'system/langedit';
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->view->addTabs('langedit', [
            (new \fpcm\view\helper\tabItem('editor'))->setText('HL_LOGS_SESSIONS')->setFile('system/langedit.php'), 
        ]);
        
        $skipVal = '{{skip}}';

        $fullLang = $this->language->getAll();

        array_walk($fullLang, function (&$value, $index) use ($skipVal) {
            $value = strpos(strtoupper($index), 'MODULE_') !== FALSE ? $skipVal : $value;
        });
        
        $fullLang = array_diff_key($fullLang, array_flip(array_keys($fullLang, $skipVal)));

        $this->view->addButton((new \fpcm\view\helper\saveButton('save')));
        
        $this->view->setFormAction('system/langedit');
        $this->view->assign('langVars', $fullLang);
        $this->view->addJsFiles(['langedit.js']);
        $this->view->render();
    }

    public function onSave()
    {
        $langsave = $this->request->fromPOST('lang');
        
        foreach ($langsave as $key => $value) {
            
            if (strpos($value, '{"') === false) {
                continue;
            }
            
            fpcmDump(__METHOD__, $key, json_decode($value, true));
            
        }
        
        
        return true;
    }
}

?>
