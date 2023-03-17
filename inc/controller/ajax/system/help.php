<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Help controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class help extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\common\isAccessibleTrue,
        \fpcm\controller\traits\common\fetchHelp;

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'system/help';
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $ref = $this->request->fromGET('ref', [
            \fpcm\model\http\request::FILTER_URLDECODE,
            \fpcm\model\http\request::FILTER_BASE64DECODE
        ]);

        $chapter = $this->request->fromGET('chapter', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        if ($chapter === null) {
            $chapter = 0;
        }

        $data = $this->getChapter($ref);

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);
        $this->view->setViewVars([
            'headline' => $ref,
            'content'  => count($data) && isset($data[$chapter]) ? trim($data[$chapter]) : $this->language->translate('GLOBAL_NOTFOUND2')
        ]);

        $this->view->render();
    }

}

?>