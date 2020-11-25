<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\templates;

/**
 * Template preview trait
 * 
 * @package fpcm\controller\traits\system.syscheck
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait preview {

    /**
     * 
     * @param type $tplId
     * @return bool|\fpcm\model\pubtemplates\template
     */
    protected function getTemplateById($tplId)
    {
        $filename = '_preview' . $tplId;

        switch ($tplId) {
            case \fpcm\model\pubtemplates\article::TEMPLATE_ID :
                return new \fpcm\model\pubtemplates\article($filename);
            case \fpcm\model\pubtemplates\article::TEMPLATE_ID_SINGLE :
                return new \fpcm\model\pubtemplates\article($filename);
            case \fpcm\model\pubtemplates\comment::TEMPLATE_ID :
                return new \fpcm\model\pubtemplates\comment($filename);
            case \fpcm\model\pubtemplates\commentform::TEMPLATE_ID :
                return new \fpcm\model\pubtemplates\commentform($filename);
            case \fpcm\model\pubtemplates\latestnews::TEMPLATE_ID :
                return new \fpcm\model\pubtemplates\latestnews($filename);
            case \fpcm\model\pubtemplates\sharebuttons::TEMPLATE_ID :
                return new \fpcm\model\pubtemplates\sharebuttons($filename);
        }

        return false;
    }

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->templates;
    }

}

?>