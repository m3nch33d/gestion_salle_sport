<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    try {
        // Enregistre nan tab coaches
        $sql = "INSERT INTO coaches (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $telephone]);
        
        $coach_id = $pdo->lastInsertId();

        // Enregistre spécialités nan tab de liaison
        if (!empty($_POST['spec'])) {
            foreach ($_POST['spec'] as $speciality_id) {
                $sql_spec = "INSERT INTO coach_speciality (coach_id, speciality_id) VALUES (?, ?)";
                $stmt_spec = $pdo->prepare($sql_spec);
                $stmt_spec->execute([$coach_id, $speciality_id]);
            }
        }

        header("Location: index.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>