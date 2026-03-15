<?php 
// 1. On inclut le header qui gère déjà le session_start() et la structure HTML
include 'includes/header.php'; 

// 2. Connexion à la base
require_once 'config/db.php';

// 3. Fonction de formatage
function formatArgent($n) {
    if ($n >= 1000000) return round($n / 1000000, 1) . 'M';
    if ($n >= 1000) return round($n / 1000, 1) . 'k';
    return $n;
}

try {
    $total_membres = $pdo->query("SELECT COUNT(*) FROM membres")->fetchColumn();
    $membres_actifs = $pdo->query("SELECT COUNT(*) FROM membres WHERE statut = 'actif'")->fetchColumn();
    $recette_mois = $pdo->query("SELECT SUM(montant_paye) FROM paiements WHERE MONTH(date_paiement) = MONTH(CURDATE()) AND YEAR(date_paiement) = YEAR(CURDATE())")->fetchColumn() ?: 0;
    $alertes_expiration = $pdo->query("SELECT COUNT(*) FROM souscriptions WHERE date_fin <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND date_fin >= CURDATE()")->fetchColumn();
} catch (Exception $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
        font-family: 'Inter', sans-serif;
    }

    #video-bg {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        z-index: -2;
        object-fit: cover;
        filter: brightness(0.4); 
    }

    /* Overlay pour lisser le rendu */
    .video-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle, rgba(15, 23, 42, 0.2) 0%, rgba(2, 6, 23, 0.7) 100%);
        z-index: -1;
    }

    /* On rend le conteneur principal transparent */
    main { background: transparent !important; }
    
    /* Conservation du design d'origine pour les cartes */
    .stat-card { background: rgba(255, 255, 255, 0.95); transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); }
</style>

