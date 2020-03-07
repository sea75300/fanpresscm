<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP request handler object (incomplete!!!)
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since FPCM 4.4
 * @ignore
 */
final class request {

    /**
     * HTTP Filter strip_tags
     */
    const FILTER_STRIPTAGS = 1;

    /**
     * HTTP Filter htmlspecialchars
     */
    const FILTER_HTMLSPECIALCHARS = 2;

    /**
     * HTTP Filter htmlentities
     */
    const FILTER_HTMLENTITIES = 3;

    /**
     * HTTP Filter stripslashes
     */
    const FILTER_STRIPSLASHES = 4;

    /**
     * HTTP Filter htmlspecialchars_decode
     */
    const FILTER_HTMLSPECIALCHARS_DECODE = 5;

    /**
     * HTTP Filter html_entity_decode
     */
    const FILTER_HTMLENTITY_DECODE = 6;

    /**
     * HTTP Filter trim
     */
    const FILTER_TRIM = 7;

    /**
     * HTTP Filter json_decode
     */
    const FILTER_JSON_DECODE = 8;

    /**
     * HTTP Filter intval
     */
    const FILTER_CASTINT = 9;

    /**
     * HTTP Filter crypt::decrypt
     */
    const FILTER_DECRYPT = 10;

    /**
     * HTTP Filter urldecode
     */
    const FILTER_URLDECODE = 11;

    /**
     * HTTP Filter base64_decode
     */
    const FILTER_BASE64DECODE = 12;

    /**
     * HTTP Filter ucfirst
     */
    const FILTER_FIRSTUPPER = 13;

    /**
     * Regex filter
     */
    const FILTER_REGEX = 14;

    /**
     * Regex replace filter
     */
    const FILTER_REGEX_REPLACE = 15;

    /**
     * filter_var sanitize filter
     */
    const FILTER_SANITIZE = 16;

    /**
     * Regex expression param
     */
    const PARAM_REGEX = 'regex';

    /**
     * Regex replace filter param
     */
    const PARAM_REGEX_REPLACE = 'regexReplace';

    /**
     * json_decode as object param
     */
    const PARAM_JSON_ASOBJECT = 'object';

    /**
     * strip_tag allowed tags param
     */
    const PARAM_STRIPTAGS_ALLOWED = 'allowedtags';

    /**
     * strip_tag allowed tags param
     */
    const PARAM_HTML_MODE = 'mode';

    /**
     * filter_var sanitize filter param
     */
    const PARAM_SANITIZE = 'filter';

    /**
     * Filter functions mapping for
     * @see request::assignFilterCommon
     * @var array
     * @ignore
     */
    private $filterFuncsMap;

    /**
     * @ignore
     */
    public function __construct() {

        $this->filterFuncsMap = [
            self::FILTER_HTMLSPECIALCHARS => 'htmlspecialchars',
            self::FILTER_HTMLENTITIES => 'htmlentities',
            self::FILTER_HTMLSPECIALCHARS_DECODE => 'htmlspecialchars_decode',
            self::FILTER_HTMLENTITY_DECODE => 'html_entity_decode',
        ];

    }

    /**
     * Fetch module string from GET-request
     * @return int
     */
    public function getModule() : string
    {
        $value = $this->fromGET('module', [
            self::FILTER_REGEX,
            self::PARAM_REGEX => '/^([a-z0-9]+)\/{1}([a-z0-9]+)\/?([a-z0-9]*)/i'
        ]);

        unset($value[0]);
        if (isset($value[3]) && !trim($value[3])) {
            unset($value[3]);
        }

        if (isset($value[4]) && !trim($value[4])) {
            unset($value[4]);
        }

        return is_array($value) ? implode('/', $value) : $value;
    }

    /**
     * Fetch ID from GET-request
     * @return int
     */
    public function getID() : int
    {
        $value = $this->fromGET('id');
        if (!$value) {
            return 0;
        }

        return $this->filter($value, [self::FILTER_CASTINT]);
    }

    /**
     * Fetch multiple ids from POST-request
     * @return array
     */
    public function getIDs() : array
    {
        $values = $this->fromPOST('ids', [self::FILTER_CASTINT]);
        if (!is_array($values) || !count($values)) {
            return [];
        }

        return $values;
    }

    /**
     * Fetch mode as integer value from GET-request
     * @return int
     */
    public function getIntMode() : int
    {
        $value = $this->fromGET('mode');
        if (!$value) {
            return 0;
        }

        return $this->filter($value, [self::FILTER_CASTINT]);
    }

    /**
     * Fetch current pagefrom request
     * @return int
     */
    public function getPage() : int
    {
        $value = $this->fetchAll('page');
        if (!$value) {
            return 0;
        }

        return $this->filter($value, [self::FILTER_CASTINT]);
    }

    /**
     * Check if get parameter contains message indicator
     * @param string $msg
     * @return bool
     */
    public function hasMessage(string $msg) : bool
    {
        return $this->fromGET($msg, []) ? true : false;
    }

    /**
     * Fetch data from GET request
     * @param string $var
     * @param array $filters
     * @return mixed[scalar|array]
     */
    public function fromGET($var, array $filters = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        if ($var === null) {
            return $_GET;
        }

        $value = $_GET[$var] ?? null;
        if ($value === null || !count($filters)) {
            return $value;
        }

        return $this->filter($value, $filters);
    }

    /**
     * Fetch data from POST request
     * @param string $var
     * @param array $filters
     * @return mixed[scalar|array]
     */
    public function fromPOST($var, array $filters = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        if ($var === null) {
            return $_POST;
        }

        $value = $_POST[$var] ?? null;
        if ($value === null || !count($filters)) {
            return $value;
        }

        return $this->filter($value, $filters);   
    }
    
