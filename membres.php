<?php 
// 1. Inclure le header (qui gère déjà la session, la sécurité et la sidebar)
include 'includes/header.php'; 

// 2. Connexion à la base de données
require_once 'config/db.php'; 

// 3. Récupération des membres
$query = $pdo->query("SELECT * FROM membres ORDER BY id DESC");
$membres = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="space-y-8">
    <div class="flex justify-between items-center">

         <div class="p-3 border border-[8px]-teal-500 rounded-[20px]">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Gestion des Adhérents</h2>
            <p class="text-slate-500 font-medium">Liste complète des membres de la salle</p>
        </div> 

        

        <a href="ajouter_membre.php" class="bg-teal-500 hover:bg-teal-400 text-slate-900 px-6 py-3 rounded-2xl font-black transition shadow-lg flex items-center transform hover:scale-105">
            <span class="mr-2"> <img src="assets/images/add.png" class="w-10 h-10">  </span> Ajouter un membre
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest shadow-xl">
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Téléphone</th>
                    <th class="px-6 py-5">Statut</th>
                    <th class="px-6 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($membres as $m): ?>
                    <tr class="hover:bg-teal-50/30 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                    <?= strtoupper(substr($m['nom'], 0, 1)) ?>
                                </div>
                                <div class="ml-4">
                                    <p class="text-slate-900 font-bold"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></p>
                                    <p class="text-slate-400 text-xs italic"><?= htmlspecialchars($m['email']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium"><?= htmlspecialchars($m['telephone']) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full <?= $m['statut'] == 'actif' ? 'text-teal-700 bg-teal-100' : 'text-red-700 bg-red-100' ?>">
                                <?= ucfirst($m['statut']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3 text-xs font-bold">
    
    
    <a href="modifier_membre.php?id=<?= $m['id'] ?>" class="text-orange-500 hover:underline">Modifier</a>
    <a href="carte.php?id=<?= $m['id'] ?>" class="text-teal-600 hover:underline">Carte</a>
    <a href="#" onclick="confirmerSuppression(<?= $m['id'] ?>, '<?= htmlspecialchars($m['nom']) ?>')" class="text-red-500 hover:underline">Supprimer</a>
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
       </table>
    </div> 

    <div class="flex justify-end mt-8">
        <a href="presences.php" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-2xl font-black transition shadow-lg flex items-center transform hover:scale-105">
            <span class="mr-2 text-lg"><img src="assets/images/souscriptions.png" class="w-10 h-10"></span> Liste des présences
        </a>
    </div>
</div> </main> <script>
function confirmerSuppression(id, nomComplet) {
    // Demande personnalisée avec le nom du membre
    if (confirm("Êtes-vous sûr de vouloir supprimer \"" + nomComplet + "\" ?")) {
        window.location.href = "supprimer_membre.php?id=" + id;
    }
}
</script>

</body>
</html>

