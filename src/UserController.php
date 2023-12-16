<?php
class UserController
{
    private $db;
    private $user;

    public function __construct($db)
    {
        $this->db = $db;
        $this->user = new User($db); // Assuming you have a User class similar to Device
    }

    public function getUserByUsername($username)
    {
        $result = $this->user->readByUsername($username); // You'll need to implement readByUsername in User class

        if ($result) {
            echo json_encode($result->fetch(PDO::FETCH_ASSOC));
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
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
