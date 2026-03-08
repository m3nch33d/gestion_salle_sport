<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? null);
    $telephone = trim($_POST['telephone'] ?? null); // Nouvo liy

    if (!empty($nom)) {
        try {
            $sql = "INSERT INTO coaches (nom, email, telephone) VALUES (:nom, :email, :tel)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':email' => !empty($email) ? $email : null,
                ':tel' => !empty($telephone) ? $telephone : null
            ]);

            header("Location: liste_coaches.php?msg=REUSSIR");
            exit();
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}