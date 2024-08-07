<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Trait for dashboard containrs based to table structure
 * 
 * @package fpcm\model\traits\
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3.1
 */
trait dashContainerCols {
    
    /**
     * Row with two columns, small right hand side
     * @param string $col1
     * @param string $col2
     * @param string $class
     * @return string
     */
    private function get2ColRow(string $col1, string $col2, string $class = '') : string
    {        
        return implode('', [
            '<div class="col px-1 '.$class.'">',
            $col1,
            '</div>',
            '<div class="col-3 px-1 text-center '.$class.'">',
            $col2,
            '</div>'
        ]);

    }
    
    /**
     * Row with two columns, small left hand side
     * @param string $col1
     * @param string $col2
     * @param string $class
     * @return string
     */
    private function get2ColRowSmallLeftAuto(string $col1, string $col2, string $class = '') : string
    {        
        return implode('', [
            '<div class="col-auto px-1 text-center '.$class.'">',
            $col1,
            '</div>',
            '<div class="col px-1 '.$class.'">',
            $col2,
            '</div>'
        ]);

    }

}
