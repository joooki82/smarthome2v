<?php
class UserController
{
    private $db;
    private $user;

    public function __construct($db)
    {
        $this->db = $db;
        $this->user = new User($db);
    }

    public function getUserByUsername($username)
    {
        try {
            $result = $this->user->readByUsername($username);

            if ($result) {
                return $result->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public function createUser()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['username']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Incomplete data provided']);
            return;
        }

        try {
            if ($this->user->create($data)) {
                http_response_code(201);
                echo json_encode(['message' => 'User created']);
            } else {
                throw new Exception('User could not be created');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

}
