<?php

/**
 * System check Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * System check dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class syscheck extends \fpcm\model\abstracts\dashcontainer {

    /**
     * Table container
     * @var array
     */
    protected $tableContent = [];

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'syscheck';
    }

    /**
     * Returns permissions
     * @return array
     */
    public function getPermissions()
    {
        return ['system' => 'options'];
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        $this->runCheck();
        return implode(PHP_EOL, $this->tableContent);
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'SYSTEM_CHECK';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 5;
    }

    /**
     * Returns container height
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_SMALL_MEDIUM;
    }

    /**
     * Check ausführen
     */
    protected function runCheck()
    {

        $sysCheckAction = new \fpcm\controller\ajax\system\syscheck(true);
        $rows = $sysCheckAction->processCli();

        $options = array_slice($rows, 16, 2);

        /* @var $data \fpcm\model\system\syscheckOption */
        foreach ($options as $description => $data) {
            $checkres = (new \fpcm\view\helper\boolToText($description))->setValue($data->getResult());
            $dat  = "<div class=\"row no-gutters\">";
            $dat .= "<div class=\"col-auto px-2 fpcm-ui-center\">{$checkres}</div>";
            $dat .= "<div class=\"col px-2 \">{$description}</div>";
            $dat .= "</div>";

            $this->tableContent[] = $dat;
        }

        $folders = array_slice($rows, -13);
        foreach ($folders as $description => $data) {
            $checkres = (new \fpcm\view\helper\boolToText($description))->setValue($data->getResult())->setText($data->getResult() ? 'GLOBAL_WRITABLE' : 'GLOBAL_NOT_WRITABLE');
            
            $dat  = "<div class=\"row no-gutters\">";
            $dat .= "<div class=\"col-auto px-2 fpcm-ui-center\">{$checkres}</div>";
            $dat .= "<div class=\"col px-2 \">{$description}</div>";
            $dat .= "</div>";

            $this->tableContent[] = $dat;
        }
    }

}
