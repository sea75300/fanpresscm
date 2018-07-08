<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Help controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class help extends \fpcm\controller\abstracts\controller {

    /**
     * 
     * @return string
     */
    protected function getViewPath()
    {
        return 'system/help';
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {
        $ref = $this->getRequestVar('ref', [
            \fpcm\classes\http::FILTER_URLDECODE,
            \fpcm\classes\http::FILTER_BASE64DECODE
        ]);

        $chapter = $this->getRequestVar('chapter', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);
        
        if ($chapter === null) {
            $chapter = 0;
        }

        $xml = simplexml_load_string($this->language->getHelp());
        $data = $xml->xpath("/chapters/chapter[@ref=\"{$ref}\"]");

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);
        $this->view->setViewVars([
            'headline' => $ref,
            'content'  => count($data) && isset($data[$chapter]) ? trim($data[$chapter]) : $this->language->translate('GLOBAL_NOTFOUND2')
        ]);

        $this->view->render();
    }

}

?>