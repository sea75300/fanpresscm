<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\shares;

/**
 * Artikel Objekt
 * 
 * @package fpcm\model\shares
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class shares extends \fpcm\model\abstracts\tablelist {

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableShares;
        parent::__construct();
    }

    /**
     * 
     * @param int $articleId
     * @return array
     */
    public function getByArticleId(int $articleId, $item = null) : array
    {
        $search = ['article_id' => $articleId];
        if ($item) {
            $search['shareitem'] = $item;
        }

        $result = $this->dbcon->select($this->table, '*', implode(' = ? AND ', array_keys($search)).' = ?', array_values($search));
        if (!$result) {
            return [];
        }
        
        $result = $this->dbcon->fetch($result, true);
        if (!$result) {
            return [];
        }

        $list = [];
        foreach ($result as $dataSet) {
            $obj = new share();
            $obj->createFromDbObject($dataSet);
            $list[$dataSet->shareitem] = $obj;
        }

        return $list;
    }
}
