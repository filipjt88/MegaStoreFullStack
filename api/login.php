<?php
header("Content-Type: application/json");
require_once("../core/config.php");

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['email'], $data['password'])) {
    echo json_encode(["error" => "Email and password are required!"]);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

try {
    $stmt->$pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!user || !password_verify($password, $user['password'])) {
        echo json_encode(["error" => "Wrong email or password!"]);
        exit;
    }

    echo json_encode(["success" => "Login successful!", "user_id" => $user['id']]);
} catch(PDOException $e) {
    echo json_encode(["error" => "Error login!" . $e->getMessage()]);
}

?>