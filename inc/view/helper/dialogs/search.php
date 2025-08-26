<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\dialogs;

/**
 * Search dialog item
 *
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class search extends \fpcm\view\helper\dialog {

    /**
     * Constructor
     * @param string $name
     */
    public function __construct(string $name = 'search')
    {
        parent::__construct('search');
    }

    /**
     * Set dialog fields, requries keys 'valueFields' and 'buildFields' as arrays
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        if (!isset($fields['valueFields']) || !isset($fields['buildFields']) || !isset($fields['sortFields'])) {
            trigger_error(sprintf('Parameter $fields of %s requries keys "valueFields", "buildFields" and "sortFields"!', __METHOD__));
            return $this;
        }

        if (!is_array($fields['valueFields']) || !is_array($fields['buildFields']) || !is_array($fields['sortFields'])) {
            trigger_error('Parameter keys "valueFields", "buildFields" and "sortFields" must be arrays!');
            return $this;
        }

        return parent::setFields($fields);
    }

    /**
     * Returns list with defautl combinations
     * @return array
     */
    public function getDefaultCombinations() : array
    {
        return [
            'ARTICLE_SEARCH_LOGICNONE' => '',
            'ARTICLE_SEARCH_LOGICAND' => 'and',
            'ARTICLE_SEARCH_LOGICOR' => 'or',
            '(' => '(',
            ')' => ')'
        ];
    }

    /**
     * Returns Javascript language vars
     * @return array
     * @since 5.3.0-dev
     */
    public function getJsLangVars() : array
    {
        $vars = parent::getJsLangVars();
        
        $vars[] = 'ARTICLE_SEARCH_USER';
        $vars[] = 'ARTICLE_SEARCH_DATE_TO';
        $vars[] = 'ARTICLE_SEARCH_DATE_FROM';
        $vars[] = 'FILE_LIST_SEARCHTEXT';
        $vars[] = 'EDITOR_IMGALTTXT';
        
        return $vars;
    }

}
