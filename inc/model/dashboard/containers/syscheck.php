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
        $sysCheckAction = new \fpcm\controller\ajax\system\syscheck(true);

        $rows = [];

        /* @var $data \fpcm\model\system\syscheckOption */
        foreach ($sysCheckAction->getOptions() as $description => $data) {

            $txt = strip_tags($description);

            $rows[] = [
                'description' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: $txt,
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

        foreach ($sysCheckAction->getFolders() as $description => $data) {

            $txt = strip_tags($description);

            $rows[] = [
                'description' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: $txt,
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
     * Returns container height
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_SMALL_MEDIUM;
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
