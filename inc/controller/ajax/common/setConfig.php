<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX-Controller zum Erzeugen und Ausgeben einer neuen Nachricht
 * 
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class setConfig extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\common\isAccessibleTrue;

    private $whiteList = ['file_view', 'dashboardpos', 'dashboard_containers_disabled'];

    /**
     * Controller-Processing
     */
    public function request()
    {
        $op = $this->request->fromPOST('op');
        if (!trim($op)) {
            $op = 'change';
        }

        $fn = match ($op) {
            'change' => 'setOptionValue',
            'reset' => 'resetOptionValue'
        };

        return $this->{$fn}();
    }

    protected function setOptionValue(): bool
    {
        $var = $this->request->fromPOST('var');
        if (!$this->checkWhitelist($var)) {
            return false;
        }

        $usrmeta = $this->session->currentUser->getUserMeta();
        
        switch ($var) {
            case 'dashboard_containers_disabled' :
                $usrmeta[$var][] = $this->request->fromPOST('value');                
                break;
            default:
                $usrmeta[$var] = $this->request->fromPOST('value');
        }        

        $this->session->currentUser->disablePasswordSecCheck();
        $this->session->currentUser->setPassword(null);
        $this->session->currentUser->setUserMeta($usrmeta);
        return $this->session->currentUser->update() === true;
    }

    /**
     * 
     * @return bool
     */
    protected function resetOptionValue(): bool
    {
        $var = $this->request->fromPOST('var');
        if (!$this->checkWhitelist($var)) {
            return false;
        }

        switch ($var) {
            case 'dashboardpos' :
                $return = $this->session->getCurrentUser()->resetDashboard();
                break;
            default:
                $return = true;
        }

        return $return;
    }

    /**
     * 
     * @param string $var
     * @return bool
     */
    private function checkWhitelist(string $var): bool
    {
        if (!in_array($var, $this->whiteList)) {
            trigger_error('Invalid variable ' . $var);
            return false;
        }

        return true;
    }

}
