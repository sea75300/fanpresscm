<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Welcome dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class welcome extends \fpcm\model\abstracts\dashcontainer {

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'welcome';
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        return '<p class="px-2">' . $this->language->translate('WELCOME_CONTENT') . '</p>';
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return $this->language->translate('WELCOME_HEADLINE', [
            '{{username}}' => \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getCurrentUser()->getDisplayname()
        ]);
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 1;
    }

    /**
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('openProfile'))
            ->setUrl(\fpcm\classes\tools::getFullControllerLink('system/profile'))
            ->setIcon('wrench')
            ->setText('PROFILE_OPEN');
    }

}
