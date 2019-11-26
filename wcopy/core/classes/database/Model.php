<?php

namespace Core\Database;

use App\App;

class Model
{


    /** @var $fields array Database table field array
     * PVZ:
     * $fields = [
     *              [
     *                  'name' => 'my_column',
     *                  'type' => Model::TEXT_SHORT,
     *                  'flags' => [Model::FLAG_NOT_NULL]
     *              ],
     *              [
     *                  'name' => 'my_other_column',
     *                  'type' => Model::NUMBER_SHORT,
     *                  'flags' => [FLAG_NOT_NULL, FLAG_AUTO_INCREMENT]
     *              ],
     *           ];
     *
     */

    protected $fields;
    protected $table_name;

    /*
     * Constants for column types
     */
    const NUMBER_SHORT = 'TINYINT';
    const NUMBER_MED = 'INT';
    const NUMBER_LONG = 'BIGINT';
    const NUMBER_FLOAT = 'FLOAT';
    const NUMBER_DOUBLE = 'DOUBLE';

    const TEXT_SHORT = 'VARCHAR(249)';
    const TEXT_MED = 'MEDIUMTEXT';
    const TEXT_LONG = 'LONGTEXT';

    /**
     * SYMBOL is a single char
     */
    const CHAR = 'CHAR';

    /**
     * DATETIME is stored in format Y-m-d H:i:s
     */
    const DATETIME = 'DATETIME';
    const DATETIME_AUTO_ON_CREATE = 'DATETIME DEFAULT CURRENT_TIMESTAMP';
    const DATETIME_AUTO_ON_UPDATE = 'DATETIME DEFAULT CURRENT_TIMESTAMPT ON UPDATE CURRENT_TIMESTAMP';

    /**
     * TIMESTAMP values are converted from the current time zone to UTC for storage, and converted back from UTC to the current time zone for retrieval.
     * Careful for 2038!
     */
    const TIMESTAMP = 'TIMESTAMP';

    /** @var TIMESTAMP_AUTO_ON_CREATE Field type TIMESTAMP with automatic value on row creation */
    const TIMESTAMP_AUTO_ON_CREATE = 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP';

    /** @var TIMESTAMP_AUTO_ON_UPDATE Field type TIMESTAMP with automatic value on row creation/updating */
    const TIMESTAMP_AUTO_ON_UPDATE = 'TIMESTAMP DEFAULT CURRENT_TIMESTAMPT ON UPDATE CURRENT_TIMESTAMP';

    /**
     * Flags for each table column
     */
    const FLAG_PRIMARY = 'PRIMARY KEY'; // Column is the "unique ID" column
    const FLAG_AUTO_INCREMENT = 'AUTO_INCREMENT'; // Column value will auto increment by 1
    const FLAG_NOT_NULL = 'NOT NULL'; // Column cannot be empty

    /*
     * Table initialisation
     * 
     * @param \Connection $c
     * @param string $table_name
     * @param array $fields = [
     *              [
     *                  'name' => 'my_column',
     *                  'type' => Model::TEXT_SHORT,
     *                  'flags' => [Model::FLAG_NOT_NULL]
     *              ],
     *              [
     *                  'name' => 'my_other_column',
     *                  'type' => Model::NUMBER_SHORT,
     *                  'flags' => [FLAG_NOT_NULL, FLAG_AUTO_INCREMENT]
     *              ],
     *           ];
     * 
     */
    public function __construct($table_name, $fields)
    {
        $this->table_name = $table_name;
        $this->fields = $fields;

        $this->init();
    }

    /**
     * Initializes table
     * If table does not exist, it creates one
     */
    public function init()
    {
        $sql = 'SHOW TABLES LIKE ' . SQLBuilder::value($this->table_name);

        $results = App::$connection->getPDO()->query($sql);
        if ($results->rowCount() == 0) {
            $this->create();
        }
    }

    /**
     * Builds SQL and creates table table from array ($this->fields)
     *
     * $this->fields = [
     *              [
     *                  'name' => 'my_column',
     *                  'type' => Model::TEXT_SHORT,
     *                  'flags' => [Model::FLAG_NOT_NULL]
     *              ],
     *              [
     *                  'name' => 'my_other_column',
     *                  'type' => Model::NUMBER_SHORT,
     *                  'flags' => [FLAG_NOT_NULL, FLAG_AUTO_INCREMENT]
     *              ],
     *           ];
     */
    public function create()
    {
        $SQL_columns = [];

        foreach ($this->fields as $field) {
            $SQL_columns[] = strtr('@col_name @col_type @col_flags', [
                '@col_name' => SQLBuilder::column($field['name']),
                '@col_type' => $field['type'],
                '@col_flags' => isset($field['flags']) ? implode(' ', $field['flags']) : ''
            ]);
        }

        $sql = strtr('CREATE TABLE @table_name (@columns);', [
            '@table_name' => SQLBuilder::table($this->table_name),
            '@columns' => implode(', ', $SQL_columns)
        ]);

        try {
            return App::$connection->getPDO()->exec($sql);
        } catch (PDOException $e) {
            throw new Exception(
                strtr('Framework database error: Failed to create table: @e',
                    ['@e' => $e->getMessage()
                    ])
            );
        }
    }

