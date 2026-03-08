<?php
// Koneksyon ak baz de done a
require_once '../config/db.php';

// Nou tcheke si gen yon ID ki pase nan URL la
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Nou efase coach la. 
        // Si ou te mete "ON DELETE CASCADE" nan SQL la, espesyalite yo ap efase otomatikman.
        $stmt = $pdo->prepare("DELETE FROM coaches WHERE id = ?");
        $stmt->execute([$id]);
        
        // Tout bagay byen pase, nou tounen sou lis la
        header("Location: index.php?msg=supprime");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    // Si pa gen ID, nou voye l tounen sou lis la
    header("Location: index.php");
    exit();
}