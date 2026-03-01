<?php 
include 'includes/header.php'; 
require_once 'config/db.php'; 

// Requête simplifiée et robuste
$sql = "SELECT p.date_presence, p.heure_entree, m.nom, m.prenom 
        FROM presences p 
        JOIN membres m ON p.id_membre = m.id 
        ORDER BY p.date_presence DESC, p.heure_entree DESC";

try {
    $stmt = $pdo->query($sql);
    $presences = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<div class='bg-red-500 text-white p-4 rounded-xl'>Erreur SQL : " . $e->getMessage() . "</div>";
    $presences = [];
}
?>

<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Historique des entrées</h2>
        <div class="bg-teal-100 text-teal-700 px-4 py-2 rounded-full font-bold text-xs uppercase">
            Contrôle d'accès actif
        </div>
    </div>

    <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest">
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Date de visite</th>
                    <th class="px-6 py-5">Heure d'entrée</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($presences as $p): ?>
                <tr class="hover:bg-teal-50/30 transition">
                    <td class="px-6 py-4 font-bold text-slate-800">
                        <?= htmlspecialchars($p['nom'] . " " . $p['prenom']) ?>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        <?= date('d/m/Y', strtotime($p['date_presence'])) ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-mono text-sm">
                            <?= $p['heure_entree'] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($presences)): ?>
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic">
                        Aucune entrée enregistrée pour le moment.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>