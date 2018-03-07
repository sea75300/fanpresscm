<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Abstract view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * 
 * @property string $name
 * @property string $id
 */
abstract class helper {

    /**
     * Element data
     * @var array
     */
    protected $data = [];

    /**
     * Element ID
     * @var string
     */
    protected $id = '';

    /**
     * Element name
     * @var string
     */
    protected $name = '';

    /**
     * Input label
     * @var string
     */
    protected $text = '';

    /**
     * CSS class string
     * @var string
     */
    protected $class = '';

    /**
     * Element is readonly
     * @var boolean
     */
    protected $readonly = false;

    /**
     * Element prefix
     * @var string
     */
    protected $prefix = '';

    /**
     * Add div wrapper to input field
     * @var string
     */
    protected $useWrapper = true;

    /**
     * CSS class for div wrapper
     * @var string
     */
    protected $wrapperClass = '';

    /**
     *
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Flag if element string was return by @see __toString
     * @var boolean
     */
    protected $returned = false;

    /**
     * Konstruktor
     * @param string $name
     * @param string $id
     */
    public function __construct($name, $id = '')
    {
        if (!is_string($name)) {
            trigger_error('Invalid view helper params found in name or id');
            return false;
        }

        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');

        $this->init();

        $this->name = $this->prefix ? $this->prefix . ucfirst($name) : $name;
        $this->id = trim($id) ? $id : $this->getCleanName();
    }

    /**
     * 
     * @return string
     */
    final public function __toString()
    {
        $this->returned = true;
        return $this->getString() . PHP_EOL;
    }

    /**
     * 
     * @return void
     */
    final public function __destruct()
    {
        if ($this->returned) {
            return;
        }

        print $this->getString() . PHP_EOL;
    }

    /**
     * 
     * @param string $name
     * @param string $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        print $name . ' does not exists in ' . get_class($this);
        return $this;
    }

    /**
     * Name cleanup from bracket, etc.
     * @return string
     */
    final protected function getCleanName()
    {
        return trim(str_replace(['[', '(', ')', ']'], '', $this->name));
    }

    /**
     * Returns name and ID string
     * @param string $prefix
     * @return string
     */
    protected function getNameIdString()
    {
        return "name=\"{$this->name}\" id=\"{$this->id}\" ";
    }

    /**
     * Returns name and ID string
     * @param string $prefix
     * @return string
     */
    final protected function getIdString()
    {
        return "id=\"{$this->id}\" ";
    }

    /**
     * Returns name and ID string
     * @param string $prefix
     * @return string
     */
    final protected function getDescriptionTextString()
    {
        return "<span class=\"fpcm-ui-label\">{$this->text}</span>";
    }

    /**
     * Set additional css class
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class .= ' ' . $class;
        return $this;
    }

    /**
     * Return class string
     * @return string
     */
    protected function getClassString()
    {
        return "class=\"{$this->class}\"";
    }

    /**
     * Return class string
     * @return string
     */
    protected function getDataString()
    {
        if (!count($this->data)) {
            return '';
        }

        $return = [];
        foreach ($this->data as $key => $value) {

            if (is_object($value) || is_array($value)) {
                $value = json_encode($value);
            }

            $return[] = "data-{$key}=\"{$value}\"";
        }

        return implode(' ', $return);
    }

    /**
     * Return class string
     * @return string
     */
    protected function getReadonlyString()
    {
        return $this->readonly ? "readonly" : '';
    }

    /**
     * Set element to readonly
     * @param boolean $readonly
     * @return $this
     */
    public function setReadonly($readonly)
    {
        $this->readonly = (bool) $readonly;
        return $this;
    }

    /**
     * Use div wrapper around input field
     * @param bool $useWrapper
     * @return $this
     */
    public function setWrapper($useWrapper)
    {
        $this->useWrapper = (bool) $useWrapper;
        return $this;
    }

    /**
     * Set wrapper css class
     * @param string $wrapperClass
     * @return $this
     */
    public function setWrapperClass($wrapperClass)
    {
        $this->wrapperClass = $wrapperClass;
        return $this;
    }

    /**
     * Set button description
     * @param string $text
     * @param array $params
     * @return $this
     */
    final public function setText($text, $params = [])
    {
        $this->text = $this->language->translate($text, $params);
        return $this;
    }

    /**
     * Returns optional JavaScript vars
     * @return void
     */
    public function getJsVars()
    {
        return [];
    }

    /**
     * Returns optional JavaScript language vars
     * @return void
     */
    public function getJsLangVars()
    {
        return [];
    }

    /**
     * Add array for 'data-'-params to element
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        return;
    }

    /**
     * Return element string
     * @return string
     */
    abstract protected function getString();
}

?>