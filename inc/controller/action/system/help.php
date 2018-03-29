<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Help controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class help extends \fpcm\controller\abstracts\controller {

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
            \fpcm\classes\http::FPCM_REQFILTER_URLDECODE,
            \fpcm\classes\http::FPCM_REQFILTER_BASE64DECODE
        ]);

        $chapter = $this->getRequestVar('chapter', [
            \fpcm\classes\http::FPCM_REQFILTER_CASTINT
        ]);
        
        if ($chapter === null) {
            $chapter = 0;
        }

        $xml = simplexml_load_string($this->lang->getHelp());
        $data = $xml->xpath("/chapters/chapter[@ref=\"{$ref}\"]");

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->setViewVars([
            'headline' => $ref,
            'content'  => trim($data[$chapter])
        ]);

        $this->view->render();
    }

}

?>