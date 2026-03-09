<?php
require_once '../config/db.php';

// Nou tcheke si done yo voye tout bon vre
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Nou ranplase ansyen non yo ak nouvo non nou mete nan HTML la
    $titre = $_POST['titre'] ?? null;
    $coach_id = $_POST['coach_id'] ?? null;
    $date_seance = $_POST['date_seance'] ?? null;
    $heure_debut = $_POST['heure_debut'] ?? null;

    // Nou tcheke si tout jaden yo ranpli
    if ($titre && $coach_id && $date_seance && $heure_debut) {
        try {
            $sql = "INSERT INTO seances (titre, coach_id, date_seance, heure_debut) 
                    VALUES (:titre, :coach_id, :date_seance, :heure_debut)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titre' => $titre,
                ':coach_id' => $coach_id,
                ':date_seance' => $date_seance,
                ':heure_debut' => $heure_debut
            ]);

            // Si sa mache, nou tounen sou index la ak mesaj siksè a
            header("Location: index.php?success=1");
            exit();
            
        } catch (PDOException $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }
    } else {
        die("Erreur : Tout jaden yo obligatwa.");
    }
}
?>