<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "config.php";

try {
    $stmt = $pdo->query("SELECT id, firstname, lastname, email, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(["error" => "Greška u bazi: " . $e->getMessage()]);
}

?>
