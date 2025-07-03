<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * System updater controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class step
extends \fpcm\model\abstracts\staticModel
implements \JsonSerializable
{
    /**
     * Package manager step
     * @var string
     */
    private string $step;

    /**
     * called fucntion
     * @var string
     */
    private string $func;

    /**
     * Step label
     * @var string
     */
    private string $label;

    /**
     * step var
     * @var string
     */
    private string $var;

    /**
     * Execution after step
     * @var string
     */
    private string $after;

    /**
     * Step icon
     * @var \fpcm\view\helper\icon
     */
    private string $icon;

    public function __construct(
        string $label,
        string $step,
        string $func,
        \fpcm\view\helper\icon $icon,
        string $var = '',
        string $after = ''
    ) {
        
        $icon->setSize('lg');
        
        $this->step = $step;
        $this->func = $func;
        $this->label = $label;
        $this->icon = (string) $icon;
        $this->var = $var;
        $this->after = $after;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'step' => $this->step,
            'func' => $this->func,
            'label' => $this->label,
            'var' => $this->var,
            'after' => $this->after,
            'icon' => $this->icon,
        ];
    }

}
