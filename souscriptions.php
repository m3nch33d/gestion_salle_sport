<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// Requête pour lier les membres, leurs abonnements et leurs dates
$sql = "SELECT s.*, m.nom, m.prenom, a.libelle 
        FROM souscriptions s
        JOIN membres m ON s.id_membre = m.id
        JOIN abonnements a ON s.id_abonnement = a.id
        ORDER BY s.date_fin DESC";
$query = $pdo->query($sql);
$souscriptions = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Suivi des Adhésions</h1>
        <a href="ajouter_souscription.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md transition">
            + Nouvelle Inscription
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Membre</th>
                    <th class="px-6 py-4">Forfait</th>
                    <th class="px-6 py-4">Validité</th>
                    <th class="px-6 py-4">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($souscriptions as $s): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($s['nom'] . ' ' . $s['prenom']) ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($s['libelle']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        Du <?= date('d/m/Y', strtotime($s['date_debut'])) ?> 
                        au <span class="font-bold text-gray-700"><?= date('d/m/Y', strtotime($s['date_fin'])) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <?php 
                        $expire = (strtotime($s['date_fin']) < time());
                        $classe = $expire ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
                        $label = $expire ? 'Expiré' : 'Actif';
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold <?= $classe ?>">
                            <?= $label ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
</div>

<div class=" justify-between items-center mb-6">
<a href="encaisser.php" class="block p-6 bg-emerald-500  text-white rounded-[20px] shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-1">
        <p class="text-2xl mb-2"><img src="assets/images/bagmoney.png" class="w-10 h-10"></p>
        <p class="font-black uppercase tracking-tighter">Encaisser un Paiement</p>
        <p class="text-emerald-100 text-xs">Enregistrer une cotisation ou un frais</p>
    </a>
</div>    
