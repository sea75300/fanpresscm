<?php

/**
 * User list Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * User list dashboard container object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.2.0
 */
class userlist extends \fpcm\model\abstracts\dashcontainer {

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'userlist';
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        $this->getCacheName();
        if ($this->cache->isExpired($this->cacheName)) {
            $this->renderContent();
        }

        return $this->cache->read($this->cacheName);
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
     * 
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

        $userlist = new \fpcm\model\users\userList();

        $content = [];
        $content[] = '<table class="fpcm-ui-table fpcm-ui-users fpcm-ui-large-td">';

        $items = $userlist->getUsersActive();
        /* @var $item \fpcm\model\users\author */
        foreach ($items as $item) {

            $emailAddress = (new \fpcm\view\helper\escape($item->getEmail()));

            $content[] = '<tr class="fpcm-ui-font-small">';
            $content[] = '  <td class="fpcm-ui-editbutton-col">';
            $content[] = (new \fpcm\view\helper\linkButton(uniqid('createMail')))->setUrl('mailto:' . $emailAddress)->setText('GLOBAL_WRITEMAIL')->setTarget('_blank')->setIcon('envelope-o')->setIconOnly(true);
            $content[] = '  </td>';
            $content[] = '  <td>';
            $content[] = '  <strong>' . (new \fpcm\view\helper\escape($item->getDisplayname())) . '</strong><br>';
            $content[] = '  <span>' . $emailAddress . '</span>';
            $content[] = '  </td>';
            $content[] = '</tr>';
        }

        $content[] = '</table>';

        $this->content = implode(PHP_EOL, $content);

        $this->cache->write($this->cacheName, $this->content, $this->config->system_cache_timeout);
    }

}
