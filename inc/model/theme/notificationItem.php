<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\theme;

/**
 * ACP notification item in top menu
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 * @since 3.6
 */
class notificationItem implements \Stringable {

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
        $this->class = 'dropdown-item'.(trim($class) ? ' '.$class : '');
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
        $callback = $this->getCallback();

        return sprintf(
            '<li id="%s" class="%s text-truncate" %s %s %s</li>',
            $this->id,
            $this->class,
            $this->getCallback(),
            !str_contains($callback, '<a href') ? $this->icon : '',
            $this->icon->getText()
        );

    }

    /**
     * Return callback string
     * @return string
     * @since 4.5
     */
    private function getCallback() : string
    {
        if (!trim($this->callback)) {
            return '>';
        }

        if ( str_starts_with($this->callback, 'http') ) {
            return sprintf(
                '><a href="%s" class="%s">%s</a>',
                $this->callback,
                'btn btn-sm btn-warning',
                $this->icon
            );
        }

        return sprintf(' data-callback="%s">', $this->callback);
    }

}