    /**
     * Inserts $row (array of values) into the table
     *
     * Array index represents column name.
     * Array value represents that column value.
     *
     * $row = [
     *          'column_1' => 'Shake dat ass',
     *          'column_2' => 3,
     *        ];
     *
     * Returns last insert id or throws exception
     * return $this->pdo->lastInsertId();
     *
     * @throws Exception
     */
    public function insert($row)
    {
        $row_columns = array_keys($row);
        $sql = strtr("INSERT INTO @table (@col) VALUES (@val)", [
            '@table' => SQLBuilder::table($this->table_name),
            '@col' => SQLBuilder::columns($row_columns),
            '@val' => SQLBuilder::binds($row_columns)
        ]);
        $query = App::$connection->getPDO()->prepare($sql);

        foreach ($row as $key => $value) {
            $query->bindValue(SQLBuilder::bind($key), $value);
        }

        try {
            $query->execute();

            return App::$connection->getPDO()->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception(
                strtr('Framework database error: Failed to insert to table: @e',
                    ['@e' => $e->getMessage()
                    ])
            );
        }
    }

    /**
     * Inserts $row (array of values) into the table
     * if row does not exist already
     *
     * @param $row array Row to insert
     * @param $unique_columns array of indexes of unique columns
     * @return string jei nera tokiu reiksmiu, kaip paduodamos $row, tai iskvies $this->insert metoda, jei yra, grazina false
     * @throws Exception
     */
    public function insertIfNotExists($row, $unique_columns)
    {
        $load_conditions = [];
        // sukisa $row paduotas values i $load_conditions array su keys lygiais $unique_columns
        foreach ($unique_columns as $column) {

            $load_conditions[$column] = $row[$column];
        }

        // pabando uzkrauti duomenis pagal conditions paduotomis $load_conditions array
        if (!$this->load($load_conditions)) {

            return $this->insert($row);
        }

        return false;
    }

