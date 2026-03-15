<?php 
require_once 'config/db.php';
// Nou pa bezwen header.php isit la paske se yon operasyon baz de done sèlman

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Nou prepare requete la pou evite injection SQL
        $stmt = $pdo->prepare("DELETE FROM coaches WHERE id = ?");
        $stmt->execute([$id]);
        
        // Apre li fin siprime, li tounen nan lis la otomatikman
        header("Location: liste_coaches.php?success=1");
        exit();
    } catch (Exception $e) {
        // Si gen yon erè, nou ka voye l tounen ak yon mesaj
        header("Location: liste_coaches.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si pa gen ID, nou tounen nan lis la
    header("Location: liste_coaches.php");
    exit();
}