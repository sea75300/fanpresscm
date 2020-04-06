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
            (new \fpcm\view\helper\tabItem('editor'))->setText('Language variable editor')->setFile('system/langedit.php'), 
        ]);
        
        $skipVal = '{{skip}}';

        $fullLang = $this->language->getAll();

        array_walk($fullLang, function (&$value, $index) use ($skipVal) {
            $value = strpos(strtoupper($index), 'MODULE_') !== FALSE ? $skipVal : $value;
        });
        
        $fullLang = array_diff_key($fullLang, array_flip(array_keys($fullLang, $skipVal)));

        $this->view->addButton((new \fpcm\view\helper\saveButton('save')));
        
        ksort($fullLang);
        
        $this->view->setFormAction('system/langedit');
        $this->view->assign('langVars', $fullLang);
        $this->view->addJsFiles(['langedit.js']);
        $this->view->render();
    }

    public function onSave()
    {
        $langsave = $this->request->fromPOST('lang');
        
        array_walk($langsave, function (&$value, $index) {
            
            if (strpos($value, '{"') === false) {
                return true;
            }

            $value = json_decode($value, true);
        });
        
        ksort($langsave);

        $tmp1 = new \fpcm\model\files\tempfile('langedit');
        $tmp1->setContent('<?php' . PHP_EOL . PHP_EOL . "/**\n* FanPress CM 4.x\n* Language file\n* @license http://www.gnu.org/licenses/gpl.txt GPLv3\n*/".PHP_EOL.PHP_EOL.'$lang = ' . var_export($langsave, true).PHP_EOL);
        $tmp1->save();

        unset($tmp1);
        
        $tmp2 = new \fpcm\model\files\tempfile('langedit');
        $tmp2->loadContent();
        
        print "<pre>";
        print htmlentities($tmp2->getContent());
        print "</pre>";
        
        $tmp2->delete();
        
        return true;
        
    }
}

?>
