<?php

declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

$database = new Database();
$db = $database->connect();

$userController = new UserController($db);
$deviceController = new DeviceController($db);

$uriSegments = explode('/', trim($uri, '/'));

// User creation endpoint
if (count($uriSegments) >= 2 && $uriSegments[1] === 'users' && $method === 'POST') {
    $userController->createUser();
} elseif (count($uriSegments) > 2 && $uriSegments[1] === 'devices') {
    // Authentication check for device operations
    if (!Auth::authenticate()) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    $id = isset($uriSegments[2]) && is_numeric($uriSegments[2]) ? (int) $uriSegments[2] : null;

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $deviceController->getSingleDevice($id);
            } else {
                $deviceController->getAllDevices();
            }
            break;
        case 'POST':
            $deviceController->createDevice();
            break;
        case 'PUT':
            if ($id !== null) {
                $deviceController->updateDevice($id);
            }
            break;
        case 'DELETE':
            if ($id !== null) {
                $deviceController->deleteDevice($id);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
}