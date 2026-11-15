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

    /**
     *
     * @var array
     */
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

        $fn = match ($this->request->fromGET('src')) {
            'frontend' => $this->fetchFrontend(),
            'drafts' => $this->fetchDrafts(),
            default => false
        };

        if (!$fn) {
            $this->response->setReturnData([])->fetch();
        }

        $this->response->setReturnData($this->list)->fetch();
    }

    private function fetchFrontend() : bool
    {

        $files = (new \fpcm\model\templates\pub\templates())->get();

        if (!count($files)) {
            return false;
        }

        /* @var $file \fpcm\model\templates\pub\file */
        foreach ($files as $file) {

            if (!isset($this->list[$file->getParentFolder()])) {
                $this->list[$file->getParentFolder()] = [];
            }

            $this->list[$file->getParentFolder()][] = $file;
        }

        return true;
    }

    private function fetchDrafts() : bool
    {
        $files = (new \fpcm\model\files\templatefilelist)->getFolderObjectList();

        if (!count($files)) {
            return false;
        }

        /* @var $file \fpcm\model\files\templatefile */
        foreach ($files as $file) {
            $this->list['drafts'][] = $file;
        }

        return true;
    }

}
