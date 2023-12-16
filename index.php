<?php

declare(strict_types=1);
require_once 'Auth.php'; // Include Auth.php


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// echo $uri;
// echo "\n";
// echo $method;
// echo "\n";

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

$database = new Database();
$db = $database->connect();

Auth::initialize($db); // Initialize Auth with DB

// if ($db) {
//     echo "Successfully connected to the database.";
// } else {
//     echo "Failed to connect to the database.";
// }
$userController = new UserController($db);
$deviceController = new DeviceController($db);

$uriSegments = explode('/', trim($uri, '/'));

if (count($uriSegments) >= 2 && $uriSegments[1] === 'users' && $method === 'POST') {
    $userController->createUser();
} elseif (count($uriSegments) >= 2 && $uriSegments[1] === 'devices') {
    Auth::authenticate(); // Authenticate before accessing device endpoints

    if (isset($uriSegments[2]) && is_numeric($uriSegments[2])) {


        $id = (int) $uriSegments[2];
        switch ($method) {
            case 'GET':
                $deviceController->getSingleDevice($id);
                break;
            case 'PUT':
                $deviceController->updateDevice($id);
                break;
            case 'DELETE':
                $deviceController->deleteDevice($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method not allowed']);
                break;
        }
    } elseif (count($uriSegments) >= 2 && $uriSegments[1] === 'devices' && $method === 'POST') {
        $deviceController->createDevice();
    } elseif (count($uriSegments) >= 2 && $uriSegments[1] === 'devices' && $method === 'GET') {
        $deviceController->getAllDevices();
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
}