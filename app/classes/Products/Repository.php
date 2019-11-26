<?php

namespace App\Products;

use App\Products\Products;



class Repository
{

    protected $model;

    public function __construct()
    {
        $this->model = new \App\Products\Model;
    }

    public function exists(Products $product)
    {
        if ($this->model->load($product->getData())) {
            return true;
        }
        return false;
    }


    /**
     * Inserts user to database if it does not exist
     * @param product $product
     * @return mixed jei irase - negrazins nieko, jei neirase grazins false
     */
    public function insert(Products $product)
    {

        return $this->model->insertIfNotExists(
            $product->getData(), ['title']);
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    public function load($array = [])
    {
        $rows = $this->model->load($array);
        $products = [];

        foreach ($rows as $row) {

            $product = new  Products($row);
            $product->setId($row['id']);
            $products[] = $product;
        }
        return $products;
    }

    /**
     * @return array
     */
    public function loadAll()
    {
        $rows = $this->model->load();
        $products = [];

        foreach ($rows as $row) {
            $product = new \App\Products\products($row);
            $product->setId($row['id']);
            $products[] = $product;
        }

        return $products;
    }

    /**
     * Updates user in database based on its id
     * @param product $product
     * @return boolean true jei irase, false jei ne
     */
    public function update(products $product)
    {
        return $this->model->update($product->getData(), [
            'id' => $product->getId()
        ]);
    }

    /**
     * Deletes user from database based on its email
     * @param products $product
     * @return boolean true jei irase, false jei ne
     */
    public function delete(products $product)
    {
        return $this->model->delete([
            'id' => $product->getId()
        ]);
    }

    /**
     * Deletes all users from database
     */
    public function deleteAll()
    {
        return $this->model->delete();
    }


}