<?php

/**
 * FanPress CM 4.x
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
 * @since 3.5
 *
 * @property int $ids Artikel-IDs
 * @property int $user via Benutzer
 * @property int $category via Kategorie
 * @property int $datefrom seit Datum X.Y.Z
 * @property int $dateto bis Datum X.Y.Z
 * @property int $combinationDatefrom AND/OR for datefrom
 * @property int $combinationDateto AND/OR for dateto
 * @property int $combinationUserid AND/OR for userid
 * @property int $combinationCategoryid AND/OR for categoryid
 * @property int $combinationPinned AND/OR for pinned
 * @property int $combinationPostponed AND/OR for postponed
 * @property int $combinationComments AND/OR for comments
 * @property int $combinationApproval AND/OR for approval
 * @property int $combinationDraft AND/OR for draft
 * @property int $combinationDeleted AND/OR for deleted
 * @property int $combinationArchived AND/OR for archived
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
 * @property bool $approval nur freizugebende Artikel
 * @property bool $metaOnly Artikel-Text nicht abrufen
 * @property bool $multipleQuery Multiple select queries
 * @property array $limit Abfrage einschränken
 * @property array $orderby Array von Sortierungen in SQL-Syntax
 */
class search extends \fpcm\model\abstracts\searchWrapper {

    const TYPE_TITLE = 0;
    const TYPE_CONTENT = 1;
    const TYPE_COMBINED = 2;
    const TYPE_COMBINED_OR = 3;

}
