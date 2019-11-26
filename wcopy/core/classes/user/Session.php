<?php

namespace Core\User;

class Session extends \Core\User\Abstracts\Session
{
    /**
     * Session constructor.
     * nustato $this->repo lygu paduotam Repository objektui
     * Jei session neprasidejus iskviecia session_start()
     * Tada bando jungtis su cookie
     * Jei Cookie nesetintas, grazina -1, jei setintas iskviecia login metoda
     * @param Repository $repo
     */
    public function __construct(\Core\User\Repository $repo)
    {
        $this->repo = $repo;
        $this->is_logged_in = false;
        if (session_id() == '' || !isset($_SESSION)) {
            // session isn't started
            session_start();
        }
        $this->loginViaCookie();
    }

    /**
     * Grazina prisijungusio user'io objekta
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * grazina false jei neprisijunges ir true jei prisijunges
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->is_logged_in;
    }

    /**
     * Pagal pateiktu login parametrus sukuria user'io session cookie
     *  jei user'i rado, patikrina ar jame nustatytas password
     * sutampa su pateiktu login'e, tikrina ar user'is aktyvus,
     * jei true, tai iraso emaila ir passworda i $_SESSION
     * set'ina user i $this-> user ir nustato is_logged_in=true
     * returnina 1
     * jei user'is neaktyvus returnina -1
     * jei nesutapo passwordas- grazina -2
     * @param string $email
     * @param string $password
     * @return int
     */
    public function login($email, $password): int
    {
        //pagal pateikta email bando uzloadint userio objekta is duombazes
        $user = $this->repo->load($email);


        if ($user) {

            if ($user->getPassword() === $password) {
                if ($user->getIsActive()) {
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $this->user = $user;
                    $this->is_logged_in = true;

                    return self::LOGIN_SUCCESS;
                }

                return self::LOGIN_ERR_NOT_ACTIVE;
            }
        }

        return self::LOGIN_ERR_CREDENTIALS;
    }

    /**
     * Patikrina ar sukurti $_SESSION email ir password,
     * jei taip - iskviecia login funkcija,
     * jei ne - grazina -1
     * @return int
     */
    public function loginViaCookie()
    {
        if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
            return $this->login($_SESSION['email'], $_SESSION['password']);
        }

        return self::LOGIN_ERR_CREDENTIALS;
    }

    /**
     * $_SESSION istustina
     * is $_COOKIE istrina PHPSESSID
     * uzdaro session
     * is_logged_in= false
     * $this-> user=null
     */
    public function logout()
    {
        $_SESSION = [];
        setcookie(session_name(), "", time() - 3600);
        session_destroy();
        $this->is_logged_in = false;
        $this->user = null;
    }

}
