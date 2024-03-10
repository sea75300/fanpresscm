<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller;

/**
 * Main controller
 * 
 * @package fpcm\controller\main
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class main {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        if (version_compare(PHP_VERSION, FPCM_PHP_REQUIRED, '<')) {
            printf('FanPress CM requires at least PHP %s or better!', FPCM_PHP_REQUIRED);
            exit;                        
        }

        if (!\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
            printf('You have to install FanPress CM %s before using it.', \fpcm\classes\baseconfig::getVersionFromFile());
            exit;
        }
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function exec()
    {
        $controllers = \fpcm\classes\baseconfig::getControllers();

        $module = \fpcm\model\http\request::getInstance()->getModule();
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
            trigger_error(sprintf('Undefined controller called: %s, Class: %s', $module, $class), E_USER_ERROR);
            $this->errorPage(sprintf("The requested controller <b class=\"px-1\">%s</b> does not exist!", $module));
        }

        /**
         * @var abstracts\controller
         */
        $controller = new $class();
        $this->isExecutable($controller, $class, $module);
        
        if ($controller instanceof interfaces\isAccessible) {
            trigger_error(sprintf('The interface "fpcm\controller\interfaces\isAccessible" '
                                . 'in "%s" is deprecated since version 5.0.0-a3. '
                                . 'The interface will be removed in future versions. '
                                . 'Please remove the implements statement.'
                    , $class),
                    E_USER_DEPRECATED);
            
            define('FPCM_NOTIFICATION_DEPRECATED_ISACCESSIBLE_INTERFACE', true);
            
        }

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
        trigger_error($errMsg, E_USER_ERROR);
        $this->errorPage($errMsg);
    }

}
