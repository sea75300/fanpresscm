<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * User list dashboard container object
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.2.0
 * @package fpcm\model\dashboard
 */
class userlist extends \fpcm\model\abstracts\dashcontainer {

    use \fpcm\model\traits\dashContainerCols;
    
    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'userlist';
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        $content = [];
        $content[] = '<div>';

        /* @var $item \fpcm\model\users\author */
        foreach ((new \fpcm\model\users\userList())->getUsersActive() as $item) {

            $emailAddress = (new \fpcm\view\helper\escape($item->getEmail()));
            
            $content[] = '<div class="row fpcm-ui-font-small py-2">';
            $content[] = $this->get2ColRowSmallLeftAuto(
                (new \fpcm\view\helper\linkButton(uniqid('createMail')))->setUrl('mailto:' . $emailAddress)->setText('GLOBAL_WRITEMAIL')->setTarget('_blank')->setIcon('envelope')->setIconOnly(),
                '<strong>' . (new \fpcm\view\helper\escape($item->getDisplayname())) . '</strong><br><span>' . $emailAddress . '</span>'
            );
            $content[] = '</div>';

        }

        $content[] = '</div>';
        return implode(PHP_EOL, $content);
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'DASHBOARD_USERLIST';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 8;
    }

    /**
     * Content rendern
     */
    private function renderContent()
    {


    }

}
