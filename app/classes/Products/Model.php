<?php

    namespace App\Products;

    class Model extends \Core\Database\Model{

        public function __construct()
        {
            parent::__construct('products_table', [
                [
                    'name' => 'id',
                    'type' => self::NUMBER_SHORT,
                    'flags' => [self::FLAG_NOT_NULL, self::FLAG_PRIMARY, self::FLAG_AUTO_INCREMENT]
                ],
                [
                    'name' => 'title',
                    'type' => self::TEXT_SHORT,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'color',
                    'type' => self::TEXT_SHORT,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'amount',
                    'type' => self::NUMBER_MED,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'image',
                    'type' => self::TEXT_MED,
                    'flags' => [self::FLAG_NOT_NULL]
                ],
                [
                    'name' => 'price',
                    'type' => self::TEXT_SHORT,
                ],

            ]);
        }
    }