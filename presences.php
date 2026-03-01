<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// Récupérer les présences avec les noms des membres
$sql = "SELECT p.date_entree, m.nom, m.prenom, a.libelle
        FROM presences p
        JOIN membres m ON p.id_membre = m.id
        JOIN souscriptions s ON m.id = s.id_membre
        JOIN abonnements a ON s.id_abonnement = a.id
        ORDER BY p.date_entree DESC";
$presences = $pdo->query($sql)->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Flux des Membres</h1>
        <a href="scanner.php" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-indigo-700">
            + Enregistrer une Entrée
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-xs font-bold text-gray-500 uppercase">
                    <th class="px-6 py-4">Heure d'entrée</th>
                    <th class="px-6 py-4">Membre</th>
                    <th class="px-6 py-4">Abonnement</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($presences as $pr): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-indigo-600">
                        <?= date('d/m/Y à H:i', strtotime($pr['date_entree'])) ?>
                    </td>
                    <td class="px-6 py-4 font-bold"><?= htmlspecialchars($pr['nom'] . ' ' . $pr['prenom']) ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 rounded text-xs"><?= htmlspecialchars($pr['libelle']) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>