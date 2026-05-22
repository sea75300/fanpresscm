<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\containers;

/**
 * System check dashboard container object
 *
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class syscheck extends \fpcm\model\dashboard\types\dataview {

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'syscheck';
    }

    /**
     * Container is accessible
     * @see \fpcm\model\interfaces\isAccessible::isAccessible()
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options;
    }

    /**
     * Returns cols
     * @return array
     */
    public function getCols(): array
    {
        return [
            'result',
            'description',
        ];
    }

    /**
     * Returns content
     * @return string
     */
    public function getRows(): array
    {
        $check = new \fpcm\model\system\check\check();
        $check->perform();

        $rows = [];

        $include = [
            'phpversion' => 0,
            'dbtype' => 1,
            'pdo' => 2,
            'sha256' => 3,
            'gb' => 4,
            'json' => 5,
            'zip' => 6,
            'openssl' => 7,
            'curl' => 8,
            'allow_url_fopen' => 9,
            'opcache' => 10,
            'memcache' => 11
        ];
        
        /* @var $data \fpcm\model\system\check\option */
        foreach ($check->getOptionsResult($include) as $data) {

            $rows[] = [
                'description' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: $data->getLabel(),
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                    class: 'text-truncate'
                ),
                'result' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: $data->getResult(),
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_BOOLICON,
                    size: 'auto'
                )
            ];
        }

        foreach ($check->getFolderResult() as $data) {

            $rows[] = [
                'description' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: $data->getLabel(),
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                    class: 'text-truncate'
                ),
                'result' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: $data->getResult(),
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_BOOLICON,
                    size: 'auto'
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
        return 'SYSTEM_CHECK';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 5;
    }

    /**
     * Get width
     * @return int
     */
    public function getWidth() {
        return 6;
    }

    /**
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        if (!$this->permissions->system->options) {
            return null;
        }

        return (new \fpcm\view\helper\linkButton('runSyscheck'))
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('system/options', ['syscheck' => 1]))
                ->setIcon('sync')
                ->setText('SYSCHECK_COMPLETE');
    }

}
