<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Edit link button view helper object
 *
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
final class updateButton extends button {

    /**
     * Updater object
     * @var \fpcm\model\updater\system
     */
    protected $updater;

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->setText('PACKAGES_UPDATE');
        $this->setIcon('sync');

        $this->setData([
            'changelog' => $this->getUpdater()->changelog ?? '',
            'update' => \fpcm\classes\tools::getFullControllerLink('package/sysupdate')
        ]);

    }

    /**
     * Get Updater object
     * @return \fpcm\model\updater\system
     */
    public function getUpdater(): \fpcm\model\updater\system
    {
        if (!$this->updater instanceof \fpcm\model\updater\system) {
            $this->updater = \fpcm\model\updater\system::getInstance();
        }

        return $this->updater;
    }

    /**
     * Set updater object
     * @param \fpcm\model\updater\system $updater
     * @return $this
     */
    public function setUpdater(\fpcm\model\updater\system $updater)
    {
        $this->updater = $updater;
        return $this;
    }
    
    /**
     * Render link button as inline list group item
     * @param string $size
     * @param string $class
     * @return string
     * @since 5.3.0-a1
     */
    final public function asInline(string $size = '', string $class = '') : string
    {
        $this->returned = true;

        $icon = trim($this->getIconString());

        $class = sprintf('list-group-item list-group-item-action align-content-center %s %s', $class, $size);

        if ($this->readonly) {
            $class .= ' pe-none';
        }

        if ($this->iconOnly) {
            return sprintf('<button type="button" title="%s" class="%s" %s %s>%s</button>', $this->text, $class, $this->getIdString(), $this->getDataString(), $icon);
        }

        $this->text = $this->language->translate($this->text);

        return sprintf('<button type="button" class="%s" %s %s>%s%s</button>', $class, $this->getIdString(), $this->getDataString(), $icon, $this->text);
    }

}
