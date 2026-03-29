<?php
require_once 'includes/securite.php';
require_once 'config/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Début de la transaction pour s'assurer que tout est supprimé ou rien
        $pdo->beginTransaction();

        // 1. Supprimer d'abord les dépendances (Paiements et Souscriptions)
        // Sinon, MySQL bloquera la suppression du membre
        $stmt1 = $pdo->prepare("DELETE FROM paiements WHERE id = ?");
        $stmt1->execute([$id]);

        $stmt2 = $pdo->prepare("DELETE FROM souscriptions WHERE id = ?");
        $stmt2->execute([$id]);

        // 2. Enfin, supprimer le membre
        $stmt3 = $pdo->prepare("DELETE FROM membres WHERE id = ?");
        $stmt3->execute([$id]);

        $pdo->commit();
        
        // Redirection avec succès
        header("Location: membres.php?msg=deleted");
        exit();

    } catch (Exception $e) {
        // En cas d'erreur, on annule tout
        $pdo->rollBack();
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    header("Location: membres.php");
    exit();
}