    /**
     * Fetch data from cookies request
     * @param string $var
     * @param array $filters
     * @return mixed[scalar|array]
     */
    public function fromCookie($var, array $filters = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        $value = $_COOKIE[$var] ?? null;
        if ($value === null || !count($filters)) {
            return $value;
        }

        return $this->filter($value, $filters);   
    }

    /**
     * Fetch data from $_REQUEST and $_COOKIE, use carfully!!!
     * @param string $var
     * @param array $filters
     * @return mixed[scalar|array]
     * @ignore
     */
    public function fetchAll($var, array $filters = [self::FILTER_STRIPTAGS, self::FILTER_STRIPSLASHES, self::FILTER_TRIM])
    {
        $value = array_merge($_REQUEST, $_COOKIE)[$var] ?? null;
        if ($value === null || !count($filters)) {
            return $value;
        }

        return $this->filter($value, $filters);
    }


    /**
     * Filter request values
     * 
     * @param type $values
     * @param array $filters (@see FILTER_* constants)<br>
     * 
     * allowedtags: allowed HTML tags for strip_tags<br>
     * mode: @see ENT_* constants of PHP<br>
     * object: return "json_decode" result as object or array<br>
     * regex: regex expression for preg_match/preg_filter<br>
     * regexReplace: filter expression for preg_filter
     * 
     * @return mixed[scalar|array]
     */
    public function filter($values, array $filters = [])
    {
        if (!$values) {
            return $values;
        }  

        if (is_array($values)) {

            foreach ($values as &$value) {
                $value = $this->filter($value, $filters);
            }

            return $values;
        }

        array_map(function($filter) use (&$values, $filters) {
            
            $filter = (int) $filter;
            
            $filterMethod = 'assignFilter'.$filter;
            if (method_exists($this, $filterMethod)) {
                $this->{$filterMethod}($values, $filters);
                return true;
            }
            
            $filterMethod = $this->filterFuncsMap[$filter] ?? false;            
            if (!$filterMethod) {
                return true;
            }

            $this->assignFilterCommon($value, $filterMethod, $filters);
            return true;

        }, $filters);

        return $values;        
        
    }
    
    /**
     * Common filter
     * @param mixed $value
     * @param array $filters
     * @return bool
     */
    private function assignFilterCommon(&$value, string $function, array $filters)
    {
        $value = $function($value, $filters[self::PARAM_HTML_MODE] ?? (ENT_COMPAT | ENT_HTML401) );
        return true;
    }
    
    /**
     * strip_tags filter
     * @param mixed $value
     * @param array $filters
     * @return bool
     */
    private function assignFilter1(&$value, array $filters)
    {
        $value = strip_tags($value, $filters[self::PARAM_STRIPTAGS_ALLOWED] ?? '');
        return true;
    }
    
    /**
     * stripslashes filter
     * @param mixed $value
     * @return bool
     */
    private function assignFilter4(&$value)
    {
        $value = stripslashes($value);
        return true;
    }
    
    /**
     * trim filter
     * @param mixed $value
     * @return bool
     */
    private function assignFilter7(&$value)
    {
        $value = trim($value);
        return true;
    }
    
    /**
     * json_decode filter
     * @param mixed $value
     * @param array $filters
     * @return bool
     */
    private function assignFilter8(&$value, array $filters)
    {
        $value = json_decode($value, ($filters[self::PARAM_JSON_ASOBJECT] ? false : true));
        return true;
    }
    
    /**
     * int cast filter
     * @param mixed $value
     * @return bool
     */
    private function assignFilter9(&$value)
    {
        $value = (int) $value;
        return true;
    }
    
    /**
     * Descrypt filter
     * @see \fpcm\classes\crypt::decrypt
     * @param mixed $value
     * @return bool
     */
    private function assignFilter10(&$value)
    {
        $value = \fpcm\classes\loader::getObject('\fpcm\classes\crypt')->decrypt($value);
        return true;
    }
    
    /**
     * urldecode filter
     * @param mixed $value
     * @return bool
     */
    private function assignFilter11(&$value)
    {
        $value = urldecode($value);
        return true;
    }
    
    /**
     * base64_decode filter
     * @param mixed $value
     * @return bool
     */
    private function assignFilter12(&$value)
    {
        $value = base64_decode($value);
        return true;
    }
    
    /**
     * ucfirst filter
     * @param mixed $value
     * @return bool
     */
    private function assignFilter13(&$value)
    {
        $value = ucfirst($value);
        return true;
    }
    
    /**
     * ucfirst filter
     * @param mixed $value
     * @param array $filters
     * @return bool
     */
    private function assignFilter14(&$value, array $filters)
    {
        preg_match($filters[self::PARAM_REGEX] ?? '', $value, $match);
        $value = $match;
        return true;
    }
    
    /**
     * ucfirst filter
     * @param mixed $value
     * @param array $filters
     * @return bool
     */
    private function assignFilter15(&$value, array $filters)
    {
        $value = preg_filter($filters[self::PARAM_REGEX] ?? '', $filters[self::PARAM_REGEX_REPLACE] ?? '', $value);
        return true;
    }
    
    /**
     * Sanitize filter
     * @param mixed $value
     * @param array $filters
     * @return bool
     */
    private function assignFilter16(&$value, array $filters)
    {
        if (!isset($filters[self::PARAM_SANITIZE])) {
            trigger_error('Invalid request filter, missing sanitize filter param');
            return false;
        }

        $value = filter_var($value, $filters[self::PARAM_SANITIZE]);
        return true;
    }

}
