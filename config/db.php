<?php
$host = '127.0.0.1';
$dbname = 'gestion_salle_sport';
$user = 'root';
$pass = ''; // Laisse vide pour Wamp par défaut
$port = '3306'; // Essaie '3308' si '3306' échoue

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si ça échoue sur 3306, on tente 3308 automatiquement
    try {
        $port = '3308';
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $ex) {
        die("Erreur fatale : " . $ex->getMessage());
    }
}