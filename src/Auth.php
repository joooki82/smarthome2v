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

    // public static function authenticate()
    // {
    //     if (!isset($_SERVER['PHP_AUTH_USER'])) {
    //         return ['authenticated' => false, 'message' => 'Hoopszi, username és jelszó szükséges'];
    //     } else {
    //         $username = $_SERVER['PHP_AUTH_USER'];
    //         $password = $_SERVER['PHP_AUTH_PW'];

    //         $user = self::$userController->getUserByUsername($username);

    //         if ($user && password_verify($password, $user['password'])) {
    //             return ['authenticated' => true, 'user' => $user]; // Assuming you want to return user details
    //         } else {
    //             return ['authenticated' => false, 'message' => 'Nem megfelelő, username és jelszó'];
    //         }
    //     }
    // }

    public function loginUser()
    {

        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->username) && isset($data->password)) {
            // Authenticate user
            $isAuthenticated = $this->authenticate();

            if ($isAuthenticated) {
                http_response_code(200);
                echo json_encode(["message" => "Login successful"]);
            } else {
                http_response_code(401);
                echo json_encode(["message" => "Login failed"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request"]);
        }
    }

}
