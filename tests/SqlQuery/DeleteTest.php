<?php

namespace TomWright\Database\QueryBuilder\Tests\SqlQuery;


use PHPUnit\Framework\TestCase;
use TomWright\Database\QueryBuilder\SqlQuery;

class DeleteTest extends TestCase
{

    public function testQueryDelete()
    {
        $q = new SqlQuery('DELETE');
        $q->setTable('users');
        $q->addWhere('username', 'Tod');
        $q->buildQuery();

        $sql = "DELETE FROM users WHERE username = :_{$q->getQueryId()}_where_username;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_username" => 'Tod',
        ], $q->getBinds());
    }

}