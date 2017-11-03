<?php


namespace TomWright\Database\QueryBuilder\Tests;


use PHPUnit\Framework\TestCase;
use TomWright\Database\QueryBuilder\Join;
use TomWright\Database\QueryBuilder\Like;
use TomWright\Database\QueryBuilder\SqlQuery;

class SelectTest extends TestCase
{

    public function testQuerySelect()
    {
        $sql = 'SELECT * FROM users;';

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->buildQuery();

        $this->assertEquals($sql, $q->getSql());
    }

    public function testQuerySelectWheres()
    {
        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->addWhere('user_id', 5);
        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE user_id = :_{$q->getQueryId()}_where_user_id;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_user_id" => 5,
        ], $q->getBinds());

        $q->addWhere('user_id', 5);
        $q->addWhere('username !=', 'Frank');
        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE user_id = :_{$q->getQueryId()}_where_user_id AND username != :_{$q->getQueryId()}_where_username;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_user_id" => 5,
            ":_{$q->getQueryId()}_where_username" => 'Frank',
        ], $q->getBinds());

        $q->addWhere('user_id', 5);
        $q->addWhere('username !=', 'Frank');
        $q->addRawWhere('(age <= :min_age OR age >= :max_age)', 'email_like');
        $q->addBind(':min_age', 18);
        $q->addBind(':max_age', 23);
        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE user_id = :_{$q->getQueryId()}_where_user_id AND username != :_{$q->getQueryId()}_where_username AND (age <= :min_age OR age >= :max_age);";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_user_id" => 5,
            ":_{$q->getQueryId()}_where_username" => 'Frank',
            ':min_age' => 18,
            ':max_age' => 23,
        ], $q->getBinds());

        $sql = 'SELECT * FROM users LIMIT 5 OFFSET 0;';

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->setPage(1, 5);
        $q->buildQuery();

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([], $q->getBinds());

        $sql = 'SELECT * FROM users LIMIT 5 OFFSET 10;';

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->setPage(3, 5);
        $q->buildQuery();

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([], $q->getBinds());
    }

    public function testQuerySelectJoins()
    {
        $sql = 'SELECT * FROM users JOIN user_groups ON users.user_id = user_groups.user_id;';

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->addJoin(new Join('JOIN', 'user_groups', 'users.user_id = user_groups.user_id'));
        $q->buildQuery();

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([], $q->getBinds());
    }

    public function testQuerySelectWhereSubQuery()
    {
        $subQ = new SqlQuery('SELECT');
        $subQ->setTable('deleted_users');
        $subQ->setFields(['user_id']);
        $subQ->addWhere('deleted_users.deleted', true);
        $subQ->addWhere('username !=', 'Jim');

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->addWhere('user_id IN (%SQL%)', $subQ);
        $q->addWhere('username !=', 'Tom');
        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE user_id IN (SELECT user_id FROM deleted_users WHERE deleted_users.deleted = :_{$subQ->getQueryId()}_where_deleted_users_deleted AND username != :_{$subQ->getQueryId()}_where_username;) AND username != :_{$q->getQueryId()}_where_username;";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$subQ->getQueryId()}_where_deleted_users_deleted" => 1,
            ":_{$subQ->getQueryId()}_where_username" => 'Jim',
            ":_{$q->getQueryId()}_where_username" => 'Tom',
        ], $q->getBinds());
    }

    public function testQueryWhereLike()
    {
        $like = new Like('contains', 'Tom');

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->addWhere('username', $like);

        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE (username LIKE :_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0);";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0" => '%Tom%',
        ], $q->getBinds());


        $like = new Like('starts_with', 'Tom');

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->addWhere('username', $like);

        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE (username LIKE :_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0);";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0" => 'Tom%',
        ], $q->getBinds());


        $like = new Like('ends_with', 'Tom');

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->addWhere('username', $like);

        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE (username LIKE :_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0);";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0" => '%Tom',
        ], $q->getBinds());


        $like = new Like('contains', ['Tom', 'Jim']);

        $q = new SqlQuery('SELECT');
        $q->setTable('users');
        $q->addWhere('username', $like);

        $q->buildQuery();

        $sql = "SELECT * FROM users WHERE (username LIKE :_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0 OR username LIKE :_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_1);";

        $this->assertEquals($sql, $q->getSql());
        $this->assertEquals([
            ":_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_0" => '%Tom%',
            ":_{$q->getQueryId()}_where_like_{$like->getLikeId()}_username_1" => '%Jim%',
        ], $q->getBinds());
    }

}