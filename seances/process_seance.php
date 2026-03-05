<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $coach_id = $_POST['coach_id'];
    $date_seance = $_POST['date_seance'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $capacite = $_POST['capacite_max'];

    try {
        // Prepare requête SQL la
        $sql = "INSERT INTO seances (titre, coach_id, date_seance, heure_debut, heure_fin, capacite_max) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titre, $coach_id, $date_seance, $heure_debut, $heure_fin, $capacite]);

        // Si sa mache, voye nou sou paj index la
        header("Location: index.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Erreur pandan anrejistreman an : " . $e->getMessage());
    }
}
?>