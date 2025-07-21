<?php

// Test json
// Postavljanje Content-Type na JSON
header('Content-Type: application/json');

// Čitanje podataka iz POST zahteva
$raw_data = file_get_contents("php://input");

// Provera da li postoje podaci
if (empty($raw_data)) {
    echo json_encode(["error" => "Nema JSON podataka!"]);
    exit;
}

// Ispis sirovih podataka koje je server primio
echo json_encode(["received_data" => $raw_data]);

?>
