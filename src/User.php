<?php
class User
{
    private $conn;
    private $table = 'users';


    public $user_id;
    public $username;
    public $password;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readByUsername($username)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE username = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $username);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error reading user: ' . $e->getMessage()]);
            exit;
        }
    }
    public function create($data)
    {
        try {
            $query = "INSERT INTO " . $this->table . " (username, password) VALUES (:username, :password)";

            $stmt = $this->conn->prepare($query);

            $username = htmlspecialchars(strip_tags($data['username']));
            $password = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception('User could not be created.');
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
            exit;
        }
    }

}
