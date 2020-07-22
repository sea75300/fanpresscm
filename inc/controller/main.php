<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller;

/**
 * Main controller
 * 
 * @package fpcm\controller\main
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class main {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        if (version_compare(PHP_VERSION, FPCM_PHP_REQUIRED, '<')) {
            exit('FanPress CM requires at least PHP ' . FPCM_PHP_REQUIRED . ' or better!');
        }

        if (!\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
            exit('You have to install FanPress CM 3 before using it.');
        }
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function exec()
    {
        $controllers = \fpcm\classes\baseconfig::getControllers();

        $module = \fpcm\classes\loader::getObject('\fpcm\model\http\request')->fromGet('module');
        if (!$module) {
            header('Location: ' . \fpcm\classes\tools::getControllerLink('system/login'));
            return true;
        }

        $class = $controllers[$module] ?? '';
        if (strpos($class, 'fpcm\\modules\\') === false) {
            $class = "fpcm/controller/" . $class;
        }

        $class = str_replace('/', '\\', $class);
        if (defined('FPCM_DEBUG_ROUTES') && FPCM_DEBUG_ROUTES) {
            fpcmLogSystem("Route for {$module} to destionation {$class}");
        }

        if (!class_exists($class)) {
            trigger_error('Undefined controller called: ' . $module.', Class: '.$class);
            $this->errorPage("The requested controller <b>{$module}</b> does not exist! <span class=\"fa fa-frown-o\"></span>");
        }

        /**
         * @var abstracts\controller
         */
        $controller = new $class();
        $this->isExecutable($controller, $class, $module);

        if (!$controller->hasAccess() || !$controller->request()) {
            return false;
        }

        if ($controller instanceof interfaces\requestFunctions) {
            $controller->processButtons();
        }
        
        $controller->process();
    }

    /**
     * Fehlerseite ausgeben
     * @param string $text
     */
    private function errorPage($text)
    {
        $view = new \fpcm\view\error($text);
        $view->render(true);
    }

    /**
     * Checks if controller can be executed
     * @return void
     */
    private function isExecutable($instance, string $class, string $op)
    {
        if (strpos($class, 'fpcm\\modules\\') !== false &&
            !$instance instanceof abstracts\module\controller &&
            !$instance instanceof abstracts\module\ajaxController) {

            $action = \fpcm\module\module::getKeyFromClass($class) . ' :: '.$op;
            $parent  = 'fpcm\controller\abstracts\module\controller OR abstracts\module\ajaxController';
            
        }
        elseif (!$instance instanceof abstracts\controller) {
            $action = $op;
            $parent = 'fpcm\controller\abstracts\controller';
        }

        if (!isset($action) && !isset($parent)) {
            return;
        }
        
        $errMsg = sprintf("ERROR: The controller for <b>%s</b> must be an instance of <b>%s</b>.", $action, $parent);
        trigger_error($errMsg);
        $this->errorPage($errMsg);
    }

}
