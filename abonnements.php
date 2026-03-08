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

<style>
    /* Arrière-plan global sans flou sur toute la page */
    body {
        margin: 0;
        background-image: url('assets/images/gymgris.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    /* 1. Effet Glassmorphism UNIQUEMENT pour le bloc titre */
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
    /* Couleur teal très pâle (15% d'opacité seulement) */
    background: rgba(20, 184, 166, 0.15) !important; 
    
    /* Augmentation du flou pour l'effet "dépoli" */
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    
    /* Bordure blanche subtile pour l'effet reflet du verre */
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
    
    /* Texte teal vif pour qu'il reste lisible sur le fond clair */
    color: #14b8a6 !important; 
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-btn-teal:hover {
    background: rgba(20, 184, 166, 0.25) !important;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border-color: rgba(255, 255, 255, 0.6) !important;
}
</style>

<div class="space-y-8 p-6 relative z-10">
    
    <div class="flex justify-between items-center">
        <div class="glass-title">
            <h2 class="text-3xl font-black text-teal-300 tracking-tight uppercase">Suivi des Abonnements</h2>
            <p class="text-slate-600 font-medium">Historique des paiements et validité des accès</p>
        </div>

        <a href="ajouter_abonnement.php" class="glass-btn-teal text-white px-8 py-4 rounded-3xl font-black shadow-lg flex items-center transform">
            <span class="mr-3"> <img src="assets/images/cc.png" class="w-8 h-8"> </span> 
            Nouvel Abonnement
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-xl border border-slate-200 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest shadow-xl">
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
                     <tr class="hover:bg-teal-100/70 transition-colors duration-700 ease-in-out">
                        <td class="px-6 py-5">
                            <p class="text-slate-900 font-bold"><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></p>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-600">
                            <?= date('d/m/Y', strtotime($a['date_debut'])) ?>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-600 font-bold">
                            <?= date('d/m/Y', strtotime($a['date_fin'])) ?>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full <?= $expire ? 'text-red-700 bg-red-100' : 'text-emerald-700 bg-emerald-100' ?>">
                                <?= $expire ? 'Expiré' : 'Valide' ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    

    <div class="mt-12 space-y-6">
        <div class="glass-title border-l-8 border-orange-400">
           <h3 class="flex items-center gap-3 text-2xl font-black text-white uppercase tracking-tighter">
             <img src="assets/images/bell.png" class="w-10 h-10 object-contain">
             <span>Alertes Relances (J-7)</span>
           </h3>
           <p class="text-slate-300 text-sm italic mt-1 ml-9">
        Membres dont l'accès expire dans la semaine à venir
           </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php 
            $alerte_trouvee = false;
            foreach ($abonnements as $a): 
                $date_fin_timestamp = strtotime($a['date_fin']);
                $date_actuelle = time();
                $limite_7_jours = strtotime('+7 days');

                // CONDITION : La date de fin est entre MAINTENANT et dans 7 JOURS
                if ($date_fin_timestamp >= $date_actuelle && $date_fin_timestamp <= $limite_7_jours): 
                    $alerte_trouvee = true;
                    $diff = $date_fin_timestamp - $date_actuelle;
                    $jours_restants = ceil($diff / (60 * 60 * 24));
            ?>
                <div class="glass-title bg-white/5 border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-orange-500/20 text-orange-400 text-[9px] font-black uppercase rounded-lg border border-orange-500/30">
                            Attention
                        </span>
                        <span class="text-white font-mono text-xs font-bold">
                            J-<?= $jours_restants ?>
                        </span>
                    </div>
                    
                    <h4 class="text-white font-bold text-lg uppercase"><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></h4>
                    
                    <p class="text-slate-400 text-xs mt-3">
                        Date d'expiration : <br>
                        <span class="text-teal-300 font-black text-sm"><?= date('d/m/y', $date_fin_timestamp) ?></span>
                    </p>

                    <div class="mt-5 pt-3 border-t border-white/5 text-right">
                        <span class="text-[9px] font-black text-slate-500 uppercase italic">Préparez le renouvellement</span>
                    </div>
                </div>
            <?php 
                endif;
            endforeach; 

            if (!$alerte_trouvee): ?>
                <div class="col-span-full p-10 text-center glass-title opacity-50">
                    <p class="text-teal-300 italic text-sm">Aucune expiration prévue dans les 7 prochains jours.</p>
                </div>
            <?php endif; ?>
        </div>
    </div> 


</div>

</main> 
</body>
</html>