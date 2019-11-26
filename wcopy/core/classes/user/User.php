<?php

namespace Core\User;

Class User extends Abstracts\User
{
    //!!!!!!
    //Daugiau metodu yra abstrakcioj klasej
    //!!!!!!

    const ACCOUNT_TYPE_USER = 1;
    const ACCOUNT_TYPE_ADMIN = 0;

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
                'age' => null,
                'gender' => null,
                'orientation' => null,
                'photo' => null,
                'account_type' => null,
                'is_active' => null,
                'password' => null
            ];
        } else {
            $this->setData($data);
        }
    }

    /**
     * Grazina koks yra account'o tipas
     * jei useris 1, jei adminas 0
     * @return int
     */
    public function getAccountType(): int
    {
        return $this->data['account_type'];
    }

    /**
     * Grazina true arba false ar vartotojas aktyvus
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->data['is_active'];
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
     * Patikrina ar paduotam $type yra priskirtas account'o tipas
     * Jei taip, tai ji uzsetina user'iui ir grazina true
     * @param int $type
     * @return bool
     */
    public function setAccountType(int $type)
    {
        if (in_array($type, [self::ACCOUNT_TYPE_ADMIN, self::ACCOUNT_TYPE_USER])) {
            $this->data['account_type'] = $type;

            return true;
        }
    }

    /**
     * Grazina array is galimu account'o tipo
     * @return array
     */
    public static function getAccountTypeOptions()
    {
        return [
            self::ACCOUNT_TYPE_USER => 'User',
            self::ACCOUNT_TYPE_ADMIN => 'Admin'
        ];
    }

    /**
     * Nustato ar vartotojas yra active ar ne
     * @param bool $active
     */
    public function setIsActive(bool $active)
    {
        $this->data['is_active'] = $active;
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
     * Pagal paduotas $data reiksmes nusetina $this->data reiksmes
     * Jei reiksme nepaduota, nustato null
     * Daugiau yra abstrakcioj klasej
     * @param array $data
     */
    public function setData(array $data)
    {
        parent::setData($data);
        $this->setIsActive($data['is_active'] ?? true);
        $this->setAccountType($data['account_type'] ?? 1);
        $this->setPassword($data['password'] ?? '');
    }

}
