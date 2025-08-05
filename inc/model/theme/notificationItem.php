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
    protected string|\fpcm\view\helper\linkButton|\fpcm\view\helper\button $callback = '';

    /**
     * Link callback
     * @var bool
     */
    private bool $isLinkCallback = false;

    /**
     * Konstruktor
     * @param \fpcm\view\helper\icon $icon
     * @param string $id
     * @param string $callback
     */
    function __construct(
        \fpcm\view\helper\icon $icon,
        string $id = '',
        string|\fpcm\view\helper\linkButton|\fpcm\view\helper\button $callback = '',
        string $class = ''
    )
    {
        if (!trim($id)) {
            $id = uniqid('fpcm-notification-item');
        }

        $this->callback = $callback;

        $disabled = !is_object($this->callback) || (is_string($callback) && !trim($callback));

        $this->class = sprintf('%s %s dropdown-item-text', trim($class), $disabled ? 'disabled' : '');
        $this->id = $id;

        $this->icon = $icon;
        $this->icon->setStack('square')->setInvertIcon();
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
        $txt = $this->icon->getText();

        return sprintf(
            '<li title="%s" id="%s" class="%s"><span class="d-flex align-items-center"><span class="me-2">%s</span><span class="flex-grow-1 text-truncate">%s</span><span class="ms-3">%s</span></span></li>',
            strip_tags($txt),
            $this->id,
            $this->class,
            $this->icon,
            $txt,
            $this->getCallback()
        );

    }

    /**
     * Return callback string
     * @return string
     * @since 4.5
     */
    private function getCallback() : string
    {
        if (!is_object($this->callback) && !trim($this->callback)) {
            return '';
        }

        if (is_string($this->callback)) {

            $name = 'callback'.$this->id;

            if (str_starts_with($this->callback, 'http')) {
                $this->callback = (new \fpcm\view\helper\linkButton($name))->setUrl($this->callback)->setText('');
            }
            else {
                $this->callback = (new \fpcm\view\helper\button($name))
                    ->setData(['fn' => $this->callback])
                    ->setText(strip_tags($this->icon->getText()));
            }

            $this->callback->overrideButtonType('secondary')->setIcon('circle-play');
        }

        if ($this->callback instanceof \fpcm\view\helper\linkButton ||
            $this->callback instanceof \fpcm\view\helper\button) {

            $this->callback->setIconOnly()->setClass('btn-sm me-1');

            return (string) $this->callback;
        }



        return sprintf(' data-callback="%s"', $this->callback);
    }

}
