<?php

namespace Core\Users;

Class User
{

    /**
     * User constructor.
     * Jei sukurdami user objekta nepaduodam jam parametro,
     * tai visas $this->data propercio reiksmes nustato i null
     * Jei paduodam - iskviecia setData metoda
     * @param array $data
     */
    public function __construct($data = null)
    {
        if (!$data) {
            $this->data = [
                'email' => null,
                'full_name' => null,
                'password' => null
            ];
        } else {
            $this->setData($data);
        }
    }


    /**
     * Grazina user'io password'a
     * @return string
     */
    public function getPassword(): string
    {
        return $this->data['password'];
    }


    /**
     * Nustato user'iui password'a
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->data['password'] = $password;
    }

    /**
     * Nustato email i $this->data
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->data['email'] = $email;
    }

    /**
     * Nustato full_name i $this->data
     * @param string $full_name
     */
    public function setFullName(string $full_name)
    {
        $this->data['full_name'] = $full_name;
    }

    /**
     * Grazina user'io email
     * @return string
     */
    public function getEmail()
    {
        return $this->data['email'];
    }

    /**
     * Grazina userio full_name
     * @return string
     */
    public function getFullName()
    {
        return $this->data['full_name'];
    }

    public function setId($id)
    {
        $this->data['id'] = $id;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * Pagal paduotas $data reiksmes nusetina $this->data reiksmes
     * Jei reiksme nepaduota, nustato null
     * Daugiau yra abstrakcioj klasej
     * @param array $data
     */

    public function setData(array $data)
    {
        $this->setEmail($data['email'] ?? '');
        $this->setFullName($data['full_name'] ?? '');
        $this->setPassword($data['password'] ?? '');
    }

    /**
     * Grazina array su user'iui nustatytomis reiksmemis
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}
