<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Abstract view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class helper {
    
    use traits\cssClassHelper,
        traits\ariaHelper;

    /*  @since 4.3.0 */
    const TEXT_DEFAULT_LABEL = 'LABEL_FIELD_';

    /*  @since 5.0.0-b5 */
    const LABEL_TYPE_DEFAULT = 'input-group';

    /*  @since 5.0.0-b5 */
    const LABEL_TYPE_FLOATING = 'form-floating';

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
     * Element is readonly
     * @var boolean
     */
    protected $readonly = false;

    /**
     * Element is autoFocused
     * @var bool
     * @since 4.1
     */
    protected $autoFocused = false;

    /**
     * Element is required
     * @var bool
     * @since 5.0.0-a3
     */
    protected $requ = false;

    /**
     * Element prefix
     * @var string
     */
    protected $prefix = '';

    /**
     * Add div wrapper to input field
     * @var string
     */
    protected $useWrapper = false;

    /**
     * CSS class for div wrapper
     * @var string
     */
    protected $wrapperClass = '';

    /**
     * CS class for label
     * @var string
     * @since 4.1
     */
    protected $labelClass = '';

    /**
     * Language object
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Flag if element string was return by @see __toString
     * @var boolean
     */
    protected $returned = false;

    /**
     * Column with if input uses an icon
     * @var string
     */
    protected $labelType = 'input-group';

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
        $this->initLabel();

        $this->id = trim($id) ? $id : $this->getCleanName();
    }

    /**
     * Prevents rendering of view helper at the end of PHP proessing
     * @param bool $returned
     * @return $this
     */
    public function setReturned($returned)
    {
        $this->returned = (bool) $returned;
        return $this;
    }
        
    /**
     * @ignore
     * @return string
     */
    final public function __toString()
    {
        $this->returned = true;
        return $this->getString() . PHP_EOL;
    }

    /**
     * @ignore
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
     * @ignore
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
    final public function getCleanName()
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
    final public function getIdString()
    {
        return "id=\"{$this->id}\" ";
    }

    /**
     * Returns name and ID string
     * @param string $prefix
     * @return string
     */
    final protected function getDescriptionTextString(string $prefix = 'ps-1')
    {
        return "<span class=\"fpcm-ui-label {$prefix}\">{$this->text}</span>";
    }

    /**
     * Initialized default label by field name
     * @return boolean
     * @since 4.3.0
     */
    final protected function initLabel()
    {
        if (trim($this->text) ||
            $this instanceof radiocheck ||
            $this instanceof icon ||
            $this instanceof boolToText ) {
            return false;
        }
        
        $this->text = self::TEXT_DEFAULT_LABEL.strtoupper(preg_replace('/([^A-Za-z0-9\_]+)/', '_', rtrim($this->name, ']')));
        $this->text = $this->language->translate($this->text);
        return true;
    }

    /**
     * Return class string
     * @return string
     */
    protected function getDataString()
    {
        return $this->assocArrayToString('data', $this->data);
    }

    /**
     * Turns assoc array into string
     * @param string $prefix
     * @param array $data
     * @return string
     */
    private function assocArrayToString(string $prefix, array $data) : string
    {
        if (!count($data)) {
            return '';
        }

        $return = array_map(function ($value, $key) use ($prefix) {
            
            if (is_object($value) || is_array($value)) {
                $value = json_encode($value);
            }

            return "{$prefix}-{$key}=\"{$value}\"";            
            
        }, array_values($data), array_keys($data));

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
     * Make helper auto focused
     * @param bool $autoFocused
     * @return $this
     * @since 4.1
     */
    public function setAutoFocused($autoFocused) {
        $this->autoFocused = (bool) $autoFocused;
        return $this;
    }

    /**
     * Return autofocused string
     * @return string
     */
    protected function getAutoFocusedString()
    {
        return $this->autoFocused ? 'autofocus' : '';
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
     * Set label class CSS string
     * @param string $labelClass
     * @return $this
     * @since 4.1
     */
    public function setLabelClass(string $labelClass)
    {
        $this->labelClass .= ' '. trim($labelClass);
        return $this;
    }

    /**
     * Set required flag
     * @param bool $required
     * @return $this
     * @since 5.0.0-a3
     */
    public function setRequired(bool $requ)
    {
        $this->requ = $requ;
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
     * Set label typ to floating
     * @return $this
     * @since 5.0.0-b4
     */
    public function setLabelTypeFloat()
    {
        $this->labelType = 'form-floating';
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
     * 
     * @param array $param
     * @return bool
     * @since 4.5
     */
    final public function initFromYml(array $param, array $viewVars)
    {
        if (!count($param)) {
            return $this;
        }

        $functions = array_filter($param, function ($value, $func) {
            return method_exists($this, 'set'.ucfirst($func));
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($functions as $func => $value) {

            $fromVar = is_string($value) && substr($value, 0, 1) === '$'
                     ? substr($value, 1)
                     : false;

            if ($fromVar && isset($viewVars[$fromVar])) {
                $value = $viewVars[$fromVar];
            }

            $this->{'set'.ucfirst($func)}($value);            
        }

        return $this;
    }

    /**
     * Optional init function
     * @return void
     * @ignore
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