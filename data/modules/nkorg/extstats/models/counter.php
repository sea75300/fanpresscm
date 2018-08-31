<?php

namespace fpcm\modules\nkorg\extstats\models;

class counter extends \fpcm\model\abstracts\tablelist {

    const MODE_MONTH = 1;
    const MODE_YEAR = 2;
    const MODE_DAY = 3;
    const SRC_ARTICLES = 'articles';
    const SRC_COMMENTS = 'comments';
    const SRC_SHARES = 'shares';
    const SRC_FILES = 'files';

    protected $mode;
    protected $months;
    protected $table;
    protected $createTimeVar = 'createtime';

    public function fetchArticles($start, $stop, $mode = 1)
    {
        $this->table = \fpcm\classes\database::tableArticles;
        return $this->fetchData($start, $stop, $mode);
    }

    public function fetchComments($start, $stop, $mode = 1)
    {
        $this->table = \fpcm\classes\database::tableComments;
        return $this->fetchData($start, $stop, $mode);
    }

    public function fetchFIles($start, $stop, $mode = 1)
    {
        $this->table = \fpcm\classes\database::tableFiles;
        $this->createTimeVar = 'filetime';
        return $this->fetchData($start, $stop, $mode);
    }

    public function fetchShares($start, $stop, $mode = 1)
    {
        $where = '1=1';

        $where .= (trim($start) ? ' AND ' . $this->createTimeVar . ' >= ' . strtotime($start) : '');
        $where .= (trim($stop) ? ' AND ' . $this->createTimeVar . ' < ' . strtotime($stop) : '');

        $values = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams())
                ->setTable(\fpcm\classes\database::tableArticles)
                ->setItem("id, title")
                ->setWhere($where)
                ->setFetchAll(true)
        );

        if (!$values) {
            return [];
        }
        
        $articles = [];
        foreach ($values as $value) {
            $articles[$value->id] = $value->title;
        }

        $values = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams())
                ->setTable(\fpcm\classes\database::tableShares)
                ->setItem("sum(sharecount) AS counted, article_id")
                ->setWhere("article_id IN (". implode(',', array_keys($articles)).") GROUP BY article_id")
                ->setFetchAll(true)
        );

        if (!$values) {
            return [];
        }
        
        foreach ($values as $value) {

            if (!isset($articles[$value->article_id])) {
                continue;
            }
            
            $len = strlen($articles[$value->article_id]);
            
            $labels[] = ( $len >= 20 ? substr($articles[$value->article_id], 0, 20).'...' : $articles[$value->article_id] ). ' ('.$value->article_id.')';
            $data[] = (string) $value->counted;
            $colors[] = $this->getRandomColor();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => '',
                    'fill' => false,
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $this->getRandomColor(),
                ]
            ]
        ];
    }

    final private function fetchData($start, $stop, $mode)
    {
        $this->mode = (int) $mode;

        $hash = \fpcm\classes\tools::getHash(__METHOD__ . json_encode(func_get_args()));
        $cache = new \fpcm\classes\cache();

        $where = '1=1';

        $where .= (trim($start) ? ' AND ' . $this->createTimeVar . ' >= ' . strtotime($start) : '');
        $where .= (trim($stop) ? ' AND ' . $this->createTimeVar . ' < ' . strtotime($stop) : '');

        $values = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams())
                ->setTable($this->table)
                ->setItem("count(id) AS counted, " . call_user_func([$this, 'getSelectItem' . ucfirst($this->dbcon->getDbtype())]))
                ->setWhere($where . ' GROUP BY dtstr ' . $this->dbcon->orderBy(['dtstr ASC']))
                ->setFetchAll(true)
        );

        if (!$values) {
            return [];
        }

        $this->months = $this->language->translate('SYSTEM_MONTHS');

        $labels = [];
        $data = [];
        $colors = [];

        $cached = ($cache->isExpired($hash) ? [] : $cache->read($hash));

        foreach ($values as $value) {

            $labels[] = $this->getLabel($value->dtstr);
            $data[] = (string) $value->counted;

            $cached[$value->dtstr] = (isset($cached[$value->dtstr]) ? $cached[$value->dtstr] : $this->getRandomColor());
            $colors[] = $cached[$value->dtstr];
        }

        $cache->write($hash, $cached, 604800);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => '',
                    'fill' => false,
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $this->getRandomColor(),
                ]
            ]
        ];
    }

    private function getLabel($data)
    {
        switch ($this->mode) {
            case self::MODE_DAY :

                $dtstr = explode('-', $data);
                $month = (int) $dtstr[1];

                return $dtstr[2] . '. ' . $this->months[$month] . ' ' . $dtstr[0];

                break;
            case self::MODE_YEAR :
                return $data;
                break;
        }

        $dtstr = explode('-', $data);
        $month = (int) $dtstr[1];

        return $this->months[$month] . ' ' . $dtstr[0];
    }

    private function getSelectItemMysql()
    {
        switch ($this->mode) {
            case self::MODE_DAY :
                return "DATE_FORMAT(FROM_UNIXTIME({$this->createTimeVar}), '%Y-%m-%d' ) AS dtstr";
                break;
            case self::MODE_YEAR :
                return "DATE_FORMAT(FROM_UNIXTIME({$this->createTimeVar}), '%Y' ) AS dtstr";
                break;
        }

        return "DATE_FORMAT(FROM_UNIXTIME({$this->createTimeVar}), '%Y-%m' ) AS dtstr";
    }

    private function getSelectItemPgsql()
    {
        switch ($this->mode) {
            case self::MODE_DAY :
                return "to_char(to_timestamp({$this->createTimeVar}), 'YYYY-MM-DD') AS dtstr";
                break;
            case self::MODE_YEAR :
                return "to_char(to_timestamp({$this->createTimeVar}), 'YYYY') AS dtstr";
                break;
        }

        return "to_char(to_timestamp({$this->createTimeVar}), 'YYYY-MM') AS dtstr";
    }

    private function getRandomColor()
    {
        $colStr = '#' . dechex(mt_rand(0, 255)) . dechex(mt_rand(0, 255)) . dechex(mt_rand(0, 255));
        return strlen($colStr) === 7 ? $colStr : str_pad($colStr, 7, dechex(mt_rand(0, 16)));
    }

}
