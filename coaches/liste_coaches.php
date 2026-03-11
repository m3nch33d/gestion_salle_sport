<?php 
// 1. Nou soti nan dosye sa a pou n al chèche config ak header
require_once '../config/db.php';
include '../includes/header.php'; 

// 2. Rekipere done coaches yo jan egzijans #6 mande l la
try {
    $coaches = $pdo->query("SELECT * FROM coaches ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    $coaches = [];
}
?>

<style>
    body {
        background: linear-gradient(rgba(226, 228, 231, 0.2), rgba(235, 236, 240, 0.25)), 
                    url('../assets/images/coachgym.png') no-repeat center center fixed;
        background-size: cover;
    }
    /* Sa a asire ke background blan ki nan main an pa kouvri imaj la */
    main {
        background: transparent !important;
    }
</style>

<div class="p-4 md:p-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 bg-slate-800/40 backdrop-blur-md p-8 rounded-[30px] border border-white/10 shadow-2xl">
        <div>
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter">
                Gestion <span class="text-teal-400">Coaches</span>
            </h1>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]"> NeuroCode Gym</p>
        </div>
        
        <a href="ajouter_coach.php" class="bg-teal-400 hover:bg-teal-300 text-slate-900 font-black px-8 py-4 rounded-2xl shadow-lg transition-all transform hover:scale-105">
            + NOUVEL ENTRAÎNEUR
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl overflow-hidden border-[12px] border-white">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100 bg-slate-50/50 shadow-xl">
                    <th class="p-6">Coach</th>
                    <th class="p-6">Spécialité </th>
                    <th class="p-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($coaches)): ?>
                <tr>
                    <td colspan="3" class="p-20 text-center text-slate-400 italic">
                        Aucun coach enregistré pour le moment.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($coaches as $c): ?>
                    <tr class="hover:bg-teal-100 transition-all">
                        <td class="p-6">
                            <p class="font-black text-slate-800 text-lg uppercase"><?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?></p>
                            <p class="text-xs text-slate-400 font-mono"><?= htmlspecialchars($c['telephone']) ?></p>
                        </td>
                        <td class="p-6">
                            <span class="bg-teal-50 text-teal-600 px-4 py-1 rounded-full text-[10px] font-black uppercase border border-teal-100">
                                <?= htmlspecialchars($c['specialite']) ?>
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex justify-center gap-4 text-[10px] font-black uppercase">
                                <a href="modifier_coach.php?id=<?= $c['id'] ?>" class="text-slate-400 hover:text-teal-500 transition-colors">Modifier</a>
                                <a href="supprimer_coach.php?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ce coach ?')" class="text-rose-400 hover:text-rose-600">Retirer</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
// Nou fèmen main an ki te louvri nan header a
echo "</main></body></html>"; 
?>