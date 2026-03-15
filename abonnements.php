<?php 
include 'includes/header.php'; 
require_once 'config/db.php'; 

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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        margin: 0;
        background-image: url('assets/images/gymgris.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        overflow-x: hidden;
    }

    .glass-title {
        background: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 20px 35px;
        border-radius: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .glass-btn-teal {
        background: rgba(20, 184, 166, 0.15) !important; 
        backdrop-filter: blur(20px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        color: #14b8a6 !important; 
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-btn-teal:hover {
        background: rgba(20, 184, 166, 0.25) !important;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border-color: rgba(255, 255, 255, 0.6) !important;
    }

    main { background: transparent !important; }

    /* Ajustements pour Mobile */
    @media (max-width: 768px) {
        .glass-title { padding: 15px 20px; text-align: center; width: 100%; }
        .flex-header { flex-direction: column; gap: 1.5rem; }
        .glass-btn-teal { width: 100%; justify-content: center; }
    }
</style>

<div id="main-content" class="space-y-8 p-4 md:p-6 relative z-10 animate__animated animate__fadeInUp">
    
    <div class="flex flex-header justify-between items-center">
        <div class="glass-title">
            <h2 class="text-2xl md:text-3xl font-black text-teal-300 tracking-tight uppercase">Suivi des Abonnements</h2>
            <p class="text-white/60 text-xs md:text-sm font-medium italic">Historique des paiements et validité des accès</p>
        </div>

        <a href="ajouter_abonnement.php" class="glass-btn-teal px-6 md:px-8 py-4 rounded-3xl font-black shadow-lg flex items-center transform">
            <span class="mr-3"> <img src="assets/images/cc.png" class="w-7 h-7 md:w-8 md:h-8"> </span> 
            Nouvel Abonnement
        </a>
    </div>

    <div class="bg-white/95 rounded-[30px] md:rounded-[40px] shadow-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-[10px] md:text-xs uppercase font-bold tracking-widest shadow-sm">
                        <th class="px-6 py-5">Membre</th>
                        <th class="px-6 py-5">Début</th>
                        <th class="px-6 py-5">Fin</th>
                        <th class="px-6 py-5">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($abonnements as $a): 
                        $expire = (strtotime($a['date_fin']) < time());
                    ?>
                         <tr class="hover:bg-teal-50/70 transition-colors duration-500">
                            <td class="px-6 py-5">
                                <p class="text-slate-900 font-bold text-sm"><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></p>
                            </td>
                            <td class="px-6 py-5 text-xs md:text-sm text-slate-600">
                                <?= date('d/m/Y', strtotime($a['date_debut'])) ?>
                            </td>
                            <td class="px-6 py-5 text-xs md:text-sm text-slate-600 font-bold">
                                <?= date('d/m/Y', strtotime($a['date_fin'])) ?>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 text-[9px] md:text-[10px] font-black uppercase rounded-full <?= $expire ? 'text-red-700 bg-red-100' : 'text-emerald-700 bg-emerald-100' ?>">
                                    <?= $expire ? 'Expiré' : 'Valide' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-12 space-y-6">
        <div class="glass-title border-l-8 border-orange-400">
           <h3 class="flex items-center gap-3 text-xl md:text-2xl font-black text-white uppercase tracking-tighter">
             <img src="assets/images/bell.png" class="w-8 h-8 md:w-10 md:h-10 object-contain">
             <span>Alertes Relances (J-7)</span>
           </h3>
           <p class="text-slate-300 text-[10px] md:text-sm italic mt-1 ml-9">Membres dont l'accès expire bientôt</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <?php 
            $alerte_trouvee = false;
            foreach ($abonnements as $a): 
                $date_fin_timestamp = strtotime($a['date_fin']);
                $date_actuelle = time();
                $limite_7_jours = strtotime('+7 days');

                if ($date_fin_timestamp >= $date_actuelle && $date_fin_timestamp <= $limite_7_jours): 
                    $alerte_trouvee = true;
                    $diff = $date_fin_timestamp - $date_actuelle;
                    $jours_restants = ceil($diff / (60 * 60 * 24));
            ?>
                <div class="glass-title bg-white/5 border-white/10 hover:bg-white/10 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-2 py-0.5 bg-orange-500/20 text-orange-400 text-[8px] md:text-[9px] font-black uppercase rounded-lg border border-orange-500/30">
                            Attention
                        </span>
                        <span class="text-white font-mono text-xs font-bold">J-<?= $jours_restants ?></span>
                    </div>
                    
                    <h4 class="text-white font-bold text-base md:text-lg uppercase"><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></h4>
                    <p class="text-slate-400 text-[10px] md:text-xs mt-3">Date d'expiration : <br>
                        <span class="text-teal-300 font-black text-sm"><?= date('d/m/y', $date_fin_timestamp) ?></span>
                    </p>
                </div>
            <?php endif; endforeach; 

            if (!$alerte_trouvee): ?>
                <div class="col-span-full p-10 text-center glass-title opacity-50">
                    <p class="text-teal-300 italic text-sm">Aucune expiration prévue prochainement.</p>
                </div>
            <?php endif; ?>
        </div>
    </div> 
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"])');

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

</main> 
</body>
</html>