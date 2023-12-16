<?php

class Auth
{
    private static $userController;

    public static function initialize($db)
    {
        self::$userController = new UserController($db);
    }

    public static function authenticate()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Hoopszi, username és jelszó szükséges';
            exit;
        } else {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

            $user = self::$userController->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                return true;
            } else {
                header('HTTP/1.0 401 Unauthorized');
                echo 'Nem megfelelő, username és jelszó';
                exit;
            }
        }
    }
}
