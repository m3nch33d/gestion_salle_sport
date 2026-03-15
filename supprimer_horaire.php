<?php 
require_once 'config/db.php';

// Nou rekipere ID orè a nan URL la
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Preparasyon requete la pou sekirite
        $stmt = $pdo->prepare("DELETE FROM horaires WHERE id = ?");
        $stmt->execute([$id]);
        
        // Redireksyon vè lis la ak yon mesaj siksè
        header("Location: liste_horaires.php?msg=supprime");
        exit();
    } catch (Exception $e) {
        // Si gen erè, nou tounen ak mesaj erè a
        header("Location: liste_horaires.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si pa gen ID, nou jis tounen sou lis la
    header("Location: liste_horaires.php");
    exit();
}