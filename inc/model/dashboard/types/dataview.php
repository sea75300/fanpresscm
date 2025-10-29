<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\types;

/**
 * Dataview type dashboard trait
 *
 * @package fpcm\model\traits\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-a1
 */
abstract class dataview extends \fpcm\model\abstracts\dashcontainer {

    private array $cols = [];

    private string $fotnsize = '';

    /**
     * Returns cols
     * @return array
     */
    abstract public function getCols() : array;

    /**
     * Returns rows
     * @return array
     */
    abstract public function getRows() : array;

    /**
     * Get font size for all items
     * @return string
     */
    public function getFontSize() : string
    {
        return '';
    }

    /**
     * Return rendered content
     * @return string
     */
    final public function getContent()
    {
        $session = \fpcm\model\system\session::getInstance();
        $this->currentUser = $session->getUserId();

        $this->getCacheName('_' . $this->currentUser);

        if (!$this->cache->isExpired($this->cacheName)) {
            return $this->cache->read($this->cacheName);
        }

        $this->fotnsize = $this->getFontSIze();
        $this->cols = $this->getCols();
        $rows = $this->getRows();

        if (!count($rows)) {
            $str = $this->language->translate('GLOBAL_NOTFOUND2');
            $this->cache->write($this->cacheName, $str);
            return $str;
        }

        $rows = array_map([$this, 'renderRow'], $rows);

        $content = implode("\n", $rows);
        $this->cache->write($this->cacheName, $content, $this->config->system_cache_timeout);

        return $content;
    }

    /**
     * render content row
     * @param array $row
     * @return string
     */
    private function renderRow(array $row) : string
    {
        $tmp = [];

        /* @var $col \fpcm\components\dataView\column */
        foreach ($this->cols as $index => $col) {

            /* @var $item \fpcm\model\dashboard\components\dataviewItem */
            $item = $row[$col] ?? null;
            if (!$item instanceof \fpcm\model\dashboard\components\dataviewItem) {
                trigger_error(sprintf('Value of dashboard dataview conatiner column must be an \fpcm\model\dashboard\components\dataviewItem, %s give', gettype($item)));
                return '';
            }

            $type = $item->getType();
            $val = $item->getValue();
            $align = $item->getAlign();
            $size = $item->getSize();
            $class = $item->getClass();
            
            $class .= ' '. $this->fotnsize;

            $tmp[] = $this->{'render'.$type}($val, $align, $size, $class);
        }

        return sprintf('<div class="list-group list-group-horizontal-lg my-1">%s</div>', implode("\n", $tmp));
    }

    /**
     * Render link button item
     * @param \fpcm\view\helper\editButton|\fpcm\view\helper\linkButton|\fpcm\view\helper\openButton $value
     * @param string $align
     * @param string $size
     * @param string $class
     * @return string
     */
    private function renderLink(\fpcm\view\helper\editButton|\fpcm\view\helper\linkButton|\fpcm\view\helper\openButton $value, string $align, string $size, string $class) : string
    {
        return $value->asInline($size, $class);
    }
    
    /**
     * Render date item value
     * @param \fpcm\view\helper\icon|array $value
     * @param string $align
     * @param string $size
     * @param string $class
     * @return string
     */
    private function renderIcons(\fpcm\view\helper\icon|array $value, string $align, string $size, string $class) : string
    {
        if (is_array($value)) {
            $value = implode('', $value);
        }
        else {
            $value = (string) $value;
        }

        return sprintf('<div class="list-group-item align-self-center fpcm %s %s %s">%s</div>', $align, $size, $class, $value);
    }
    
    /**
     * Render bool icon item
     * @param bool $value
     * @param string $align
     * @param string $size
     * @param string $class
     * @return string
     */
    private function renderBoolIcon(bool $value, string $align, string $size, string $class) : string
    {
        $icon = new \fpcm\view\helper\icon( $value ? 'circle-check' : 'circle-xmark' );
        $icon->setSize('lg');
        
        $class .= ' list-group-item-' . ($value ? 'success' : 'danger');

        return sprintf('<div class="list-group-item align-self-center fpcm %s %s %s">%s</div>', $align, $size, $class, $icon);
    }
    
    /**
     * Render text item
     * @param string $value
     * @param string $align
     * @param string $size
     * @param string $class
     * @return string
     */
    private function renderText(string $value, string $align, string $size, string $class) : string
    {
        if ($value != 0) {
            $value = $this->language->translate($value);
        }
        
        return sprintf('<div class="list-group-item align-self-center fpcm %s %s %s">%s</div>', $align, $size, $class, $value);
    }

}
