<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article title controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class showtitle extends \fpcm\controller\abstracts\pubController {

    /**
     * auszuführende Aktion
     * @var string
     */
    protected $action = '';

    /**
     * Daten-Parameter
     * @var string
     */
    protected $param = '';

    /**
     * UTF8-Encoding aktiv
     * @var bool
     */
    protected $isUtf8 = true;

    /**
     * Konstruktor
     * @param string $action
     * @param string $param
     */
    public function __construct($action, $param, $isUtf8 = true)
    {
        parent::__construct();

        $this->action = $action;
        $this->param = $param;
        $this->isUtf8 = $isUtf8;
    }

    /**
     * 
     * @return bool
     */
    public function hasAccess()
    {
        if (!$this->maintenanceMode(false)) {
            return false;
        }

        if ($this->ipList->ipIsLocked()) {
            return false;
        }

        return true;
    }

    /**
     * Controller ausführen
     * @return bool
     */
    public function process()
    {
        $content = '';
        switch ($this->action) {
            case 'page' :
                $page = $this->getRequestVar('page', [
                    \fpcm\classes\http::FILTER_CASTINT
                ]);

                if (!$page) {
                    return '';
                }

                $content = ' ' . $this->param . ' ' . $page;
                break;
            case 'title' :

                $id = $this->getRequestVar('id', [
                    \fpcm\classes\http::FILTER_CASTINT
                ]);

                if ($this->getRequestVar('module') != 'fpcm/article' || $id === null) {
                    return;
                }

                $article = new \fpcm\model\articles\article($id);
                $content = ' ' . $this->param . ' ' . $article->getTitle();
                break;
        }
        
        print $this->isUtf8 ? $content : utf8_decode($content);
    }

}

?>