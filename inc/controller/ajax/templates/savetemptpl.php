<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\templates;

/**
 * AJAX save template preview code
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\controller\ajax\system\cronasync
 * @since FPCM 3.4
 */
class savetemptpl extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\system\templatepreview;

    protected function getPermissions()
    {
        return ['system' => 'templates'];
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $tplId = $this->getRequestVar('tplid', [9]);
        $content = $this->getRequestVar('content', [7, 6]);

        $template = $this->getTemplateById($tplId);

        file_put_contents($template->getFullpath(), '');

        $template->setContent($content);
        $template->save();
    }

}

?>