<?php
// Koneksyon ak baz de done a
require_once '../config/db.php';

// Nou tcheke si gen yon ID ki pase nan URL la
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id']; // Nou fòse l tounen yon chif pou sekirite
    
    try {
        // Nou efase coach la ak yon Prepared Statement
        $stmt = $pdo->prepare("DELETE FROM coaches WHERE id = ?");
        $stmt->execute([$id]);
        
        // Nou tounen sou lis la ak yon mesaj siksè
        header("Location: liste_coaches.php?msg=SUPPRIME_OK");
        exit();
    } catch (PDOException $e) {
        // Si gen yon pwoblèm (pa egzanp si coach la gen sessions ki lye avè l)
        die("Erreur de suppression : " . $e->getMessage());
    }
} else {
    // Si pa gen ID, nou tounen sou lis la
    header("Location: liste_coaches.php");
    exit();
}