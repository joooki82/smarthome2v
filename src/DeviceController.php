<?php
class DeviceController
{
    private $db;
    private $device;

    public function __construct($db)
    {
        $this->db = $db;
        $this->device = new Device($db);
    }

    public function getSingleDevice($id)
    {
        $result = $this->device->read($id)->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Device not found']);
        }
    }

    public function getAllDevices()
    {
        $result = $this->device->readAll()->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No devices found']);
        }
    }

    public function updateDevice($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($this->device->update($id, $data)) {
            http_response_code(200);
            echo json_encode(['message' => 'Device updated']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Device could not be updated']);
        }
    }

    public function createDevice()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['device_type']) || empty($data['device_name']) || empty($data['status'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Incomplete data provided']);
            return;
        }

        try {
            if ($this->device->create($data)) {
                http_response_code(201);
                echo json_encode(['message' => 'Device created']);
            } else {
                throw new Exception('Device could not be created');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function deleteDevice($id)
    {
        if ($this->device->delete($id)) {
            http_response_code(200);
            echo json_encode(['message' => 'Device deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Device could not be deleted']);
        }
    }

}
