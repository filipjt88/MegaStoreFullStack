<?php 
$host = 'localhost';
$dbname = 'webshop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username,$password);
} catch(PDOException $e) {
    die("Connection failed:" . $e->getMessage());
}
?>