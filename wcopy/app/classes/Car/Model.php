<?php

    namespace App\Products;

    class Model extends \Core\Database\Model{

        public function __construct()
        {
            parent::__construct('cars_table', [
                [
                    'name' => 'id',
                    'type' => self::NUMBER_SHORT,
                    'flags' => [self::FLAG_NOT_NULL, self::FLAG_PRIMARY, self::FLAG_AUTO_INCREMENT]
                ],
                [
                    'name' => 'model',
                    'type' => self::TEXT_SHORT,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'brand',
                    'type' => self::TEXT_SHORT,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'year',
                    'type' => self::NUMBER_MED,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'image',
                    'type' => self::TEXT_MED,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'fuel_type',
                    'type' => self::TEXT_SHORT,
                ],
                [
                    'name' => 'doors',
                    'type' => self::NUMBER_SHORT,
                ],

            ]);
        }
    }