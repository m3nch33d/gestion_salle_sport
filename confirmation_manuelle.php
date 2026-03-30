<?php
require_once 'config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sub = $_POST['id_souscription'];
    $montant = $_POST['montant'];
    $phone = $_POST['phone'];
    
    // On génère un faux numéro de transaction MonCash (Ex: MC-827391)
    $fake_transaction = "MC-" . rand(100000, 999999);

    try {
        $pdo->beginTransaction();

        // 1. Enregistrer le paiement
        $stmt = $pdo->prepare("INSERT INTO paiements (id_souscription, montant_paye, stripe_payment_id, date_paiement, methode) 
                               VALUES (?, ?, ?, NOW(), 'MonCash')");
        $stmt->execute([$id_sub, $montant, $fake_transaction]);

        // 2. Mettre à jour la souscription
        $pdo->prepare("UPDATE souscriptions SET statut = 'payé' WHERE id = ?")->execute([$id_sub]);

        $pdo->commit();
        header("Location: index.php?status=success&method=moncash");

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}