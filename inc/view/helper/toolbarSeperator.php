<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Text input view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0-dev
 */
final class toolbarSeperator extends helper {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'btn-group';
        $this->setAria([
            'label' => $this->language->translate('TEMPLATE_EDITOR')
        ]);
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        return  "</div><div {$this->getClassString()} {$this->getAriaString()} role=\"group\">";
    }

}
