<?php

/**
 * Welcome Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Welcome dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class welcome extends \fpcm\model\abstracts\dashcontainer {

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'welcome';
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->language->translate('WELCOME_CONTENT');
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return $this->language->translate('WELCOME_HEADLINE', [
            '{{username}}' => \fpcm\classes\loader::getObject('\fpcm\model\system\session')->currentUser->getDisplayname()
        ]);
    }

    /**
     * 
     * @return int
     */
    public function getPosition()
    {
        return 1;
    }

}
