<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\theme;

/**
 * ACP notification item in top menu
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 * @since FPCM 3.6
 */
class notificationItem {

    /**
     * CSS-Klassen für Icon
     * @var \fpcm\view\helper\icon
     */
    protected $icon = '';

    /**
     * allgemeine CSS-Klassen
     * @var string
     */
    protected $class = '';

    /**
     * Item-ID
     * @var string
     */
    protected $id = '';

    /**
     * JavaScript Callback in fpcm.notifications
     * @var string
     */
    protected $callback = '';

    /**
     * Konstruktor
     * @param \fpcm\view\helper\icon $icon
     * @param string $id
     * @param string $callback
     */
    function __construct(\fpcm\view\helper\icon $icon, string $id = '', string $callback = '', string $class = '')
    {
        $this->icon = $icon;
        $this->icon->setSize('lg');

        $this->id = trim($id) ? trim($id) : uniqid('fpcm-notification-item');
        $this->callback = $callback;
        $this->class = 'fpcm-menu-top-level2 fpcm-notification-item fpcm-ui-align-left py-2'.(trim($class) ? ' '.$class : '');
    }

    /**
     * CSS-Klassen für Icon zurückgeben
     * @return \fpcm\view\helper\icon
     */
    public function getIcon() : \fpcm\view\helper\icon
    {
        return $this->icon;
    }

    /**
     * CSS-Klassen zurückgeben
     * @return string
     */
    public function getClass() : string
    {
        return $this->class;
    }

    /**
     * Item-ID zurückgeben
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Objekt als String zurückgeben
     * @return string
     * @ignore
     */
    public function __toString() : string
    {
        return "<li id=\"{$this->id}\" class=\"{$this->class}\"".$this->getCallback(). $this->icon . $this->icon->getText() . "</li>";
    }

    /**
     * Return callback string
     * @return string
     * @since FPCM 4.5
     */
    private function getCallback() : string
    {
        if (!trim($this->callback)) {
            return '>';
        }

        if (strpos($this->callback, 'http') === 0) {
            return "><a href=\"{$this->callback}\">" . $this->icon . "</a>";
        }

        return " data-callback=\"{$this->callback}\">";
    }

}
