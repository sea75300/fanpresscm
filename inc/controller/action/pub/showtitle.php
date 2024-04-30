<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\pub;

/**
 * Public article title controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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
     * Konstruktor
     * @param string $action
     * @param string $param
     */
    public function __construct($action, $param)
    {
        parent::__construct();

        $this->action = $action;
        $this->param = $param;
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
                $page = $this->request->getPage();

                if (!$page) {
                    return '';
                }

                $content = ' ' . $this->param . ' ' . $page;
                break;
            case 'title' :

                $id = $this->request->fromGET('id', [
                    \fpcm\model\http\request::FILTER_CASTINT
                ]);

                if ($this->request->getModule() != 'fpcm/article' || $id === null) {
                    return;
                }

                $article = new \fpcm\model\articles\article($id);
                $content = ' ' . $this->param . ' ' . $article->getTitle();
                break;
        }
        
        print $content;
    }

}
