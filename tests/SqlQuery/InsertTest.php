<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 03/11/17
 * Time: 14:51
 */

namespace TomWright\Database\QueryBuilder\Tests;


use PHPUnit\Framework\TestCase;
use TomWright\Database\QueryBuilder\SqlQuery;

class InsertTest extends TestCase
{

    public function testQueryInsert()
    {
        $q = new SqlQuery('INSERT');
        $q->setTable('users');
        $q->addValue('username', 'Tod');
        $q->addValue('password', 'abcdef');
        $q->buildQuery();

        $sql = "INSERT INTO users SET username = :_{$q->getQueryId()}_update_bind_username, password = :_{$q->getQueryId()}_update_bind_password;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_update_bind_username" => 'Tod',
            ":_{$q->getQueryId()}_update_bind_password" => 'abcdef',
        ], $q->getBinds());
    }

    public function testQueryInsertOnDuplicateKeyUpdate()
    {
        $q = new SqlQuery('INSERT');
        $q->setTable('users');
        $q->addValue('username', 'Tod');
        $q->addValue('password', 'abcdef');
        $q->addOnDupeValue('password', 'abcdef');
        $q->buildQuery();

        $sql = "INSERT INTO users SET username = :_{$q->getQueryId()}_update_bind_username, password = :_{$q->getQueryId()}_update_bind_password ON DUPLICATE KEY UPDATE password = :_{$q->getQueryId()}_dupe_update_bind_password;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_update_bind_username" => 'Tod',
            ":_{$q->getQueryId()}_update_bind_password" => 'abcdef',
            ":_{$q->getQueryId()}_dupe_update_bind_password" => 'abcdef',
        ], $q->getBinds());
    }

}