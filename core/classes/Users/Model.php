<?php

namespace Core\Users;

class Model extends \Core\Database\Model {

    public function __construct() {

        parent::__construct('users_table', [
            [
                'name' => 'email',
                'type' => self::TEXT_SHORT,
                'flags' => [self::FLAG_NOT_NULL, self::FLAG_PRIMARY]
            ],
            [
                'name' => 'password',
                'type' => self::TEXT_SHORT,
                'flags' => [self::FLAG_NOT_NULL]
            ],
            [
                'name' => 'full_name',
                'type' => self::TEXT_SHORT,
                'flags' => [self::FLAG_NOT_NULL]
            ],
        ]);
    }

}
