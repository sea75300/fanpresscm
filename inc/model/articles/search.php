<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\articles;

    /**
     * Article search wrapper object
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\articles
     * @since FPCM 3.5
     * 
     *
     * @property int $ids Artikel-IDs
     * @property int $user via Benutzer
     * @property int $category via Kategorie
     * @property int $datefrom seit Datum X.Y.Z
     * @property int $dateto bis Datum X.Y.Z
     * @property string $title via Title-Inhalt
     * @property string $content via content-Inhalt
     * @property string $combination logische Verknüpfung AND/OR
     * @property bool $postponed nur geplante Artikel
     * @property bool $archived nur archivierte Artikel
     * @property bool $pinned nur gepinnte Artikel
     * @property bool $comment Kommentare sind aktiv
     * @property bool $comments Kommentare sind aktiv
     * @property bool $deleted nur gelöschte Artikel
     * @property bool $draft nur Entwürfe
     * @property bool $drafts nur Entwürfe
     * @property bool $active nur aktive Artikel
     * @property bool $approval nur freizugebende Artikel
     * @property array $limit Abfrage einschränken
     * @property array $orderby Array von Sortierungen in SQL-Syntax
     */ 
    class search extends \fpcm\model\abstracts\searchWrapper {
        
    }