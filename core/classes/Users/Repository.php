<?php

namespace Core\Users;

use Core\Users\Model;
use Core\Users\User;

class Repository
{

    protected $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function exists(user $user)
    {
        if ($this->model->load($user->getData())) {
            return true;
        }
        return false;
    }


    /**
     * Inserts user to database if email does not exist
     * @param User $user
     * @return mixed jei irase - negrazins nieko, jei neirase grazins false
     */
    public function insert(User $user)
    {

        return $this->model->insertIfNotExists(
            $user->getData(), ['email']);
    }

    /**
     * @param array $array
     * @return User
     */
    public function load($array = [])
    {
        $rows = $this->model->load($array);
        $users = [];

        foreach ($rows as $row) {

            $user = new  user($row);

            $users[] = $user;
        }
        return $users;
    }

    /**
     * @return array
     */
    public function loadAll()
    {
        $rows = $this->model->load();
        $users = [];

        foreach ($rows as $row) {
            $user = new \Core\Users\User($row);
//            $user->setId($row['id']);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Updates user in database based on its id
     * @param user $user
     * @return boolean true jei irase, false jei ne
     */
    public function update(user $user)
    {
        return $this->model->update($user->getData(), [
            'id' => $user->getId()
        ]);
    }

    /**
     * Deletes user from database based on its email
     * @param user $user
     * @return boolean true jei irase, false jei ne
     */
    public function delete(user $user)
    {
        return $this->model->delete([
            'id' => $user->getId()
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