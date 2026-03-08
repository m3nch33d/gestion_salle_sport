<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// 1. Récupérer les souscriptions qui n'ont pas encore été payées (ou pour un nouveau mois)
// On joint les membres et les abonnements pour avoir un affichage clair
$sql_souscriptions = "SELECT s.id, m.nom, m.prenom, a.libelle, a.prix 
                      FROM souscriptions s
                      JOIN membres m ON s.id_membre = m.id
                      JOIN abonnements a ON s.id_abonnement = a.id
                      ORDER BY s.id DESC";
$souscriptions = $pdo->query($sql_souscriptions)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_souscription = $_POST['id_souscription'];
    $montant = $_POST['montant'];
    $methode = $_POST['methode'];

    try {
        $sql = "INSERT INTO paiements (id_souscription, montant_paye, methode_paiement) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_souscription, $montant, $methode]);
        
        echo "<div class='bg-green-500 text-white p-4 text-center'>Paiement enregistré avec succès !</div>";
        echo "<script>setTimeout(() => { window.location.href='paiements.php'; }, 1500);</script>";
    } catch (PDOException $e) {
        echo "<div class='bg-red-500 text-white p-4'>Erreur : " . $e->getMessage() . "</div>";
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="妥9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Encaisser un Paiement</h2>
        </div>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Adhérent & Contrat</label>
                <select name="id_souscription" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none appearance-none">
                    <option value="">-- Choisir la vente à encaisser --</option>
                    <?php foreach($souscriptions as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['nom'] . ' ' . $s['prenom']) ?> - <?= htmlspecialchars($s['libelle']) ?> (<?= $s['prix'] ?> HTG)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Montant reçu (HTG)</label>
                <input type="number" step="0.01" name="montant" placeholder="Ex: 1500" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Méthode de paiement</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="methode" value="cash" checked class="text-green-600">
                        <span class="ml-2">Cash</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="methode" value="moncash" class="text-green-600">
                        <span class="ml-2">MonCash</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition duration-300 shadow-lg transform hover:-translate-y-1">
                Confirmer le Paiement
            </button>
        </form>
    </div>
</div>