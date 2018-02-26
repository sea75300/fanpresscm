<?php

/**
 * Recent articles Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Recent articles dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class fpcmnews extends \fpcm\model\abstracts\dashcontainer {

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'fpcmnews';
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
        return 'RECENT_FPCMNEWS';
    }

    /**
     * 
     * @return int
     */
    public function getPosition()
    {
        return 7;
    }

    /**
     * 
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_MEDIUM;
    }

    /**
     * Content rendern
     */
    private function renderContent()
    {
        if (!\fpcm\classes\baseconfig::canConnect()) {
            $this->content = $this->language->translate('GLOBAL_NOTFOUND2');
            return false;
        }

        $xmlString = simplexml_load_file('https://nobody-knows.org/category/fanpress-cm/feed/');

        if (!$xmlString) {
            $this->content = $this->language->translate('GLOBAL_NOTFOUND2');
            return false;
        }

        $items = $xmlString->channel->item;

        $idx = 0;

        $content = [];
        $content[] = '<table class="fpcm-ui-table fpcm-ui-rssnews fpcm-ui-large-td">';
        foreach ($items as $item) {
            if ($idx >= 10) {
                break;
            }

            $content[] = '<tr class="fpcm-ui-font-small">';
            $content[] = '  <td class="fpcm-ui-articlelist-open">';
            $content[] = (new \fpcm\view\helper\openButton(uniqid('fpcmNews')))->setUrl(strip_tags($item->link))->setTarget('_blank');
            $content[] = '  </td>';
            $content[] = '  <td>';
            $content[] = '  <strong>' . (new \fpcm\view\helper\escape(strip_tags($item->title))) . '</strong><br>';
            $content[] = '  <span>' . (new \fpcm\view\helper\dateText(strtotime($item->pubDate))) . '</span>';
            $content[] = '  </td>';
            $content[] = '</tr>';
            $idx++;
        }

        $content[] = '</table>';

        $this->content = implode(PHP_EOL, $content);

        $this->cache->write($this->cacheName, $this->content, $this->config->system_cache_timeout);
    }

}
