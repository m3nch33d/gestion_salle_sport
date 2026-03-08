<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// 1. Récupérer les membres et les abonnements pour les menus déroulants
$membres = $pdo->query("SELECT id, nom, prenom FROM membres ORDER BY nom")->fetchAll();
$offres = $pdo->query("SELECT id, libelle, duree_mois FROM abonnements ORDER BY libelle")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_membre = $_POST['id_membre'];
    $id_abonnement = $_POST['id_abonnement'];
    $date_debut = $_POST['date_debut'];

    // Trouver la durée de l'abonnement choisi pour calculer la date de fin
    $stmt = $pdo->prepare("SELECT duree_mois FROM abonnements WHERE id = ?");
    $stmt->execute([$id_abonnement]);
    $duree = $stmt->fetchColumn();

    // Calcul de la date de fin
    $date_fin = date('Y-m-d', strtotime("$date_debut + $duree months"));

    try {
        $sql = "INSERT INTO souscriptions (id_membre, id_abonnement, date_debut, date_fin, statut) VALUES (?, ?, ?, ?, 'actif')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_membre, $id_abonnement, $date_debut, $date_fin]);
        
        echo "<script>window.location.href='souscriptions.php';</script>";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Nouvelle Souscription</h2>
        
        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Choisir l'Adhérent</label>
                <select name="id_membre" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                    <option value="">-- Sélectionner un membre --</option>
                    <?php foreach($membres as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Choisir le Forfait</label>
                <select name="id_abonnement" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                    <option value="">-- Sélectionner une offre --</option>
                    <?php foreach($offres as $o): ?>
                        <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['libelle']) ?> (<?= $o['duree_mois'] ?> mois)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                <input type="date" name="date_debut" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition shadow-md">
                Valider l'adhésion
            </button>
            
            <div class="text-center">
                <a href="souscriptions.php" class="text-sm text-gray-500 hover:underline">Annuler</a>
            </div>
        </form>
    </div>
</div>