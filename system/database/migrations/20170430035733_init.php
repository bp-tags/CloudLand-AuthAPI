<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Init extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $users = $this->table("users");
        $users->addColumn("email", "string");
        $users->addColumn("uuid", "string");
        $users->addColumn("username", "string");
        $users->addColumn("password", "string");
        $users->addColumn("create_time", "biginteger");
        $users->addColumn("update_time", "biginteger");
        $users->create();

        $tokens = $this->table("tokens");
        $tokens->addColumn("clientId", "string");
        $tokens->addColumn("clientKey", "string");
        $tokens->addColumn("userId", "biginteger");
        $tokens->addColumn("create_time", "biginteger");
        $tokens->addColumn("update_time", "biginteger");
        $tokens->create();

        $joins = $this->table("joins");
        $joins->addColumn("clientKey", "string");
        $joins->addColumn("uuid", "string"); // player's uuid, copied here
        $joins->addColumn("userId", "biginteger"); // player's internal id, copied here
        $joins->addColumn("random", "string"); // sending from server
        $joins->addColumn("create_time", "biginteger");
    }
}
