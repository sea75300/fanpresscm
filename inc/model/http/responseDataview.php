<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\http;

/**
 * HTTP response result object for dataviews
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 * @since 4.4
 */
final class responseDataview implements \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Dataview vars
     * @var mixed
     */
    private array $dataViewVars;

    /**
     * Dataview name
     * @var string
     */
    private string $dataViewName = '';

    /**
     * Dataview message
     * @var \fpcm\view\message
     */
    private ?\fpcm\view\message $message = null;

    /**
     * Dataview pager data
     * @var array
     */
    private null|array|\fpcm\view\helper\pager $pager = null;

    /**
     * Constructor
     * @param string $dataViewName
     * @param array $dataViewVars
     * @param \fpcm\view\message|null $message
     * @param \fpcm\view\helper\pager|null $pager
     * @return type
     */
    public function __construct(
        string $dataViewName,
        array $dataViewVars,
        ?\fpcm\view\message $message = null,
        null|array|\fpcm\view\helper\pager $pager = null)
    {
        $this->dataViewVars = $dataViewVars;
        $this->dataViewName = $dataViewName;
        $this->message = $message;
        $this->pager = $pager;

        if (!$pager instanceof \fpcm\view\helper\pager) {
            return;
        }

        $pager->setReturned(true);
    }

}
