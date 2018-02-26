<?php

/**
 * System check Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
     * 
     * @return string
     */
    public function getName()
    {
        return 'syscheck';
    }

    /**
     * 
     * @return array
     */
    public function getPermissions()
    {
        return ['system' => 'options'];
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        $this->runCheck();
        return implode(PHP_EOL, array('<table class="fpcm-ui-table fpcm-ui-font-small2" style="overflow:auto;">', implode(PHP_EOL, $this->tableContent), '</table>'));
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
     * 
     * @return int
     */
    public function getPosition()
    {
        return 5;
    }

    /**
     * 
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_MEDIUM;
    }

    /**
     * Check ausführen
     */
    protected function runCheck()
    {

        $sysCheckAction = new \fpcm\controller\ajax\system\syscheck(true);
        $rows = $sysCheckAction->processCli();

        $this->tableContent[] = '<tr><td class="fpcm-td-spacer"></td></tr>';

        $options = array_slice($rows, 16, 2);

        /* @var $data \fpcm\model\system\syscheckOption */
        foreach ($options as $description => $data) {
            $checkres = (new \fpcm\view\helper\boolToText($description))->setValue($data->getResult());
            $this->tableContent[] = "<tr><td>{$checkres} {$description}</td></tr>";
        }

        $folders = array_slice($rows, -13);
        foreach ($folders as $description => $data) {
            $checkres = (new \fpcm\view\helper\boolToText($description))->setValue($data->getResult())->setText($data->getResult() ? 'GLOBAL_WRITABLE' : 'GLOBAL_NOT_WRITABLE');
            $this->tableContent[] = "<tr><td>{$checkres} {$description}</td></tr>";
        }
    }

}
