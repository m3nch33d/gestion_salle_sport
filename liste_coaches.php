<?php 
require_once 'config/db.php';
include 'includes/header.php'; 

try {
    $coaches = $pdo->query("SELECT * FROM coaches ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    $coaches = [];
}
?>

<link rel="stylesheet" href="public/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        margin: 0;
        background: linear-gradient(rgba(226, 228, 231, 0.2), rgba(235, 236, 240, 0.25)), 
                    url('assets/images/coachgym.png') no-repeat center center fixed;
        background-size: cover;
        overflow-x: hidden;
    }
    main { background: transparent !important; }

    /* Animation de survol pour les lignes du tableau */
    .row-hover:hover {
        background: rgba(20, 184, 166, 0.1) !important;
        transition: all 0.4s ease;
    }
</style>

<div id="main-content" class="p-4 md:p-8 animate__animated animate__fadeInUp">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 bg-slate-800/40 backdrop-blur-md p-8 rounded-[30px] border border-white/10 shadow-2xl">
        <div>
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter">
                Gestion <span class="text-teal-400">Coaches</span>
            </h1>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]"> NeuroCode Gym</p>
        </div>
        
        <a href="ajouter_coach.php" class="bg-teal-400 hover:bg-teal-300 text-slate-900 font-black px-8 py-4 rounded-2xl shadow-lg transition-all transform hover:scale-105">
            <span><img src="assets/images/add.png" class="w-5 h-5 mr-2">NOUVEL ENTRAÎNEUR
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
                    <tr class="row-hover transition-all">
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
                                <a href="supprimer_coach.php?id=<?= $c['id'] ?>" onclick="return confirmerSuppression(event, this.href)" class="text-rose-400 hover:text-rose-600">Retirer</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// 1. Gestion de la suppression avec animation
function confirmerSuppression(e, url) {
    e.preventDefault();
    if (confirm('Voulez-vous vraiment retirer ce coach ?')) {
        const container = document.getElementById('main-content');
        container.classList.remove('animate__fadeInUp');
        container.classList.add('animate__fadeOutDown');
        setTimeout(() => {
            window.location.href = url;
        }, 500);
    }
    return false;
}

// 2. Gestion des transitions de sortie vers le bas
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([onclick])');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (link.hostname === window.location.hostname) {
                e.preventDefault();
                const destination = this.href;
                container.classList.remove('animate__fadeInUp');
                container.classList.add('animate__fadeOutDown');
                setTimeout(() => { window.location.href = destination; }, 500);
            }
        });
    });
});
</script>

<?php 
echo "</main></body></html>"; 
?>