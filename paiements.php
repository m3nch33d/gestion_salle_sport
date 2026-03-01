<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// Requête pour récupérer tous les paiements avec les noms des membres
$sql = "SELECT p.*, m.nom, m.prenom, a.libelle 
        FROM paiements p
        JOIN souscriptions s ON p.id_souscription = s.id
        JOIN membres m ON s.id_membre = m.id
        JOIN abonnements a ON s.id_abonnement = a.id
        ORDER BY p.date_paiement DESC";
$query = $pdo->query($sql);
$paiements = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Historique des Paiements</h1>
        <div class="bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg font-bold">
            Total : <?php 
                $total = array_sum(array_column($paiements, 'montant_paye'));
                echo number_format($total, 2, '.', ' ') . " HTG";
            ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b">
    <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
        <th class="px-6 py-4">Date</th>
        <th class="px-6 py-4">Membre</th>
        <th class="px-6 py-4">Forfait</th>
        <th class="px-6 py-4">Montant</th>
        <th class="px-6 py-4">Méthode</th>
        <th class="px-6 py-4">Action</th> </tr>
</thead>

<tbody class="divide-y divide-gray-100">
    <?php foreach ($paiements as $p): ?>
    <tr class="hover:bg-gray-50 transition">
        <td class="px-6 py-4 text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($p['date_paiement'])) ?></td>
        <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($p['nom'] . ' ' . $p['prenom']) ?></td>
        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($p['libelle']) ?></td>
        <td class="px-6 py-4 font-bold text-green-600"><?= number_format($p['montant_paye'], 2, '.', ' ') ?> HTG</td>
        <td class="px-6 py-4 text-xs font-bold uppercase"><?= $p['methode_paiement'] ?></td>
        
        <td class="px-6 py-4">
            <a href="recu.php?id=<?= $p['id'] ?>" class="text-indigo-600 hover:text-indigo-900 font-bold flex items-center">
                <span class="mr-1">📄</span> Reçu
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    </div>
</div>

