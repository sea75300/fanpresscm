<?php

/**
 * AJAX syscheck controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * AJAX-Controller - System Check
 * 
 * @package fpcm\controller\ajax\system\syscheck
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class syscheck extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\system\syscheck;

    /**
     *
     * @var bool
     */
    protected $installer;

    /**
     * Add no view to returned values
     * @var bool
     */
    protected $noView;

    /**
     * 
     * @param bool $noView
     */
    public function __construct($noView = false)
    {
        $this->noView = $noView;
        parent::__construct();
    }

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath() : string
    {
        return $this->noView || $this->getRequestVar('sendstats') ? '' : 'system/syscheck';
    }

    /**
     * 
     * @return boolean
     */
    public function request()
    {
        if ($this->getRequestVar('sendstats')) {
            $this->submitStatsData();
            return false;
        }

        return true;
    }

    /**
     * @see \fpcm\controller\abstracts\controller::hasAccess()
     * @return boolean
     */
    public function hasAccess()
    {
        if (!\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
            return true;
        }

        if (\fpcm\classes\baseconfig::dbConfigExists() && $this->session->exists() && $this->permissions->check(['system' => 'options'])) {
            return true;
        }

        return false;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->view->assign('checkOptions', $this->getCheckOptions());
        $this->view->render();
    }

    /**
     * System-Check-Optionen ermitteln
     * @return array
     */
    private function getCheckOptions()
    {
        return $this->events->trigger('runSystemCheck', $this->getCheckOptionsSystem());
    }

    /**
     * 
     * @return boolean
     */
    private function submitStatsData()
    {
        $data = array_slice($this->processCli(), 0, 18);

        $text = 'Statistical data ' . \fpcm\classes\tools::getHash(\fpcm\classes\dirs::getRootUrl()) . PHP_EOL . PHP_EOL;

        /* @var $value \fpcm\model\system\syscheckOption */
        foreach ($data as $key => $value) {

            if (!trim($key)) {
                continue;
            }

            $text .= '- ' . str_pad(trim($key), 40, '.') . ': ' . $value->getCurrent() . PHP_EOL;
        }

        $text .= PHP_EOL;

        $stats = new \fpcm\model\dashboard\sysstats();
        $data = explode(PHP_EOL, strip_tags($stats->getContent()));

        foreach ($data as $value) {
            $value = explode(':', $value);

            if (!isset($value[0]) || !isset($value[1])) {
                continue;
            }

            $text .= '- ' . str_pad(trim($value[0]), 40, '.') . ': ' . $value[1] . PHP_EOL;
        }

        $email = new \fpcm\classes\email('sea75300@yahoo.de', 'FanPress CM Stats', $text);
        $email->submit();

        return true;
    }

    public function processCli()
    {
        return $this->events->trigger('runSystemCheck', $this->getCheckOptionsSystem());
    }

}

?>