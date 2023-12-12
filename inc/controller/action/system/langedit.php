<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Language file editor
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class langedit extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\requestFunctions
{

    /**
     *
     * @var string
     */
    private $langCode = null;
    /**
     *
     * @var \fpcm\classes\language
     */
    private $langObj = null;

    public function isAccessible(): bool
    {
        return $this->permissions->system->options && defined('FPCM_LANG_XML') && FPCM_LANG_XML;
    }

    public function request()
    {
        $this->langCode = $this->request->fromPOST('langselect');
        $this->langObj = $this->langCode !== null ? new \fpcm\classes\language($this->langCode) : $this->language;
        $this->view = new \fpcm\view\view;
        if ($this->langCode === '') {
            return false;
        }

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $skipVal = '{{skip}}';

        $this->view->addTabs('langedit', [
            (new \fpcm\view\helper\tabItem('editor'))->setText('Language variable editor')->setFile('system/langedit.php'), 
        ]);

        $this->cache->cleanup('system/langcache' . strtoupper($this->langObj->getLangCode()));
        
        $fullLang = $this->langObj->getAll(true);

        array_walk($fullLang, function (&$value, $index) use ($skipVal) {
            $value = strpos(strtoupper($index), 'MODULE_') !== FALSE ? $skipVal : $value;
        });
        
        $fullLang = array_diff_key($fullLang, array_flip(array_keys($fullLang, $skipVal)));
        $this->view->addButtons([
            (new \fpcm\view\helper\saveButton('save'))->setPrimary(),
            (new \fpcm\view\helper\button('new'))->setText('Neue Variable')->setIcon('plus')
        ]);
        
        $this->view->addToolbarRight([
            (new \fpcm\view\helper\select('langselect'))
                ->setOptions(array_flip($this->language->getLanguages()))
                ->setSelected($this->langObj->getLangCode())
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED),
            (new \fpcm\view\helper\submitButton('selectLang'))->setText('GLOBAL_OK')->setIcon('check')->setIconOnly()
        ]);
        
        ksort($fullLang);
        
        $this->view->setFormAction('system/langedit');
        $this->view->assign('langVars', $fullLang);
        $this->view->addJsFiles(['system/langedit.js']);
        $this->view->render();
    }

    public function onSave()
    {
        $langsave = $this->request->fromPOST('lang', [\fpcm\model\http\request::FILTER_TRIM ]);
        ksort($langsave);
        
        $lists = array_filter($langsave, function ($value) {
            return (substr($value, 0, 2) === 'a:') ? true : false;
        });
        
        $vars = array_diff($langsave, $lists);
        array_walk($vars, function (&$value) {
            $value = str_replace(\fpcm\classes\language::VARTEXT_NEWLINE, PHP_EOL, $value);
        });

        $res = $this->langObj->saveFiles(
            $vars,
            array_map('unserialize', $lists)
        );

        if (!$res) {
            $this->view->addErrorMessage('Fehler beim speichern, Error-Log-prüfen!');
            return false;
        }

        $this->view->addNoticeMessage('Änderungen gespeichert.');
        return true;
        
    }
    
}
