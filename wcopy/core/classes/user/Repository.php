<?php

namespace Core\User;

class Repository extends \Core\User\Abstracts\Repository {

    protected $model;

    public function __construct() {
        $this->model = new \Core\User\Model();
    }

    /**
     * Patikrinam are user'is su tokiu email'u egzistuoja
     * Jeigu ne, tada įtraukiam jį į duombazę ir
     * returniname REGISTER_SUCCESS
     * Jeigu egzistuoja, returniname REGISTER_ERR_EXISTS
     */
    public function register(\Core\User\User $user) {
        if (!$this->exists($user)) {
            $this->insert($user);

            return self::REGISTER_SUCCESS;
        }

        return self::REGISTER_ERR_EXISTS;
    }
    /**
     * Inserts user to database if it does not exist
     * @param User $user
     * @return mixed jei irase - negrazins nieko, jei neirase grazins false
     */
    public function insert(\Core\User\User $user) {

        return $this->model->insertIfNotExists(
                $user->getData(), ['email']
        );
    }

    /**
     * Loads user via $email
     * @param string $email
     * @return \Core\User\User
     */
    public function load($email) {
       $rows = $this->model->load([
           'email' => $email 
        ]);
       
        foreach ($rows as $row) {
            return new \Core\User\User($row);  
        }
    }


    /**
     * Loads all users
     *
     * @return array grazina array su  \Core\User\User objektais
     */
    public function loadAll() {
       $rows = $this->model->load();
       $users = [];
       
        foreach ($rows as $row) {
            $users[] = new \Core\User\User($row);  
        }
        
        return $users;
    }

    /**
     * Updates user in database based on its email]
     * @return boolean true jei irase, false jei ne
     */
    public function update(\Core\User\User $user) {
        return $this->model->update($user->getData(), [
            'email' => $user->getEmail()
        ]);
    }

    /**
     * Deletes user from database based on its email
     * @return boolean true jei irase, false jei ne
     */
    public function delete(\Core\User\User $user) {
        return $this->model->delete([
            'email' => $user->getEmail()
        ]);
    }

    /**
     * Deletes all users from database
     */
    public function deleteAll() {
        return $this->model->delete();
    }
}
