<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// Requête pour récupérer tous les paiements avec les noms des membres
$sql = "SELECT p.*, m.nom, m.prenom, a.libelle 
        FROM paiements p
        JOIN souscriptions s ON p.id_souscription = s.id
        JOIN membres m ON s.id_membre = m.id
        JOIN abonnements a ON s.id_abonnement = a.id
        ORDER BY p.date_paiement DESC";
$query = $pdo->query($sql);
$paiements = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        background: url('assets/images/gymgris.jpeg') no-repeat center center fixed; 
        background-size: cover;
        min-height: 100vh;
        margin: 0;
    }

    .glass-header {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 20px;
    }

    .glass-total {
        background: rgba(16, 185, 129, 0.2) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
    }

    .table-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 24px;
        overflow: hidden;
    }

    /* Styles spécifiques pour les badges de paiement */
    .badge-moncash {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: 1px solid #fecaca !important;
    }
    
    .badge-cash {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
    }

    /* Ajustements Mobile */
    @media (max-width: 768px) {
        .glass-header { padding: 1rem !important; text-align: center; }
        .glass-total { align-items: center !important; width: 100%; }
        h1 { font-size: 1.5rem !important; }
    }
</style>

<div id="main-content" class="container mx-auto px-4 py-8 md:py-12 relative z-10 animate__animated animate__fadeInUp">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 md:mb-10 gap-6">
        <div class="glass-header px-6 md:px-8 py-5 md:py-6 w-full md:w-auto">
            <h1 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter italic">
                Historique des Paiements
            </h1>
            <p class="text-teal-300 text-[10px] md:text-xs font-bold uppercase tracking-widest mt-1">Registre des encaissements</p>
        </div>

        <div class="glass-total text-emerald-400 px-8 py-5 rounded-2xl shadow-2xl flex flex-col items-center md:items-end w-full md:w-auto">
            <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-emerald-200/60 mb-1">Recette Totale</span>
            <span class="text-2xl md:text-3xl text-slate-900 font-black">
                <?php 
                    $total = array_sum(array_column($paiements, 'montant_paye'));
                    echo number_format($total, 2, '.', ' ') . " <span class='text-sm'>HTG</span>";
                ?>
            </span>
        </div>
    </div>

    <div class="table-container shadow-2xl border border-white/20">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-100/80 border-b border-slate-200">
                    <tr class="text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-4 md:px-6 py-5 text-center">Date</th>
                        <th class="px-4 md:px-6 py-5">Membre</th>
                        <th class="px-4 md:px-6 py-5">Forfait</th>
                        <th class="px-4 md:px-6 py-5">Montant</th>
                        <th class="px-4 md:px-6 py-5 text-center">Méthode</th>
                        <th class="px-4 md:px-6 py-5 text-center">Action</th> 
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($paiements as $p): ?>
                    <tr class="hover:bg-teal-50/50 transition-colors duration-300">
                        <td class="px-4 md:px-6 py-5 text-[10px] md:text-xs font-bold text-slate-400 text-center">
                            <?= date('d/m/Y', strtotime($p['date_paiement'])) ?>
                            <br><span class="text-[9px] opacity-60 font-medium"><?= date('H:i', strtotime($p['date_paiement'])) ?></span>
                        </td>
                        <td class="px-4 md:px-6 py-5 font-bold text-slate-800 uppercase text-xs md:text-sm italic truncate max-w-[150px]">
                            <?= htmlspecialchars($p['nom'] . ' ' . $p['prenom']) ?>
                        </td>
                        <td class="px-4 md:px-6 py-5 text-slate-600 text-xs md:text-sm font-medium">
                            <?= htmlspecialchars($p['libelle']) ?>
                        </td>
                        <td class="px-4 md:px-6 py-5 font-black text-emerald-600 text-sm md:text-base">
                            <?= number_format($p['montant_paye'], 0, '.', ' ') ?> <span class="text-[10px]">HTG</span>
                        </td>
                        <td class="px-4 md:px-6 py-5 text-center">
                            <?php 
                            $m = strtoupper($p['methode_paiement'] ?? ''); 
                            if(!empty($m)): 
                                $badgeClass = ($m === 'MONCASH') ? 'badge-moncash' : 'badge-cash';
                            ?>
                                <span class="px-2 md:px-3 py-1 text-[9px] md:text-[10px] font-black rounded-lg uppercase <?= $badgeClass ?>">
                                    <?= htmlspecialchars($m) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-red-500 text-[9px] font-bold italic">Erreur</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 md:px-6 py-5 text-center">
                            <a href="recu.php?id=<?= $p['id'] ?>" class="inline-flex items-center justify-center p-2 bg-teal-50/50 hover:bg-teal-600 rounded-lg transition-all group border border-teal-100">
                                <img src="assets/images/recu.png" class="w-4 h-4 md:w-5 md:h-5 object-contain group-hover:brightness-0 group-hover:invert">
                                <span class="ml-2 text-[8px] font-black text-teal-600 group-hover:text-white uppercase hidden sm:inline">Reçu</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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