<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Imput view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class input extends helper {

    use traits\iconHelper,
        traits\valueHelper,
        traits\typeHelper;

    /**
     * Maximum input lenght
     * @var int
     */
    protected $maxlenght = 255;

    /**
     * Use label text as placeholder
     * @var string
     */
    protected $placeholder = false;

    /**
     * Column with if input uses an icon
     * @var string
     */
    protected $colWidth = '12';

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        $wrapperStart = '';
        $wrapperEnd = '';

        if ($this->useWrapper) {
            $wrapperStart = "<div class=\"fpcm-ui-input-wrapper col-{$this->colWidth} {$this->wrapperClass} fpcm-ui-padding-none-lr\"><div class=\"fpcm-ui-input-wrapper-inner\">";
            $wrapperEnd = "</div></div>";
        }

        $input = "<input type=\"{$this->type}\" {$this->getNameIdString()}{$this->getClassString()} {$this->getValueString()} {$this->getReadonlyString()} maxlength=\"{$this->maxlenght}\" {$this->getPlaceholderString()} {$this->getDataString()}>";
        if (!$this->text) {
            return $wrapperStart . $input . $wrapperEnd;
        }

        $description    = $this->placeholder
                        ? ($this->icon ? "<label class=\"{$this->labelClass}\" for=\"{$this->id}\">{$this->getIconString()}</label>" : '')
                        : "<label class=\"{$this->labelClass}\" for=\"{$this->id}\">{$this->getIconString()}{$this->getDescriptionTextString()}</label>";

        return $wrapperStart . $description . $input . $wrapperEnd;
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-input';
        $this->labelClass = 'align-self-center';
    }

    /**
     * Set max lenght
     * @param int $maxlenght
     * @return $this
     */
    public function setMaxlenght($maxlenght)
    {
        $this->maxlenght = (int) $maxlenght;
        return $this;
    }

    /**
     * Use label text as placeholder
     * @param bool $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = (bool) $placeholder;
        return $this;
    }

    /**
     * Set column with if input uses an icon
     * @param mixed $colWidth
     * @return $this
     */
    public function setInputColWidth($colWidth)
    {
        $this->colWidth = $colWidth;
        return $this;
    }
    
    /**
     * Placeholder string
     * @return string
     */
    protected function getPlaceholderString()
    {
        return ($this->placeholder ? "placeholder=\"{$this->text}\"" : '');
    }

}

?>