<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Search form trait
 * 
 * @package fpcm\controller\traits\common\searchParams
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3
 */
trait searchParams {

    /**
     * Assigns basic Search field vars
     * @return array
     */
    public function assignSearchFromVars()
    {
        $this->view->assign('searchCombination', [
            'ARTICLE_SEARCH_LOGICNONE' => -1,
            'ARTICLE_SEARCH_LOGICAND' => 0,
            'ARTICLE_SEARCH_LOGICOR' => 1,
        ]);
        
        $this->view->addJsLangVars(['SEARCH_WAITMSG', 'ARTICLES_SEARCH', 'ARTICLE_SEARCH_START']);
    }

    /**
     * Assigns Search combinations to searchWrapper object
     * @param array $combinations
     * @param \fpcm\model\abstracts\searchWrapper $obj
     * @return bool
     */
    public function assignParamsVars(array $combinations, \fpcm\model\abstracts\searchWrapper &$obj) : bool
    {
        if ( !count($combinations) ) {
            return false;
        }

        $combinations = array_filter($combinations, function ($value) {
            return $value == -1 ? false : true;
        });

        foreach ($combinations as $key => $value) {
            $obj->$key = (int) $value;
        }

        return true;
    }


}

?>