<?php 
// 1. Sécurité et Session en premier
session_start();
require_once 'config/db.php'; 

if (!isset($_SESSION['admin_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit();
}

include 'includes/header.php'; 

// 2. Récupération de tous les membres
$query = $pdo->query("SELECT * FROM membres ORDER BY id DESC");
$membres = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-4 py-8 bg-teal-100 rounded-[30px] border border-slate-100">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight uppercase italic">Gestion des Adhérents</h1>
            <p class="text-gray-500 text-sm">Liste complète des membres de la salle</p>
        </div>
        <a href="ajouter_membre.php" class="bg-teal-500 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg flex items-center">
            <span class="mr-2">➕</span> Ajouter un membre
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-400 text-left text-xs uppercase font-bold tracking-widest">
                    <th class="px-6 py-4">Membre</th>
                    <th class="px-6 py-4">Téléphone</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($membres)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                            Aucun membre enregistré pour le moment.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($membres as $m): ?>
                        <tr class="hover:bg-indigo-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                        <?= strtoupper(substr($m['nom'], 0, 1)) ?>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-900 font-bold"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></p>
                                        <p class="text-gray-500 text-xs italic"><?= htmlspecialchars($m['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                <?= htmlspecialchars($m['telephone']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-black uppercase rounded-full <?= $m['statut'] == 'actif' ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' ?>">
                                    <?= ucfirst($m['statut']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 text-sm font-bold">
                                <a href="voir_membre.php?id=<?= $m['id'] ?>" class="text-blue-600 hover:underline">Détails</a>
                                <a href="modifier_membre.php?id=<?= $m['id'] ?>" class="text-orange-600 hover:underline">Modifier</a>
                                <a href="carte.php?id=<?= $m['id'] ?>" class="text-emerald-600 hover:underline">🪪 Carte</a>
                                
                                <a href="#" 
                                   onclick="confirmerSuppression(<?= $m['id'] ?>, '<?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?>')" 
                                   class="text-red-600 hover:underline">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmerSuppression(id, nom) {
    if (confirm("⚠️ Êtes-vous sûr de vouloir supprimer " + nom + " ?\nCette action est irréversible et supprimera aussi ses historiques.")) {
        window.location.href = "supprimer_membre.php?id=" + id;
    }
}
</script>

</body>
</html>