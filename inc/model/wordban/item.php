<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\wordban;

/**
 * Word Ban Item Object
 * 
 * @package fpcm\model\wordban
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.2.0
 */
class item extends \fpcm\model\abstracts\dataset
implements \fpcm\model\interfaces\isCsvImportable {

    /**
     * gesuchter Text
     * @var string
     */
    protected $searchtext;

    /**
     * Text für Ersetzung
     * @var string
     */
    protected $replacementtext;

    /**
     * Text ersetzen
     * @var bool
     * @since 3.5
     */
    protected $replacetxt;

    /**
     * Artikel muss freigeschalten werden
     * @var bool
     * @since 3.5
     */
    protected $lockarticle;

    /**
     * Kommentar muss freigegeben werden
     * @var bool
     * @since 3.5
     */
    protected $commentapproval;

    /**
     * Action-String für edit-Action
     * @var string
     */
    protected $editAction = 'wordban/edit&id=';

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->table = \fpcm\classes\database::tableTexts;
        parent::__construct($id);
    }

    /**
     * gesuchter Text zurückgeben
     * @return string
     */
    public function getSearchtext()
    {
        return $this->searchtext;
    }

    /**
     * Text für Ersetzung zurückgeben
     * @return string
     */
    public function getReplacementtext()
    {
        return $this->replacementtext;
    }

    /**
     * Status das Text ersetzt wird
     * @return bool
     * @since 3.5
     */
    function getReplaceTxt()
    {
        return $this->replacetxt;
    }

    /**
     * Status ob Artikel überprüft werden muss
     * @return bool
     * @since 3.5
     */
    function getLockArticle()
    {
        return $this->lockarticle;
    }

    /**
     * Status ob Kommentar freigegeben werden muss
     * @return bool
     * @since 3.5
     */
    function getCommentApproval()
    {
        return $this->commentapproval;
    }

    /**
     * gesuchter Text setzen
     * @param string $searchtext
     * @since 3.5
     */
    public function setSearchtext($searchtext)
    {
        $this->searchtext = $searchtext;
    }

    /**
     * Text für Ersetzung setzen
     * @param string $replacementtext
     * @since 3.5
     */
    public function setReplacementtext($replacementtext)
    {
        $this->replacementtext = $replacementtext;
    }

    /**
     * Status das Text ersetzt wird setzen
     * @param bool $replacetxt
     * @since 3.5
     */
    function setReplaceTxt($replacetxt)
    {
        $this->replacetxt = (int) $replacetxt;
    }

    /**
     * Status ob Artikel überprüft werden muss setzen
     * @param bool $lockarticle
     * @since 3.5
     */
    function setLockArticle($lockarticle)
    {
        $this->lockarticle = (int) $lockarticle;
    }

    /**
     * Status ob Kommentar freigegeben werden muss setzen
     * @param bool $commentapproval
     * @since 3.5
     */
    function setCommentApproval($commentapproval)
    {
        $this->commentapproval = (int) $commentapproval;
    }

    /**
     * Executes save process to database and events
     * @return bool
     * @see \fpcm\model\abstracts\dataset::save
     */
    public function save()
    {
        return parent::save() === false ? false : true;
    }

    /**
     * Assigns csv row to internal fields
     * @param array $csvRow
     * @return bool
     * @since 4.5-b8
     */
    public function assignCsvRow(array $csvRow): bool
    {
        $data = array_intersect_key($csvRow, array_flip($this->getFields()));

        if (!count($data)) {
            trigger_error('Failed to assign data, empty field set!');
            return false;
        }

        if (empty($data['searchtext'])) {
            trigger_error('Failed to assign data, searchtext cannot be empty!');
            return false;
        }

        if (empty($data['replacementtext'])) {
            trigger_error('Failed to assign data, replacementtext cannot be empty!');
            return false;
        }

        $obj = clone $this;

        $obj->setSearchtext($data['searchtext']);
        $obj->setReplacementtext($data['replacementtext']);
        $obj->setReplaceTxt($data['replacetxt'] ?? 0);
        $obj->setLockArticle($data['lockarticle'] ?? 0);
        $obj->setCommentApproval($data['commentapproval'] ?? 0);

        if (!$obj->save())  {
            trigger_error('Failed to import text cencor item.'.PHP_EOL.PHP_EOL.print_r($data, true));
            return false;
        }

        unset($obj);
        return true;
    }

    /**
     * Fetch fields for mapping
     * @return array
     * @since 4.5-b8
     */
    public function getFields(): array
    {
        return [
            'WORDBAN_NAME' => 'searchtext',
            'WORDBAN_REPLACEMENT_TEXT' => 'replacementtext',
            'WORDBAN_REPLACETEXT' => 'replacetxt',
            'WORDBAN_APPROVE_ARTICLE' => 'lockarticle',
            'WORDBAN_APPROVA_COMMENT' => 'commentapproval',
        ];
    }

    
    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since 4.1
     */
    protected function getEventModule(): string
    {
        return 'wordban';
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since 4.1
     */
    protected function afterSaveInternal(): bool
    {
        $this->cache->cleanup();
        return true;
    }

    /**
     * Is triggered after successful database update
     * @see \fpcm\model\abstracts\dataset::afterUpdateInternal
     * @return bool
     * @since 4.1
     */
    protected function afterUpdateInternal(): bool
    {
        $this->cache->cleanup();
        $this->init();
        return true;
    }

}
