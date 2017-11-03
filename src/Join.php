<?php

namespace TomWright\Database\QueryBuilder;

class Join
{

    public const JOIN = 'JOIN';
    public const INNER_JOIN = 'INNER JOIN';
    public const OUTER_JOIN = 'OUTER JOIN';
    public const LEFT_JOIN = 'LEFT JOIN';
    public const LEFT_INNER_JOIN = 'LEFT INNER JOIN';
    public const LEFT_OUTER_JOIN = 'LEFT OUTER JOIN';
    public const RIGHT_JOIN = 'RIGHT JOIN';
    public const RIGHT_INNER_JOIN = 'RIGHT INNER JOIN';
    public const RIGHT_OTHER_JOIN = 'RIGHT OUTER JOIN';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array|string[]
     */
    protected $conditions;


    public function __construct($type = Join::JOIN, $table = null, $conditions = [])
    {
        if (is_string($conditions)) {
            $conditions = [$conditions];
        }
        $this->setType($type);
        $this->setTable($table);
        $this->setConditions($conditions);
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }


    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }


    /**
     * @return array|\string[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }


    /**
     * @param array|\string[] $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }


    /**
     * @param string $condition
     */
    public function addCondition($condition)
    {
        if (!in_array($condition, $this->conditions)) {
            $this->conditions[] = $condition;
        }
    }


    /**
     * @return string
     */
    public function getConditionsString()
    {
        $result = '';
        if (count($this->conditions) > 0) {
            $result = implode(' AND ', $this->conditions);
        }
        return $result;
    }

}