<?php


header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

include('dbConnect.php');


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM users";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $users = array();
        
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        echo json_encode(array("success" => true, "users" => $users));
    } else {
        echo json_encode(array("success" => false, "message" => "No users found"));
    }
}


$conn->close();
?>