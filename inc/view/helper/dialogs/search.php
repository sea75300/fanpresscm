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
        if (!isset($fields['valueFields']) || !isset($fields['buildFields'])) {
            trigger_error(sprintf('Parameter $fields of %s requries keys "valueFields" and "buildFields"!', __METHOD__));
            return $this;
        }

        if (!is_array($fields['valueFields']) || !is_array($fields['buildFields'])) {
            trigger_error('Parameter keys "valueFields" and "buildFields" must be arrays!');
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

}
