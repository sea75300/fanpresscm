<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\containers;

/**
 * User list dashboard container object
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.2.0
 * @package fpcm\model\dashboard
 */
class userlist extends \fpcm\model\dashboard\types\dataview {

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'userlist';
    }

    /**
     * Returns cols
     * @return array
     */
    public function getCols(): array
    {
        return [
            'mail',
            'username'
        ];
    }

    /**
     * Returns rows
     * @return array
     */
    public function getRows(): array
    {
        $items = (new \fpcm\model\users\userList())->getUsersActive();

        $rows = [];
        
        /* @var $item \fpcm\model\users\author */
        foreach ($items as $item) {

            $emailAddress = (new \fpcm\view\helper\escape($item->getEmail()));

            $rows[] = [
                'mail' => new \fpcm\model\dashboard\components\dataviewItem(
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_LINK,
                    value: (new \fpcm\view\helper\linkButton(uniqid('createMail')))->setUrl('mailto:' . $emailAddress)->setText('GLOBAL_WRITEMAIL')->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)->setIcon('envelope')->setIconOnly()
                ),
                'username' => new \fpcm\model\dashboard\components\dataviewItem(
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                    value: sprintf(
                        '<strong>%s</strong><br><span class="text-secondary">%s</span>',
                        new \fpcm\view\helper\escape($item->getDisplayname()),
                        $emailAddress,
                    ),
                    class: 'fpcm-ui-font-small text-truncate'
                )
            ];
        }

        return $rows;
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

}
