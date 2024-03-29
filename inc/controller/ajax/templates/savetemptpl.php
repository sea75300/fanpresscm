<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\templates;

/**
 * AJAX save template preview code
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\controller\ajax\system\templates
 * @since 3.4
 */
class savetemptpl extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\templates\preview;

    /**
     * Controller-Processing
     */
    public function process()
    {
        $tplId = $this->request->fromPOST('tplid');
        $content = $this->request->fromPOST('content', [
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_HTMLENTITY_DECODE
        ]);

        $template = $this->getTemplateById($tplId);

        file_put_contents($template->getFullpath(), '');

        $template->setContent($content);
        $template->save();
    }

}
