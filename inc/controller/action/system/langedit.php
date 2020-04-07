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
     * @var \SimpleXMLElement
     */
    private $xml;

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
        ksort($langsave);
        
        $this->xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><langvars></langvars>', null, false);
        
        array_walk($langsave, function ($value, $descr) {

            $child = $this->xml->addChild('langvar');
            $child->addAttribute('var', $descr);

            if (strpos($value, '{"') === false) {
                $child->addAttribute('value', $value);
                return true;
            }

            $value = json_decode($value, true);            
            if (!is_array($value)) {
                return true;
            }

            $childVal = $child->addChild('list');
            foreach ($value as $key => $val) {
                $subchildVal = $childVal->addChild('item');
                $subchildVal->addAttribute('var', $key);
                $subchildVal->addAttribute('value', $val);
            }
            
            unset($subchildVal, $childVal, $child);
            return true;
        });
        
        $this->execDestruct = false;
        
        //(new \fpcm\model\http\response)->addHeaders('Content-type: text/xml; charset=utf-8')->setReturnData($this->xml->asXML())->fetch();exit;
        
        

        $list = [];

        $var1 = 'EDITOR_HTML_BUTTONS_READMORE';
        $this->getLangVar($this->xml->xpath("/langvars/langvar[@var=\"{$var1}\"]")[0], $list);
        
        $var2 = 'EDITOR_INSERTMEDIA_FORMATS';
        $this->getLangVar($this->xml->xpath("/langvars/langvar[@var=\"{$var2}\"]")[0], $list);
        
        fpcmDump($list);exit;

        
        return true;
        
    }
    
    private function getLangVar(\SimpleXMLElement $item, array &$list) : bool
    {
        $attr = $item->attributes();
        $children = $item->children();

        if (!isset($attr->value) && !count($children)) {
            return false;
        }

        $langVar = (string) $attr->var;
        if (isset($attr->value)) {
            $list[$langVar] = (string) $attr->value;
            return true;
        }

        /* @var $value \SimpleXMLElement */
        foreach ($children->list->item as $subItem) {

            $subAttr = $subItem->attributes();
            $subVar = (string) $subAttr->var;

            if (!isset($list[$langVar])) {
                $list[$langVar] = [];
            }

            $list[$langVar][$subVar] = (string) $subAttr->value;
        }

        return true;
    }
    
}

?>
