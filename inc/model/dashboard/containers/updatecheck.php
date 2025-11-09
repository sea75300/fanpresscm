<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\containers;

/**
 * Update check dashboard container object
 *
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class updatecheck extends \fpcm\model\abstracts\dashcontainer implements \fpcm\model\interfaces\isAccessible {

    /**
     * Container table content
     * @var array
     */
    protected $tableContent = [];

    /**
     * Ergebnis der System-Update-Prüfung
     * @var bool
     * @since 3.1.3
     */
    private $systemCheckresult;

    /**
     * System-Update-Object
     * @var \fpcm\model\updater\system
     * @since 3.1.3
     */
    private $systemUpdates;

    /**
     * Container is accessible
     *
     * @see \fpcm\model\interfaces\isAccessible::isAccessible()
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options && $this->permissions->system->update;
    }

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'updatecheck';
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        if (!\fpcm\classes\baseconfig::canConnect()) {
            $this->addItem('exclamation-triangle', 'secondary', 'UPDATE_MODULECHECK_FAILED');
            return sprintf('<div class="row">%s</div>', implode(PHP_EOL, $this->tableContent));
        }

        $this->getSystemUpdateStatus();
        $this->getModuleUpdateStatus();

        $this->addItem(
            text: 'UPDATE_VERSIONCHECK_NOTES',
            itemClass: 'fpcm ui-background-white-50p'
        );

        return sprintf('<div class="row">%s</div>', implode(PHP_EOL, $this->tableContent));
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'SYSTEM_UPDATE';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 3;
    }

    /**
     * Initialize internal objects
     * @return bool
     */
    protected function initObjects()
    {
        $this->systemUpdates = \fpcm\model\updater\system::getInstance();
        return true;
    }

    /**
     * Liefert System-Update-HTML zurück
     * @since 3.1.0
     */
    private function getSystemUpdateStatus()
    {
        if (!$this->systemUpdates->filesListExists()) {
            $this->addItem(
                'exclamation-triangle',
                'danger',
                'UPDATE_VERSIONCECK_FILETXT_ERR'
            );
        }

        if ($this->config->system_version !== \fpcm\classes\baseconfig::getVersionFromFile()) {
            $button = (new \fpcm\view\helper\linkButton('updater'))
                    ->setText('PACKAGES_UPDATE')
                    ->setIcon('sync')
                    ->setUrl(\fpcm\classes\tools::getFullControllerLink('package/sysupdate', ['update-db' => 1]))
                    ->setIconOnly();

            $this->addItem(
                'code-branch',
                'secondary',
                'UPDATE_VERSIONCECK_FILEDB_ERR',
                $button
            );
        }

        $this->systemCheckresult = $this->systemUpdates->updateAvailable();
        if ($this->systemCheckresult === true ||
            $this->systemCheckresult === \fpcm\model\updater\system::FORCE_UPDATE) {

            $icon = 'cloud-download-alt';
            $status = 'danger';

            $text = $this->language->translate('UPDATE_VERSIONCHECK_NEW', [
                '{{version}}' => $this->systemUpdates->version
            ]);
            
            $button = (new \fpcm\view\helper\updateButton('startUpdate'))->setUpdater($this->systemUpdates)->setIconOnly();
        }
        else {
            $icon = 'check';
            $status = 'success';
            $text = 'UPDATE_VERSIONCHECK_CURRENT';
            $button = null;
        }

        $this->addItem($icon, $status, $text, $button);
    }

    /**
     * Liefert Modul-Update-HTML zurück
     * @since 3.1.0
     */
    private function getModuleUpdateStatus()
    {
        if ((new \fpcm\module\modules())->getInstalledUpdates(true) > 0) {
            $this->addItem(
                'cloud-download-alt',
                'warning',
                'UPDATE_MODULECHECK_NEW',
                (new \fpcm\view\helper\linkButton('showModuleUpdates'))
                    ->setText('PACKAGES_UPDATES_LIST')
                    ->setIcon('sync')
                    ->setUrl(\fpcm\classes\tools::getFullControllerLink('modules/list'))
                    ->setIconOnly()
            );

            return true;
        }

        $this->addItem('check', 'success', 'UPDATE_MODULECHECK_CURRENT');
    }

    /**
     * Returns JavaScript variables
     * @return array
     */
    public function getJavascriptVars()
    {
        return [
            'forceUpdate' => $this->systemUpdates->updateAvailable() === \fpcm\model\updater\system::FORCE_UPDATE
        ];
    }

    /**
     * Returns JavaScript language variables
     * @return array
     */
    public function getJavascriptLangVars()
    {
        return [];
    }

    /**
     * 
     * @param string $icon
     * @param string $status
     * @param string $text
     * @param null|\fpcm\view\helper\linkButton|\fpcm\view\helper\updateButton $button
     * @param string $itemClass
     * @return void
     * @since 3.1.0
     */
    private function addItem(
        string $icon = '',
        string $status = '',
        string $text = '',
        null|\fpcm\view\helper\linkButton|\fpcm\view\helper\updateButton $button = null,
        string $itemClass = ''
    ) : void
    {
        if ($status) {
            $status = 'list-group-item-' . $status;
        }

        $text = $this->language->translate($text);
        
        if (!$icon) {
            $this->tableContent[] = sprintf(
                '<div class="list-group my-1"><div class="list-group-item %s align-self-center my-1 %s">%s</div></div>',
                $status,
                $itemClass,
                $text
            );
            return;
        }

        $iconCol = sprintf('<div class="list-group-item %s col-auto align-content-center py-3">%s</div>', $status, (new \fpcm\view\helper\icon($icon))->setSize('lg') );
        $textCol = sprintf('<div class="list-group-item %s col align-self-center py-3">%s</div>', $status, $text);

        if ($button === null) {
            $this->tableContent[] = sprintf('<div class="list-group list-group-horizontal my-1">%s%s</div>', $iconCol, $textCol);
            return;
        }

        $this->tableContent[] = sprintf('<div class="list-group list-group-horizontal my-1">%s%s%s</div>', $iconCol, $textCol, $button->asInline('w-auto', 'list-group-item-info'));
    }

    /**
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('manualCheckFooter'))
            ->setUrl(\fpcm\classes\baseconfig::$updateServerManualLink)
            ->setIcon('square-up-right')
            ->setText('PACKAGES_MANUALCHECK')
            ->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW)
            ->setRel('noreferrer,noopener,external');
    }

}
