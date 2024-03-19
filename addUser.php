<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");
// Allow requests with the POST method
header("Access-Control-Allow-Methods: POST, OPTIONS");
// Allow requests with content type application/json
header("Access-Control-Allow-Headers: Content-Type");

// Check if it's a preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Define API response format
header('Content-Type: application/json');

// Check the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Establish MySQL database connection
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $database = "schedule";

    // Create connection
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Add new user
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (isset($data['username']) && isset($data['password'])) {
        $username = $conn->real_escape_string($data['username']);
        $password = $conn->real_escape_string($data['password']);
        
        // Insert user into database
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("success" => true, "message" => "User added successfully"));
        } else {
            echo json_encode(array("success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Name and email are required"));
    }

    // Close database connection
    $conn->close();
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Method not allowed"));
}
?>