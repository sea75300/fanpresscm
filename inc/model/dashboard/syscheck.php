<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * System check dashboard container object
 * 
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class syscheck extends \fpcm\model\abstracts\dashcontainer {

    /**
     * Returns name
     * @return string
     */
    public function getName()
    {
        return 'syscheck';
    }

    /**
     * Container is accessible
     * @see \fpcm\model\interfaces\isAccessible::isAccessible()
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options;
    }

    /**
     * Returns content
     * @return string
     */
    public function getContent()
    {
        $sysCheckAction = new \fpcm\controller\ajax\system\syscheck(true);

        $content = [];
        
        /* @var $data \fpcm\model\system\syscheckOption */
        foreach ($sysCheckAction->getOptions() as $description => $data) {
            
            $txt = strip_tags($description);
            $checkres = (new \fpcm\view\helper\boolToText($txt))->setValue($data->getResult());

            $dat  = "<div class=\"row g-0\">";
            $dat .= "<div class=\"col-auto px-2 text-center\">{$checkres}</div>";
            $dat .= "<div class=\"col px-2 \">{$description}</div>";
            $dat .= "</div>";

            $content[] = $dat;
        }

        foreach ($sysCheckAction->getFolders() as $description => $data) {

            $txt = strip_tags($description);
            $checkres = (new \fpcm\view\helper\boolToText($txt))->setValue($data->getResult())->setText($data->getResult() ? 'GLOBAL_WRITABLE' : 'GLOBAL_NOT_WRITABLE');
            
            $dat  = "<div class=\"row g-0\">";
            $dat .= "<div class=\"col-auto px-2 text-center\">{$checkres}</div>";
            $dat .= "<div class=\"col px-2 \">{$description}</div>";
            $dat .= "</div>";

            $content[] = $dat;
        }        
        
        return implode(PHP_EOL, $content);
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
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        if (!$this->permissions->system->options) {
            return null;
        }

        return (new \fpcm\view\helper\linkButton('runSyscheck'))
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('system/options', ['syscheck' => 1]))
                ->setIcon('sync')
                ->setText('SYSCHECK_COMPLETE');
    }

}
