<?php

namespace TomWright\Database\QueryBuilder\Tests;

use PHPUnit\Framework\TestCase;
use TomWright\Database\QueryBuilder\SqlQuery;

class QueryTest extends TestCase
{

    public function testQueryConvertInt()
    {
        $q = new SqlQuery('DELETE');
        $q->setTable('users');
        $q->addWhere('active', false);
        $q->buildQuery();

        $sql = "DELETE FROM users WHERE active = :_{$q->getQueryId()}_where_active;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_active" => 0,
        ], $q->getBinds());

        $q->addWhere('active', true);
        $q->buildQuery();

        $this->assertEquals([
            ":_{$q->getQueryId()}_where_active" => 1,
        ], $q->getBinds());

        $q->setConvertBoolToInt(false);
        $q->addWhere('active', false);
        $q->buildQuery();

        $this->assertEquals([
            ":_{$q->getQueryId()}_where_active" => false,
        ], $q->getBinds());

        $q->addWhere('active', true);
        $q->buildQuery();

        $this->assertEquals([
            ":_{$q->getQueryId()}_where_active" => true,
        ], $q->getBinds());
    }

}