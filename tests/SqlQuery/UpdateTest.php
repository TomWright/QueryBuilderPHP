<?php

namespace TomWright\Database\QueryBuilder\Tests\SqlQuery;


use PHPUnit\Framework\TestCase;
use TomWright\Database\QueryBuilder\SqlQuery;

class UpdateTest extends TestCase
{

    public function testQueryUpdate()
    {
        $q = new SqlQuery('UPDATE');
        $q->setTable('users');
        $q->addValue('username', 'Tod');
        $q->addWhere('username', 'Frank');
        $q->buildQuery();

        $sql = "UPDATE users SET username = :_{$q->getQueryId()}_update_bind_username WHERE username = :_{$q->getQueryId()}_where_username;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_update_bind_username" => 'Tod',
            ":_{$q->getQueryId()}_where_username" => 'Frank',
        ], $q->getBinds());

        $q = new SqlQuery('UPDATE');
        $q->setTable('users');
        $q->addValue('username', 'Tod');
        $q->addRawValue('dt_modified', 'NOW()');
        $q->addWhere('username', 'Frank');
        $q->buildQuery();

        $sql = "UPDATE users SET username = :_{$q->getQueryId()}_update_bind_username, dt_modified = NOW() WHERE username = :_{$q->getQueryId()}_where_username;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_update_bind_username" => 'Tod',
            ":_{$q->getQueryId()}_where_username" => 'Frank',
        ], $q->getBinds());
    }

}