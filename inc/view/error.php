<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view;

/**
 * Error View Objekt
 * 
 * @package fpcm\view
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class error extends \fpcm\view\view {

    /**
     * Error message
     * @var string
     */
    protected $errorMessage;

    /**
     * Destination controller for "Back" button
     * @var string
     */
    protected $backController;

    /**
     * Icon class name
     * @var string
     */
    protected $icon;

    /**
     * Konstruktor
     * @param string $errorMessage
     * @param string $backController
     * @param string $icon
     */
    public function __construct($errorMessage, $backController = null, $icon = null)
    {
        parent::__construct('common/error');
        $this->errorMessage = $this->language->translate($errorMessage);
        $this->backController = trim($backController) ? trim($backController) : '';
        $this->icon = trim($icon) ? $icon : 'exclamation-triangle';
        $this->showHeaderFooter(view::INCLUDE_HEADER_NONE);
    }

    /**
     * Renders view
     * @param bool $exit
     * @return bool
     */
    public function render($exit = true) : bool
    {
        $this->assign('errorMessage', $this->errorMessage);
        $this->assign('backController', \fpcm\classes\tools::getFullControllerLink($this->backController));
        $this->assign('icon', $this->icon);
        parent::render();
        if ($exit) {
            exit;
        }

        return false;
    }

}
