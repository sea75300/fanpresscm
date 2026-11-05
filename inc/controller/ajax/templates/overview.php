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
 * @since 5.4.0-a1
 */
class overview extends \fpcm\controller\abstracts\ajaxController
{

    private array $list = [];


    /**
     * Accessible
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->templates;
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {

        $files = (new \fpcm\model\templates\pub\templates())->get();

        if (!count($files)) {
            $this->response->setReturnData([])->fetch();
        }
        
        /* @var $file \fpcm\model\templates\pub\file */
        foreach ($files as $file) {
            
            if (!isset($this->list[$file->getParentFolder()])) {
                $this->list[$file->getParentFolder()] = [];
            }

            $this->list[$file->getParentFolder()][] = $file;
        }
        
        $this->response->setReturnData($this->list)->fetch();
    }

}
