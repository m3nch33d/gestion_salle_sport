<?php 
require_once 'config/db.php';
include 'includes/header.php'; 

try {
    $coaches = $pdo->query("SELECT * FROM coaches ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    $coaches = [];
}
?>

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

    /* Animasyon survol */
    .row-hover:hover {
        background: rgba(20, 184, 166, 0.05) !important;
        transition: all 0.3s ease;
    }

    /* Vlope tablo a pou l bèl */
    .table-wrapper {
        border-radius: 30px;
        overflow: hidden;
        border: 6px solid white;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
    }

    @media (min-width: 768px) {
        .table-wrapper {
            border-radius: 40px;
            border: 12px solid white;
        }
    }
</style>

<div id="main-content" class="p-3 md:p-8 animate__animated animate__fadeInUp">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 md:mb-10 bg-slate-800/40 backdrop-blur-md p-6 md:p-8 rounded-[25px] md:rounded-[30px] border border-white/10 shadow-2xl gap-6 text-center md:text-left">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tighter">
                Gestion <span class="text-teal-400">Coaches</span>
            </h1>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]">NeuroCode Gym</p>
        </div>
        
        <a href="ajouter_coach.php" class="w-full md:w-auto bg-teal-400 hover:bg-teal-300 text-slate-900 font-black px-6 md:px-8 py-4 rounded-2xl shadow-lg transition-all transform hover:scale-105 flex items-center justify-center">
            <img src="assets/images/add.png" class="w-5 h-5 mr-2"> 
            <span class="text-sm md:text-base">NOUVEL ENTRAÎNEUR</span>
        </a>
    </div>

    <div class="table-wrapper bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 uppercase text-[9px] md:text-[10px] font-black tracking-widest border-b border-slate-100 bg-slate-50/50">
                        <th class="p-4 md:p-6">Coach</th>
                        <th class="p-4 md:p-6">Spécialité</th>
                        <th class="p-4 md:p-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($coaches)): ?>
                    <tr>
                        <td colspan="3" class="p-10 md:p-20 text-center text-slate-400 italic text-sm">
                            Aucun coach enregistré pour le moment.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($coaches as $c): ?>
                        <tr class="row-hover transition-all">
                            <td class="p-4 md:p-6">
                                <p class="font-black text-slate-800 text-sm md:text-lg uppercase leading-tight">
                                    <?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?>
                                </p>
                                <p class="text-[10px] md:text-xs text-slate-400 font-mono mt-1">
                                    <?= htmlspecialchars($c['telephone']) ?>
                                </p>
                            </td>
                            <td class="p-4 md:p-6">
                                <span class="bg-teal-50 text-teal-600 px-3 md:px-4 py-1 rounded-full text-[8px] md:text-[10px] font-black uppercase border border-teal-100 whitespace-nowrap">
                                    <?= htmlspecialchars($c['specialite']) ?>
                                </span>
                            </td>
                            <td class="p-4 md:p-6 text-center">
                                <div class="flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-4 text-[9px] md:text-[10px] font-black uppercase">
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
</div>

<script>
// Fonksyon pou konfime suppression ak animasyon
function confirmerSuppression(e, url) {
    e.preventDefault();
    if (confirm('Voulez-vous vraiment retirer ce coach ?')) {
        const container = document.getElementById('main-content');
        container.classList.remove('animate__fadeInUp');
        container.classList.add('animate__fadeOutDown');
        setTimeout(() => { window.location.href = url; }, 500);
    }
    return false;
}

// Tranzisyon paj dous
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

<?php echo "</main></body></html>"; ?>