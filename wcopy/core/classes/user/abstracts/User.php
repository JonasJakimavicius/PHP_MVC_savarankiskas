<?php

namespace Core\User\Abstracts;

abstract class User {

    protected $data;

    const ORIENTATION_GAY = 'g';
    const ORIENTATION_STRAIGHT = 's';
    const ORIENTATION_BISEXUAL = 'b';
    
    const GENDER_MALE = 'm';
    const GENDER_FEMALE = 'f';

    /**
     * User constructor.
     * Ziuret Core\User
     * @param null $data
     */
    public function __construct($data = null) {
        if (!$data) {
            $this->data = [
                'email' => null,
                'full_name' => null,
                'age' => null,
                'gender' => null,
                'orientation' => null,
                'photo' => null,
            ];
        } else {
            $this->setData($data);
        }
    }

    abstract public function getIsActive(): bool;

    abstract public function setIsActive(bool $active);

    abstract public function getAccountType(): int;

    abstract public function setAccountType(int $type);

    abstract public function getPassword(): string;

    abstract public function setPassword(string $password);

    /**
     * Nustato email i $this->data
     * @param string $email
     */
    public function setEmail(string $email) {
        $this->data['email'] = $email;
    }

    /**
     * Nustato full_name i $this->data
     * @param string $full_name
     */
    public function setFullName(string $full_name) {
        $this->data['full_name'] = $full_name;
    }

    /** Nustato age i $this->data
     * @param int $age
     */
    public function setAge(int $age) {
        $this->data['age'] = $age;
    }

    /**
     * Patikrina ar pateikta lyties sutrumpinimas yra tarp galimu ir
     * nustato gender $this->data
     * @param string $gender
     * @return bool
     */
    public function setGender(string $gender) {
        if (in_array($gender, [$this::GENDER_MALE, $this::GENDER_FEMALE])) {
            $this->data['gender'] = $gender;

            return true;
        }
    }

    /**
     *  Patikrina ar pateikta orientacijos sutrumpinimas yra tarp galimu ir
     * nustato orientation i $this->data
     * @param string $orientation
     * @return bool
     */
    public function setOrientation(string $orientation) {
        if (in_array($orientation, [
                    $this::ORIENTATION_STRAIGHT,
                    $this::ORIENTATION_GAY,
                    $this::ORIENTATION_BISEXUAL])) {
            $this->data['orientation'] = $orientation;

            return true;
        }
    }

    /**
     * Nustato photo i $this->data
     * @param string $photo
     */
    public function setPhoto(string $photo) {
        $this->data['photo'] = $photo;
    }

    /**
     * Grazina user'io email
     * @return string
     */
    public function getEmail() {
        return $this->data['email'];
    }

    /**
     * Grazina userio full_name
     * @return string
     */
    public function getFullName() {
        return $this->data['full_name'];
    }

    /**
     * Grazina user'io age
     * @return int
     */
    public function getAge() {
        return $this->data['age'];
    }

    /**
     * Grazina userio gender
     * @return string
     */
    public function getGender() {
        return $this->data['gender'];
    }

    /**
     * Grazina userio orientation
     * @return string
     */
    public function getOrientation() {
        return $this->data['orientation'];
    }

    /**
     * Grazina array su galimomis gender
     * @return array
     */
    public static function getGenderOptions() {
        return [
            self::GENDER_FEMALE => 'Female',
            self::GENDER_MALE => 'Male'
        ];
    }

    /**
     *  Grazina array su galimomis orientation
     * @return array
     */
    public static function getOrientationOptions() {
        return [
            self::ORIENTATION_GAY => 'Gay',
            self::ORIENTATION_STRAIGHT => 'Straight',
            self::ORIENTATION_BISEXUAL => 'Bisexual'
        ];
    }

    /**
     * Grazina user'io photo
     * @return string
     */
    public function getPhoto() {
        return $this->data['photo'];
    }

    /**
     * Pagal paduotas $data reiksmes nusetina $this->data reiksmes
     * Jei reiksme nepaduota, nustato null
     * @param array $data
     */
    public function setData(array $data) {
        $this->setEmail($data['email'] ?? '');
        $this->setFullName($data['full_name'] ?? '');
        $this->setAge($data['age'] ?? null);
        $this->setGender($data['gender'] ?? '');
        $this->setOrientation($data['orientation'] ?? '');
        $this->setPhoto($data['photo'] ?? '');
    }

    /**
     * Grazina array su user'iui nustatytomis reiksmemis
     * @return array
     */
    public function getData() {
        return $this->data;
    }

}
