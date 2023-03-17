<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Select menu view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class accordionItem extends helper {

    use traits\valueHelper;

    /**
     * 
     * @var string
     */
    private $parent = '';

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'accordion-item';
        $this->data['bs-toggle'] = 'collapse';
    }

    /**
     * Set input value
     * @param mixed $value
     * @param int $escapeMode
     * @return $this
     */
    public function setValue($value, $escapeMode = null)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 
     * @param string $parent
     * @return $this
     */
    public function setParent(string $parent)
    {
        $this->parent = '#fpcm-id-accordion-' . $parent;
        return $this;
    }
        
    /**
     * Return item string
     * @return string
     */
    protected function getString(): string
    {        
        return <<<HTML
            <div class="accordion-item">
                <h2 class="accordion-header" {$this->getIdString()}>
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-id-{$this->id}-line" aria-controls="fpcm-id-{$this->id}-head" aria-expanded="false">
                        {$this->text}
                    </button>
                </h2>
                <div id="fpcm-id-{$this->id}-line" class="accordion-collapse collapse" aria-labelledby="fpcm-id-{$this->id}-head" data-bs-parent="{$this->parent}">
                    <div class="accordion-body">
                        {$this->value}
                    </div>
                </div>
            </div>
            \n
        HTML;
    }

}

?>