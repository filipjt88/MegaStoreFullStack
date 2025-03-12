<?php

header("Content-Type: application/json");

// Učitavanje podataka iz POST-a
$raw_data = file_get_contents("php://input");

// Provera da li postoje podaci
if (empty($raw_data)) {
    echo json_encode(["error" => "Nema JSON podataka!"]);
    exit;
}

// Dekodiranje JSON podataka
$data = json_decode($raw_data, true);

// Provera greške pri dekodiranju JSON-a
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["error" => "Neispravni JSON podaci!", "json_error" => json_last_error_msg()]);
    exit;
}

// Provera da li su svi podaci prisutni
if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['password'])) {
    echo json_encode(["error" => "Sva polja su obavezna!"]);
    exit;
}

// Dalja logika, kao što je unos u bazu
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

// PDO konekcija sa bazom
try {
    $pdo = new PDO("mysql:host=localhost;dbname=mega_store", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Provera da li postoji korisnik sa istim email-om
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["error" => "Korisnik sa ovim email-om već postoji!"]);
        exit;
    }

    // Unos novog korisnika u bazu
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
    echo json_encode(["error" => "Greška u bazi podataka: " . $e->getMessage()]);
}
?>
