<?php

namespace Core\Database;

use PDO;

class Conection
{

    /** @var $host Defines the MySQL server IP.
     * Usually is 'localhost', if server is running locally */
    protected $host;

    /** @var $pass MySQL server password */
    protected $pass;

    /** @var $user MySQL server username. Usually is 'root' */
    protected $user;

    /**
     * Database Controller PDO instance
     * @var PDO
     */
    protected $pdo;

    /**
     * Connection constructor.
     * Konstruktorius iskviecia setCredentials funkcija, kuri nustato properties $host, $user ir $pass
     * @param $creds array, kuriame paduodam properciu $host, $user ir $pass reiksmes, keys turi but 'host', 'user', 'password'
     */
    public function __construct($creds)
    {
        $this->setCredentials($creds);
        $this->connect();
    }

    /**
     * Connect To Database
     * Patikrina ar $this->PDO nera null (jeigu ne null, vadinasi jau priconnectinom)
     * Jei ne, nustamom $this->pdo = new PDO ir paduodam jam i konstruktoriu $this->host", $this->user, $this->pass
     * Globali konstanta DEBUG nustato aplinka, jei true for developers, jei false for production
     *  Ši konstanta app/config/app.php turi būt true, jei norim matyti klaidas,
     * false, jei nenorim
     *
     * Jeigu globaline konstanta DEBUG yra nustatyta, executinti šias dvi eilutes
     *  $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     *  $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
     * Jeigu nepavyko sukurti PDO instancijos, throw'inti exceptioną panaudojant try, catch
     * @throws Exception
     */
    public function connect()
    {
        if (!$this->pdo) {
            try {
                $this->pdo = new \PDO
                ("mysql:host=$this->host", $this->user, $this->pass);

                // Ši konstanta app/config/app.php turi būt true, jei norim matyti klaidas
                // false, jei nenorim
                if (DEBUG) {
                    $this->pdo->setAttribute(
                        PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
                    );
                    $this->pdo->setAttribute(
                        PDO::ATTR_EMULATE_PREPARES, true
                    );
                }
            } catch (\PDOException $e) {
                throw new \PDOException('Could not connect to the database');
            }
        }
    }

    /**
     * Nustato $this->>PDO null
     */
    public function disconnect()
    {
        $this->pdo = null;
    }

    /**
     * Jei PDO nenustatytas iskviecia connect ir nustato PDO ir grazina ji
     * Jei nustatytas, tiesiog grazina PDO
     * @return PDO
     */
    public function getPDO()
    {
        if (!$this->pdo) {
            $this->connect();
        }

        return $this->pdo;
    }

    /**
     * Nustato $this->host
     * @param STRING $cred_host
     */
    public function setCredentialHost($cred_host)
    {
        $this->host = $cred_host;
    }

    /**
     * Grazina $this->host
     * @return STRING
     */
    public function getCredentialHost()
    {
        return $this->host;
    }

    /**
     * nustato $this->user
     * @param STRING $cred_user
     */
    public function setCredentialUser($cred_user)
    {
        $this->user = $cred_user;
    }

    /**
     * grazina $this->user
     * @return  STRING
     */
    public function getCredentialUser()
    {
        return $this->user;
    }

    /**
     * nustato $this->pass
     * @param STRING $cred_pass
     */
    public function setCredentialPass($cred_pass)
    {
        $this->pass = $cred_pass;
    }

    /**
     * grazina $this->pass
     * @return  STRING
     */
    public function getCredentialPass()
    {
        return $this->pass;
    }

    /**
     *  nustato properties $host, $user ir $pass
     * @param array $creds kuriame paduodam properciu $host, $user ir $pass reiksmes, keys turi but 'host', 'user', 'password'
     *
     */
    public function setCredentials($creds)
    {
        $this->setCredentialHost($creds['host']);
        $this->setCredentialUser($creds['user']);
        $this->setCredentialPass($creds['password']);
    }

}
