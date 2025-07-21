<?php

// Register
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


require_once "config.php";

// Preuzimanje JSON podataka iz zahteva
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Provera da li su podaci poslati
if (!$data) {
    echo json_encode(["error" => "Nema JSON podataka!"]);
    exit;
}

// Provera da li su sva polja popunjena
if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['password'])) {
    echo json_encode(["error" => "Sva polja su obavezna!"]);
    exit;
}

$firstname = htmlspecialchars(strip_tags($data['firstname']));
$lastname = htmlspecialchars(strip_tags($data['lastname']));
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = password_hash($data['password'], PASSWORD_DEFAULT);

try {
    // Provera da li email već postoji
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["error" => "Korisnik sa ovim email-om već postoji!"]);
        exit;
    }

    // Ubacivanje korisnika
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");
    $stmt->bindParam(":firstname", $firstname);
    $stmt->bindParam(":lastname", $lastname);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Korisnik je uspešno registrovan!"]);
    } else {
        echo json_encode(["error" => "Došlo je do greške pri registraciji."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Greška u bazi: " . $e->getMessage()]);
}

?>
