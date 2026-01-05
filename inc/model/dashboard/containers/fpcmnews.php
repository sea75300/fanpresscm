<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\containers;

/**
 * Recent articles dashboard container object
 *
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class fpcmnews extends \fpcm\model\dashboard\types\dataview {

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'fpcmnews';
    }

    public function getCols(): array
    {
        return [
            'url',
            'text'
        ];
    }

    public function getRows(): array
    {
        if (!\fpcm\classes\baseconfig::canConnect()) {
            return [];
        }

        $xmlString = simplexml_load_file('https://nobody-knows.org/category/fanpress-cm/feed/');
        if (!$xmlString) {
            return [];
        }

        $items = $xmlString->channel->item;

        $rows = [];
        
        $idx = 0;

        foreach ($items as $item) {

            if ($idx > 10) {
                break;
            }

            $rows[] = [
                'url' => new \fpcm\model\dashboard\components\dataviewItem(
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_LINK,
                    value: (new \fpcm\view\helper\openButton(uniqid('fpcmNews')))->setUrl(strip_tags($item->link))->setTarget('_blank')->setRel('external')
                ),
                'text' => new \fpcm\model\dashboard\components\dataviewItem(
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                    value: sprintf(
                        '<strong>%s</strong><br><span class="text-secondary">%s</span>',
                        new \fpcm\view\helper\escape(strip_tags($item->title)),
                        new \fpcm\view\helper\dateText(strtotime($item->pubDate))
                    ),
                    class: 'fpcm-ui-font-small text-truncate'
                )
            ];

            $idx++;
        }

        return $rows;
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
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('toActiveArticles'))
            ->setUrl('https://github.com/sea75300/fanpresscm/releases')
            ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
            ->setIcon('github', 'fa-brands')
            ->setText('GitHub');
    }

}
