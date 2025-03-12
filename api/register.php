<?php
header("Content-Type: application/json");
require_once("../core/config.php");

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['firstname'], $data['lastname'], $data['email'], $data['password'])) {
    echo json_encode(["error" => "All fields are required!"]);
    exit;
}

$firstname = trim($data['firstname']);
$lastname = trim($data['lastname']);
$email = trim($data['email']);
$password = trim($data['password']);

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" = > "Defective mail!"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if($stmt->rowCount() > 0) {
        echo json_encode(["error" => "Email is already registered!"]);
        exit;
    }

    $stmt->$pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?,?,?,?)");
    $stmt->execute([$firstname, $lastname, $email, $hashed_password]);
    echo json_encode(["success" => "Successful registration!"]);
} catch(PDOException $e) {
    echo json_encode(["error" => "Registration error!" . $e->getMessage()]);
}