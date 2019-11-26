<?php

namespace App\Products;


class Products
{
    private $data;



    public function __construct($data = null)
    {
        if ($data === null) {
            $this->data = [
                'title' => null,
                'color' => null,
                'amount' => null,
                'price' => null,
                'image'=>null,

            ];

        } else {
            $this->setData($data);

        }
    }

    public function setTitle($title)
    {

        $this->data['title'] = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->data['title'];
    }

    public function getColor()
    {
        return $this->data['color'];
    }

    /**
     * @param string $model
     */
    public function setColor(string $color)
    {
        $this->data['color'] = $color;
    }

    public function setAmount($amount)
    {

        $this->data['amount'] = $amount;
    }

    public function getAmount()
    {
        return $this->data['amount'];
    }

    public function setImage($image)
    {
        $this->data['image'] = $image;

    }

    public function getImage()
    {
        return $this->data['image'];
    }

    public function setPrice($price)
    {
        $this->data['price'] = $price;

    }

    public function getPrice()
    {
        return $this->data['price'];
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
        $this->setTitle($data['title']);
        $this->setAmount($data['amount']);
        $this->setImage($data['image']);
        $this->setPrice($data['price']);
        $this->setColor($data['color']);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}