<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP response result object for dataviews
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since FPCM 4.4
 */
final class responseDataview implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Dataview vars
     * @var mixed
     */
    private $dataViewVars;

    /**
     * Dataview name
     * @var string
     */
    private $dataViewName;

    /**
     * Dataview message
     * @var \fpcm\view\message
     */
    private $message;

    /**
     * Dataview pager
     * @var \fpcm\view\helper\pager
     */
    private $pager;

    /**
     * Constructor
     * @param string $dataViewName
     * @param array $dataViewVars
     * @param \fpcm\view\message $message
     */
    public function __construct(string $dataViewName, $dataViewVars, $message = null, $pager = null)
    {
        $this->dataViewVars = $dataViewVars;
        $this->dataViewName = $dataViewName;
        $this->message = $message;
        $this->pager = $pager instanceof \fpcm\view\helper\pager ? (string) $pager : 0;
    }

}