<video autoplay muted loop playsinline id="video-bg">
    <source src="assets/videos/background.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div id="main-content" class="space-y-6 md:space-y-8 p-4 md:p-8 rounded-[24px] md:rounded-[40px] border-[8px] border-slate-900 shadow-2xl bg-slate-900/40 backdrop-blur-sm animate__animated animate__fadeInUp">
    
    <div class="flex flex-col md:flex-row justify-between bg-teal-500 rounded-[20px] items-start md:items-center shadow-lg p-5 md:p-6 gap-4">
        <div class="flex items-center space-x-4">
            <div class="bg-white/20 p-2 rounded-xl hidden md:block border border-white/30">
                <img src="assets/images/logogym.png" class="w-10 h-10">
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight uppercaseLeading-none">Dashboard</h2>
                <p class="text-slate-100 text-sm font-medium italic">Salut, <?= htmlspecialchars($_SESSION['utilisateur_nom'] ?? 'Admin') ?> !</p>
            </div>
        </div>
        
        <div class="text-left md:text-right w-full md:w-auto border-t md:border-t-0 border-black/10 pt-3 md:pt-0">
            <p class="text-xs md:text-sm font-bold text-white uppercase tracking-widest"><?= date('d F Y') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="stat-card p-4 md:p-6 rounded-[24px] md:rounded-[30px] shadow-xl flex flex-col md:flex-row items-center md:space-x-4 text-center md:text-left">
            <div class="w-10 h-10 md:w-14 md:h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-2 md:mb-0 shadow-inner">
                <img src="assets/images/members.png" class="w-7 h-7 md:w-10 md:h-10">
            </div>
            <div>
                <p class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-wide">Membres</p>
                <p class="text-xl md:text-2xl font-black text-slate-800 leading-tight"><?= $total_membres ?></p>
            </div>
        </div>

        <div class="stat-card p-4 md:p-6 rounded-[24px] md:rounded-[30px] shadow-xl flex flex-col md:flex-row items-center md:space-x-4 text-center md:text-left">
            <div class="w-10 h-10 md:w-14 md:h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-2 md:mb-0 shadow-inner">
                <img src="assets/images/actif.png" class="w-7 h-7 md:w-10 md:h-10">
            </div>
            <div>
                <p class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-wide">Actifs</p>
                <p class="text-xl md:text-2xl font-black text-teal-600 leading-tight"><?= $membres_actifs ?></p>
            </div>
        </div>

        <div class="stat-card p-4 md:p-6 rounded-[24px] md:rounded-[30px] shadow-xl flex flex-col md:flex-row items-center md:space-x-4 text-center md:text-left">
            <div class="w-10 h-10 md:w-14 md:h-14 bg-emerald-50 rounded-xl flex items-center justify-center mb-2 md:mb-0 shadow-inner">
              <img src="assets/images/bagmoney.png" class="w-5 h-5 md:w-7 md:h-7">
            </div>
            <div>
                <p class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-wide">Recettes</p>
                <p class="text-lg md:text-xl font-black text-slate-800 leading-tight">
                    <?= formatArgent($recette_mois) ?> 
                    <span class="text-[10px] opacity-50">HTG</span>
                </p>
            </div>
        </div>

        <div class="stat-card p-4 md:p-6 rounded-[24px] md:rounded-[30px] shadow-xl flex flex-col md:flex-row items-center md:space-x-4 text-center md:text-left">
            <div class="w-10 h-10 md:w-14 md:h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-2 md:mb-0 shadow-inner">
                <img src="assets/images/warning.png" class="w-7 h-7 md:w-10 md:h-10">
            </div>
            <div>
                <p class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-wide">Alertes</p>
                <p class="text-xl md:text-2xl font-black text-red-600 leading-tight"><?= $alertes_expiration ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <div class="lg:col-span-2 bg-white/95 rounded-[30px] md:rounded-[40px] p-6 md:p-8 shadow-2xl border border-white/10">
            <h3 class="text-lg md:text-xl font-black text-slate-800 mb-6 uppercase tracking-tight">Dernières inscriptions</h3>
            <div class="space-y-3">
                <?php
                $derniers = $pdo->query("SELECT * FROM membres ORDER BY id DESC LIMIT 5")->fetchAll();
                foreach($derniers as $d): ?>
                <div class="flex items-center justify-between p-3 md:p-4 rounded-2xl hover:bg-teal-50 transition-all border border-transparent hover:border-slate-100">
                    <div class="flex items-center space-x-3 md:space-x-4">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-teal-500 rounded-full flex items-center justify-center font-bold text-white text-xs shadow-inner">
                            <?= strtoupper(substr($d['nom'], 0, 1)) ?>
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-slate-800 text-sm md:text-base truncate"><?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']) ?></p>
                            <p class="text-[10px] md:text-xs text-slate-400 truncate italic"><?= htmlspecialchars($d['email']) ?></p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-block text-[10px] font-black px-3 py-1 rounded-full bg-teal-100 text-teal-700">ACTIF</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <a href="ajouter_membre.php" class="flex-1 p-6 md:p-8 bg-teal-500 text-slate-900 rounded-[24px] md:rounded-[30px] shadow-lg hover:bg-teal-400 transition transform hover:-translate-y-2 group">
                <div class="bg-white/30 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center mb-4 border border-white/30">
                    <img src="assets/images/add.png" class="w-6 h-6 md:w-8 md:h-8">
                </div>
                <p class="font-black uppercase tracking-tighter text-sm md:text-base leading-none">Nouveau Membre</p>
            </a>
            <a href="scanner.php" class="flex-1 p-6 md:p-8 bg-slate-900 text-white rounded-[24px] md:rounded-[30px] shadow-lg hover:bg-slate-800 transition transform hover:-translate-y-2 border border-slate-700 group">
                <div class="bg-white/10 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center mb-4 border border-white/20">
                    <img src="assets/images/security.png" class="w-6 h-6 md:w-8 md:h-8">
                </div>
                <p class="font-black uppercase tracking-tighter text-sm md:text-base leading-none">Scanner d'entrée</p>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // On cible le conteneur principal par son ID
    const mainContainer = document.getElementById('main-content');
    
    // On cible tous les liens internes vers d'autres pages
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="tel:"])');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Vérification que c'est bien un lien interne
            if (link.hostname === window.location.hostname) {
                e.preventDefault(); // Bloque le départ immédiat
                const destination = this.href;

                // Lance l'animation de sortie (fadeOut vers le bas)
                mainContainer.classList.remove('animate__fadeInUp');
                mainContainer.classList.add('animate__fadeOutDown');

                // Change de page juste après l'animation (500ms)
                setTimeout(() => {
                    window.location.href = destination;
                }, 500);
            }
        });
    });
});
</script>

</main> </body>
</html>