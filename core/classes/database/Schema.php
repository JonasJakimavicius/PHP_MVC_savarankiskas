<?php

namespace Core\Database;

use App\App;
use \Core\Database\SQLBuilder;

class Schema
{

    /** @var string Schema name */
    protected $name;


    /**
     * Schemos sukūrimas
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $this->init();
    }

    /**
     * Initializes Schema
     *
     * @return type
     */
    public function init()
    {
        try {
            $sql_check = strtr('SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA '
                . 'WHERE SCHEMA_NAME = @schema', [
                '@schema' => SQLBuilder::value($this->name)
            ]);
            $query = App::$connection->getPDO()->query($sql_check);

            // Check if schema exists. If we can query one column, it means yes
            if (!(bool)$query->fetchColumn()) {
                $this->create();
            }

            // USE `schema`. This SQL lets all subsequent requests specify table only
            $sql_use = strtr('USE @schema', [
                '@schema' => SQLBuilder::schema($this->name)
            ]);
            App::$connection->getPDO()->exec($sql_use);

        } catch (PDOException $e) {
            throw new Exception("Database Error: " . $e->getMessage());
        }
    }

    /**
     * Creates Schema
     *
     * @throws PDOException
     */
    public function create()
    {
        $create = strtr("CREATE DATABASE @schema_name", [
            '@schema_name' => SQLBuilder::column($this->name)
        ]);
        App::$connection->getPDO()->exec($create);

        $grant = strtr("GRANT ALL ON @schema_name.* TO @user@@host", [
            '@user' => SQLBuilder::value(App::$connection->getCredentialUser()),
            '@host' => SQLBuilder::value(App::$connection->getCredentialHost()),
            '@schema_name' => SQLBuilder::column($this->name)
        ]);

        App::$connection->getPDO()->exec($grant);

        $flush = 'FLUSH PRIVILEGES';
        App::$connection->getPDO()->exec($flush);
    }

}