    /**
     * Load from table
     *
     * @param array $conditions
     * @param array $order_by
     * @param int $offset kintamasis, kuris nurodo, kiek eiluciu praleist nuo pradzios
     * @param int $limit nurodo kiek eiluciu nuo virsaus rodyt
     * @return array
     */
    public function load($conditions = [], $order_by = [], $offset = 0, $limit = 0)
    {
        $sql = [];

        if ($conditions) {
            //jei paduotas $conditions parametras i $sql array index 'where' iraso SQL'ini stringa,
            // kuriame WHERE column 1`=:column_1  AND `column 2`=:column_2 AND ,...,

            $sql['where'] = 'WHERE ' . SQLBuilder::columnsEqualBinds(array_keys($conditions), ' AND ');
        }

        if ($order_by) {
            // jei paduotas $order_by parametras i $sql array index'u order_by iraso ORDER BY
            //$order_by parametras turi buti array
            //kuriame index yra column pavadinimas, o value ASC arba DESC
            //DESC - reiskia, kad table issortins mazejancia tvarka
            //ASC- reiskia, kad table issortins didejancia tvarka
            // value (DESC arba ASC) nebutina paduot
            //ASC yra defaultine reiksme
            // galima sortint ir pagal kelis collumn
            //pvz issortins nuo abeceles galo valstybes, o imones tose valstybese pagal abecele
            //!!!!!!!
            //Taisiau koda, buvo:
            //  $sql['order_by'][] = SQLBuilder::column($column) . ' ' . $direction;
            //!!!!!!!!!!

            $sql['order_by'] = 'ORDER BY ';

            foreach ($order_by as $column => $direction) {
                $sql['order_by'] .= SQLBuilder::column($column) . ' ' . $direction;
            }

        }

        if ($offset) {
            // jei paduotas $offset parametras tai i $sql array index 'OFFSET' irasys SQL komanda
            //OFFSET $offset
            // $offset turi but integer,kuris nurodys, kiek eiluciu praleist  nuo virsaus

            $sql['offset'] = 'OFFSET ' . $offset;
        }

        if ($limit) {
            //jei paduotas parametras $limit, tai $sql array index 'limit irasys SQL komanda
            //LIMIT $LIMIT $limit
            //$limit turi but integer, kuris nurodo kiek eiluciu nuo virsaus rodyt
            $sql['limit'] = 'LIMIT ' . $limit;
        }

        // i $sql['exec'] iraso SQL stringa su table pavadinimu ir
        // auksciau irasytom WHERE, ORDER BY, OFFSET ir LIMIT SQL komandu  stringinem komandom
        //jei kazkuri nebus nurodyta, ides tuscia stringa
        $sql['exec'] = strtr('SELECT * FROM @table @where @order_by @offset @limit;', [
            '@table' => $this->table_name,
            '@where' => $sql['where'] ?? '',
            '@order_by' => $sql['order_by'] ?? '',
            '@offset' => $sql['offset'] ?? '',
            '@limit' => $sql['limit'] ?? ''
        ]);


        //ziuret PDO dokumentacija (CMD+click)
        $query = App::$connection->getPDO()->prepare($sql['exec']);


        foreach ($conditions as $column => $value) {
            //ziuret PDO dokumentacija (CMD+click)
            $query->bindValue(SQLBuilder::bind($column), $value);
        }
        //ziuret PDO dokumentacija (CMD+click)
        $query->execute();

        //ziuret PDO dokumentacija (CMD+click)
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Updates $row columns based on conditions
     *
     * Array index represents column name.
     * Array value represents that column (updated) value.
     *
     * $row = [
     *          'full_name' => 'Wicked Mthfucka',
     *          'photo' => 'https://i.ytimg.com/vi/uVxSZnJv2gs/maxresdefault.jpg,
     *        ];
     *
     * $conditions = [
     *          'email' => 'lolz@gmail.com
     *          ];
     *
     * Conditions represent WHERE statements, combined with AND
     *
     * @param array $row <p> paduoda array, kuriame nurodyta,
     * kokiuose stulpeliuose i kokias reiksmes pakeist
     * (index -column name, value- reiksme i kuria pakeist)
     * </p>
     * @param array $conditions <P>
     * paduoda array, kuriam nurodyta pagal kokia salyga pakeist(WHERE)
     * (index -column name, value- reiksme, kurios ieskom) </p>
     * @return boolean True jei updatino,false jei ne. Jei ne, throws exception
     * @throws Exception
     */
    public function update($row = [], $conditions = [])
    {
        $row_columns = array_keys($row);
        $cond_columns = array_keys($conditions);

        if ($conditions) {
            ///jei paduotas $conditions parametras i $sql variable iraso SQL'ini stringa,
            // kuriame UPDATE `table_name` SET  column 1`=:column_1, `column 2`=:column_2, etc...
            // WHERE column 1`=:column_1   AND `column 2`=:column_2 AND ,...,

            $sql = strtr("UPDATE @table SET @col WHERE @condition", [
                '@table' => SQLBuilder::table($this->table_name),
                '@col' => SQLBuilder::columnsEqualBinds($row_columns),
                '@condition' => SQLBuilder::columnsEqualBinds($cond_columns, ' AND ', 'c_'),
            ]);
        } else {

            //jei nepaduotas $conditions parametras i $sql variable iraso SQL'ini stringa,
            // kuriame UPDATE table_name SET  column 1`=:column_1, `column 2`=:column_2, etc..

            $sql = strtr("UPDATE @table SET @col", [
                '@table' => SQLBuilder::table($this->table_name),
                '@col' => SQLBuilder::columnsEqualBinds($row_columns)
            ]);
        }

        $query = App::$connection->getPDO()->prepare($sql);

        foreach ($row as $row_key => $row_value) {
            $query->bindValue(SQLBuilder::bind($row_key), $row_value);
        }

        foreach ($conditions as $condition_idx => $condition) {
            $query->bindValue(SQLBuilder::bind($condition_idx, 'c_'), $condition);
        }

        try {
            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception(
                strtr('Framework database error: Failed to update table: @e',
                    ['@e' => $e->getMessage()
                    ])
            );
        }
    }

    /**
     * Deletes row based on conditions
     * @param array $conditions
     *  paduoda array, kuriam nurodyta pagal kokia salyga istrinti (WHERE)
     * (index -column name, value- reiksme, kurios ieskom)
     * Jei $conditions array tuscias- istrina viska</p>
     * @return boolean
     * @throws Exception
     */
    public function delete($conditions = [])
    {
        if ($conditions) {
            $cond_columns = array_keys($conditions);

            ///jei paduotas $conditions parametras i $sql variable iraso SQL'ini stringa,
            // kuriame DELETE `table_name` WHERE  column 1`=:column_1 AND `column 2`=:column_2, etc...
            $sql = strtr("DELETE FROM @table WHERE @condition", [
                '@table' => SQLBuilder::table($this->table_name),
                '@condition' => SQLBuilder::columnsEqualBinds($cond_columns, ' AND '),
            ]);
        } else {
            ///jei nepaduotas $conditions parametras i $sql variable iraso SQL'ini stringa,
            // kuriame DELETE `table_name`
            $sql = strtr("DELETE FROM @table", [
                '@table' => SQLBuilder::table($this->table_name),
            ]);
        }

        $query = App::$connection->getPDO()->prepare($sql);

        foreach ($conditions as $condition_idx => $condition) {
            $query->bindValue(SQLBuilder::bind($condition_idx), $condition);
        }

        try {
            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception(
                strtr('Framework database error: Failed to delete from table: @e',
                    ['@e' => $e->getMessage()
                    ])
            );
        }
    }

}
