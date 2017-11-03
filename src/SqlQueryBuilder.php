<?php

namespace TomWright\Database\QueryBuilder;


class SqlQueryBuilder
{

    /**
     * Returns a new SqlQuery
     * @param string $queryType
     * @return SqlQuery
     */
    public function create(string $queryType): SqlQuery
    {
        return new SqlQuery($queryType);
    }

    /**
     * Returns a new SELECT query
     * @return SqlQuery
     */
    public function select(): SqlQuery
    {
        return $this->create('SELECT');
    }

    /**
     * Returns a new UPDATE query
     * @return SqlQuery
     */
    public function update(): SqlQuery
    {
        return $this->create('UPDATE');
    }

    /**
     * Returns a new INSERT query
     * @return SqlQuery
     */
    public function insert(): SqlQuery
    {
        return $this->create('INSERT');
    }

    /**
     * Returns a new DELETE query
     * @return SqlQuery
     */
    public function delete(): SqlQuery
    {
        return $this->create('DELETE');
    }

}