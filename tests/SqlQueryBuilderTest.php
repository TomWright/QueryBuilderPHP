<?php


namespace TomWright\Database\QueryBuilder\Tests;


use PHPUnit\Framework\TestCase;
use TomWright\Database\QueryBuilder\SqlQueryBuilder;

class SqlQueryBuilderTest extends TestCase
{

    /**
     * @var SqlQueryBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new SqlQueryBuilder();
    }

    public function testSelectQuery()
    {
        $this->assertEquals('SELECT', $this->builder->select()->getType());
    }

    public function testUpdateQuery()
    {
        $this->assertEquals('UPDATE', $this->builder->update()->getType());
    }

    public function testInsertQuery()
    {
        $this->assertEquals('INSERT', $this->builder->insert()->getType());
    }

    public function testDeleteQuery()
    {
        $this->assertEquals('DELETE', $this->builder->delete()->getType());
    }

}