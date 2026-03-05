<?php 
// 1. Inclure le header (Sécurité + Sidebar + Session incluses)
include 'includes/header.php'; 

// 2. Connexion à la base
require_once 'config/db.php'; 

// 3. Récupération des souscriptions avec les noms des membres
try {
    $sql = "SELECT s.*, m.nom, m.prenom 
            FROM souscriptions s 
            JOIN membres m ON s.id_membre = m.id 
            ORDER BY s.date_fin DESC";
    $stmt = $pdo->query($sql);
    $abonnements = $stmt->fetchAll();
} catch (Exception $e) {
    echo "<div class='bg-red-100 p-4 rounded-xl text-red-700'>Erreur : " . $e->getMessage() . "</div>";
}
?>

<div class="space-y-8">
    <div class="flex justify-between items-center">

        <div class="p-3 border border-[8px]-teal-500 rounded-[20px]">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Suivi des Abonnements</h2>
            <p class="text-slate-500 font-medium">Historique des paiements et validité des accès</p>
        </div>

        <a href="ajouter_abonnement.php" class="bg-teal-500 hover:bg-teal-400 text-slate-900 px-6 py-3 rounded-2xl font-black transition shadow-lg flex items-center transform hover:scale-105">
            <span class="mr-2"> <img src="assets/images/cc.png" class="w-10 h-10">  </span> Nouvel Abonnement
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-xl border border-slate-100 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest">
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Date Début</th>
                    <th class="px-6 py-5">Date Fin</th>
                    <th class="px-6 py-5">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($abonnements as $a): 
                    $expire = (strtotime($a['date_fin']) < time());
                ?>
                    <tr class="hover:bg-teal-50/30 transition">
                        <td class="px-6 py-4">
                            <p class="text-slate-900 font-bold"><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <?= date('d/m/Y', strtotime($a['date_debut'])) ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-bold">
                            <?= date('d/m/Y', strtotime($a['date_fin'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($expire): ?>
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full text-red-700 bg-red-100">Expiré</span>
                            <?php else: ?>
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full text-emerald-700 bg-emerald-100">Valide</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($abonnements)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">Aucun abonnement enregistré.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</main> </body>
</html>