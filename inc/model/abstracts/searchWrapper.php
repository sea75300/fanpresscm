<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Object search wrapper object
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\abstracts
 * @since 3.5
 * @package fpcm\model\abstracts
 * @abstract
 */
abstract class searchWrapper extends staticModel {

    const COMBINATION_AND = 0;

    const COMBINATION_OR = 1;

    const COMBINATION_STR_AND = 'and';

    const COMBINATION_STR_OR = 'or';

    /**
     * Multiple search flag
     * @var bool
     * @since 4.3
     */
    protected $isMultiple = false;

    /**
     * Field order array
     * @var array
     */
    protected array $fieldOrder = [];

    /**
     * Search conditions array
     * @var array
     * @since 5.3.0-dev
     */
    protected array $filterParams;

    /**
     * Query assign result object
     * @var \fpcm\model\dbal\queryAssignResult
     * @since 5.3.0-dev
     */
    protected \fpcm\model\dbal\queryAssignResult $queryAssignResult;

    /**
     * Liefert Daten zurück, die über Eigenschaften erzeugt wurden
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Funktion liefert Informationen zurpck, ob Suchparameter vorhanden
     * @return bool
     */
    public function hasParams()
    {
        return count($this->data) ? true : false;
    }

    /**
     * Is multiple flag set
     * @return bool
     * @since 4.2
     */
    public function isMultiple() : bool
    {
        return $this->isMultiple;
    }

    /**
     * Set filter params
     * @param array $filterParams
     * @return $this
     * @since 5.3.0-dev
     */
    public function setFilterParams(array $filterParams)
    {
        if (isset($filterParams['sort'])) {
            unset($filterParams['sort']);
        }

        $this->filterParams = $filterParams;
        return $this;
    }

    /**
     * Sets multiple lag
     * @param bool $isMultiple
     * @return $this
     * @since 4.3
     */
    public function setMultiple(bool $isMultiple = true)
    {
        $this->isMultiple = $isMultiple;
        return $this;
    }

    /**
     * Returns condition for given value
     * @param string $condition
     * @param string $query
     * @return string
     */
    public function getCondition(string $condition, string $query)
    {
        $value = $this->{'combination'.ucfirst($condition)};
        if ($value === self::COMBINATION_AND) {
            return ' AND '.$query;
        }

        if ($value === self::COMBINATION_OR) {
            return ' OR '.$query;
        }

        return $query;
    }

    /**
     * Assigns filter params conditions
     * @param \fpcm\model\http\filterParam $obj
     * @return void
     */
    private function assignCondition(\fpcm\model\http\filterParam $obj) : void
    {
        $cond = $obj->getCombination();

        $c = match ($cond) {
            self::COMBINATION_STR_AND => ' AND ',
            self::COMBINATION_STR_OR => ' OR ',
            '(' => ' ( ',
            ')' => ' ) ',
            default => ''
        };

        if (!trim($c)) {
            return;
        }

        $this->queryAssignResult->setQueries($c);
    }

    /**
     * Assigns fields annd values from filter params
     * @param \fpcm\model\http\filterParam $obj
     * @return void
     */
    private function assignFieldAndValue(\fpcm\model\http\filterParam $obj) : void
    {
        $field = $obj->getField();
        $value = $obj->getValue();

        if (!$field) {
            return;
        }

        $this->data[$field] = $value;

        $prep = 'prepare'.ucfirst($field);
        if (method_exists($this, $prep)) {
            $this->{$prep}();
        }

        $afn = 'assign'.ucfirst($field);
        if (!method_exists($this, $afn)) {
            trigger_error(sprintf('No assign method %s found for %s in %s', $afn, $field, get_class($this)));
            return;
        }

        $this->{$afn}();
    }

    /**
     * Get database layer instance
     * @return \fpcm\classes\database
     * @since 5.3.0-dev
     */
    protected function getDB() : \fpcm\classes\database
    {
        return \fpcm\classes\loader::getObject('\fpcm\classes\database');
    }

    /**
     * Parse and assigns UI filter params array to queries
     * @return \fpcm\model\dbal\queryAssignResult
     */
    final public function prepareFilterParams() : \fpcm\model\dbal\queryAssignResult
    {
        $this->queryAssignResult = new \fpcm\model\dbal\queryAssignResult();
        if (!count($this->filterParams)) {
            return $this->queryAssignResult;
        }

        foreach ($this->filterParams as $value) {
            $obj = new \fpcm\model\http\filterParam($value);
            $this->assignCondition($obj);
            $this->assignFieldAndValue($obj);
        }

        return $this->queryAssignResult;
    }

    /**
     * Prepare values
     * @param array $filter
     * @return void
     * @since 5.3.0-dev
     */
    public function prepareValues(array &$filter) : void
    {
        return;
    }

    /**
     * Prepare order string
     * @param string $field
     * @param string $order
     * @return void
     * @since 5.3.0-dev
     */
    public function prepareOrder(string $field, string $order) : void
    {
        $fields = $this->getOrderFields();
        if (!count($fields)) {
            return;
        }

        if (!in_array($field, $fields)) {
            $field = $this->getDefaultOrder();
        }

        if (!in_array($order, ['desc', 'asc'])) {
            $order = ' desc';
        }

        $this->orderby = [sprintf("%s %s", $field, strtoupper($order))];
    }

    /**
     * Returns field whitelist for ordering
     * @return array
     * @since 5.3.0-dev
     */
    protected function getOrderFields() : array
    {
        return [];
    }

    /**
     * Retrun deafult order field
     * @return string
     * @since 5.3.0-dev
     */
    public function getDefaultOrder() : string
    {
        return '';
    }
}
