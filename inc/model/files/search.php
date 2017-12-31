<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Files search wrapper object
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\articles
     * @since FPCM 3.6.3
     * 
     * @property int $datefrom seit Datum X.Y.Z
     * @property int $dateto bis Datum X.Y.Z
     * @property string $filename Dateiname
     * @property string $combination logische Verknüpfung AND/OR
     * @property array $limit Abfrage einschränken
     * @property array $orderby Array von Sortierungen in SQL-Syntax
     */ 
    class search extends \fpcm\model\abstracts\searchWrapper {
        
    }