<?php
    /**
     * FanPress CM main index file
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    header('X-XSS-Protection: 1; mode=block');

    require_once __DIR__.'/inc/controller/main.php';
    require_once __DIR__.'/inc/common.php';

    $mainController = new fpcm\controller\main();
    $mainController->exec();
    
?>