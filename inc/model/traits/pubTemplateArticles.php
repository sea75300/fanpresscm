<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;


/**
 * Trait for return of empty getEventModule function
 * 
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
trait pubTemplateArticles {
    
    /**
     * Parse perma link tag
     * @param mixed $value
     * @param array $return
     * @since 4.4
     */
    protected function parsePermaLink($value, array &$return)
    {
        $return[0] = "<a href=\"$value\" class=\"fpcm-pub-permalink\">";
        $return[1] = '</a>';
    }

    /**
     * Parse comment link tag
     * @param mixed $value
     * @param array $return
     * @since 4.4
     */
    protected function parseCommentLink($value, array &$return)
    {
        if (!$this->commentsEnabled) {
            $return[0] = '';
            $return[1] = '';
            return true;
        }

        $return[0] = "<a href=\"$value\" class=\"fpcm-pub-commentlink\">";
        $return[1] = '</a>';
    }

}

?>