<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Recent articles dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class fpcmnews extends \fpcm\model\abstracts\dashcontainer {

    use \fpcm\model\traits\dashContainerCols;
    
    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'fpcmnews';
    }

    /**
     * Returns content
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
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 7;
    }

    /**
     * Returns container height
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_SMALL_MEDIUM;
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
        $content[] = '<div>';
        foreach ($items as $item) {
            if ($idx >= 10) {
                break;
            }

            $content[] = '<div class="row fpcm-ui-font-small py-1">';
            $content[] = $this->get2ColRowSmallLeftAuto(
                (new \fpcm\view\helper\openButton(uniqid('fpcmNews')))->setUrl(strip_tags($item->link))->setTarget('_blank')->setRel('external'),
                '<strong>' . (new \fpcm\view\helper\escape(strip_tags($item->title))) . '</strong><br><span>' . (new \fpcm\view\helper\dateText(strtotime($item->pubDate))) . '</span>',
                'fpcm-ui-ellipsis'
            );
            $content[] = '</div>';            

            $idx++;
        }

        $content[] = '</div>';

        $this->content = implode(PHP_EOL, $content);

        $this->cache->write($this->cacheName, $this->content);
    }

    /**
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('toActiveArticles'))
                ->setUrl('https://github.com/sea75300/fanpresscm/releases')
                ->setTarget('_blank')
                ->setIcon('github', 'fa-brands')
                ->setText('GitHub');
    }

}
