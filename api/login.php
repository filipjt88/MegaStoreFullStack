<?php

// Login

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "config.php";

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(["error" => "Nema JSON podataka!"]);
    exit;
}

if (empty($data['email']) || empty($data['password'])) {
    echo json_encode(["error" => "Email i lozinka su obavezni!"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        echo json_encode(["error" => "Neispravan email ili lozinka!"]);
        exit;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $user['password'])) {
        echo json_encode(["success" => "Uspešno ste prijavljeni!", "user" => $user]);
    } else {
        echo json_encode(["error" => "Neispravan email ili lozinka!"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Greška u bazi: " . $e->getMessage()]);
}

?>
