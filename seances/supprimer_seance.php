<?php
require_once '../config/db.php';

// Nou tcheke si gen yon ID ki voye nan URL la
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare rekèt pou efase a
        $sql = "DELETE FROM seances WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Egzekite rekèt la ak ID a
        if ($stmt->execute([':id' => $id])) {
            // Si sa mache, nou tounen sou index la ak yon mesaj siksè (opsyonèl)
            header("Location: index.php?msg=supprime");
            exit();
        }
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    // Si pa gen ID, nou tounen sou index
    header("Location: index.php");
    exit();
}