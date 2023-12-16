<?php
class Device
{
    private $conn;
    private $table = 'devices';

    public $device_id;
    public $device_type;
    public $device_name;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE device_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error reading device: ' . $e->getMessage()]);
            exit;
        }
    }

    public function readAll()
    {
        try {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error reading device: ' . $e->getMessage()]);
            exit;
        }
    }

    public function update($id, $data)
    {
        try {
            $query = "UPDATE " . $this->table . " SET device_type = ?, device_name = ?, status = ? WHERE device_id = ?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $data['device_type']);
            $stmt->bindParam(2, $data['device_name']);
            $stmt->bindParam(3, $data['status']);
            $stmt->bindParam(4, $id);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    throw new Exception('No changes were made. Device not found or data is the same as current.');
                }
            } else {
                throw new Exception('Device could not be updated due to a database error.');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
            exit;
        }
    }

    public function create($data)
    {
        try {
            $query = "INSERT INTO " . $this->table . " (device_type, device_name, status) VALUES (:device_type, :device_name, :status)";

            $stmt = $this->conn->prepare($query);

            $device_type = htmlspecialchars(strip_tags($data['device_type']));
            $device_name = htmlspecialchars(strip_tags($data['device_name']));
            $status = htmlspecialchars(strip_tags($data['status']));

            $stmt->bindParam(':device_type', $device_type);
            $stmt->bindParam(':device_name', $device_name);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception('Device could not be created.');
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

    public function delete($id)
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE device_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    throw new Exception('Device not found or has already been deleted.');
                }
            } else {
                throw new Exception('Device could not be deleted due to a database error.');
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
            exit;
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
            exit;
        }
    }

}
