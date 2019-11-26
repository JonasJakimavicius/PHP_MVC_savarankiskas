<?php

namespace App\Products;

use App\Products\Car;

use Core\User\User;

class Repository
{

    protected $model;

    public function __construct()
    {
        $this->model = new \App\Products\Model;
    }

    public function exists(Car $car)
    {
        if ($this->model->load($car->getData())) {
            return true;
        }
        return false;
    }


    /**
     * Inserts user to database if it does not exist
     * @param Car $car
     * @return mixed jei irase - negrazins nieko, jei neirase grazins false
     */
    public function insert(Car $car)
    {

        return $this->model->insertIfNotExists(
            $car->getData(), ['brand', 'model', 'year', 'image', 'fuel_type']);
    }

    /**
     * @param $key
     * @param $value
     * @return \App\Products\Car
     */
    public function load($key, $value)
    {
        $rows = $this->model->load([
            $key => $value
        ]);

        foreach ($rows as $row) {
            $car = new  Car($row);
            $car->setId($row['id']);
            return $car;
        }
    }

    /**
     * @return array
     */
    public function loadAll()
    {
        $rows = $this->model->load();
        $cars = [];

        foreach ($rows as $row) {
            $car = new \App\Products\Car($row);
            $car->setId($row['id']);
            $cars[] = $car;
        }

        return $cars;
    }

    /**
     * Updates user in database based on its id
     * @param Car $car
     * @return boolean true jei irase, false jei ne
     */
    public function update(Car $car)
    {
        return $this->model->update($car->getData(), [
            'id' => $car->getId()
        ]);
    }

    /**
     * Deletes user from database based on its email
     * @param Car $car
     * @return boolean true jei irase, false jei ne
     */
    public function delete(Car $car)
    {
        return $this->model->delete([
            'id' => $car->getId()
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