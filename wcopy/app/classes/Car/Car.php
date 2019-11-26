<?php

namespace App\Products;


class Car
{
    private $data;


    const FUEL_TYPE_DIESEL = 'D';
    const FUEL_TYPE_PETROL = 'P';
    const FUEL_TYPE_ELECTRICITY = 'E';

    public function __construct($data = null)
    {
        if ($data === null) {
            $this->data = [
                'brand' => null,
                'model' => null,
                'year' => null,
                'image' => null,
                'fuel_type' => null,
                'doors' => null,

            ];

        } else {
            $this->setData($data);

        }
    }

    public function setBrand($brand)
    {

        $this->data['brand'] = $brand;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->data['brand'];
    }

    public function getModel()
    {
        return $this->data['model'];
    }

    /**
     * @param string $model
     */
    public function setModel(string $model)
    {
        $this->data['model'] = $model;
    }

    public function setYear($year)
    {

        $this->data['year'] = $year;
    }

    public function getYear()
    {
        return $this->data['year'];
    }

    public function setImage($image)
    {
        $this->data['image'] = $image;

    }

    public function getImage()
    {
        return $this->data['image'];
    }

    public function setFuelType($fuel_type)
    {
        $this->data['fuel_type'] = $fuel_type;

    }

    public function getFuelType()
    {
        return $this->data['fuel_type'];
    }

    public function setDoors($doors)
    {
        $this->data['doors'] = $doors;

    }

    public function geDoors()
    {
        return $this->data['doors'];
    }


    public function getId()
    {
        return $this->data['id'];
    }

    public function setId($id)
    {
        $this->data['id'] = $id;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->setBrand($data['brand']);
        $this->setModel($data['model']);
        $this->setYear($data['year']);
        $this->setImage($data['image']);
        $this->setFuelType($data['fuel_type']);
        $this->setDoors($data['doors']);

    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}