<?php 
// 1. On inclut le header qui gère déjà le session_start() et la structure HTML
include 'includes/header.php'; 

// 2. Connexion à la base
require_once 'config/db.php';

// 3. Fonction de formatage
function formatArgent($n) {
    if ($n >= 1000) {
        return round($n / 1000, 1) . 'k';
    }
    return $n;
}

try {
    $total_membres = $pdo->query("SELECT COUNT(*) FROM membres")->fetchColumn();
    $membres_actifs = $pdo->query("SELECT COUNT(*) FROM membres WHERE statut = 'actif'")->fetchColumn();
    $recette_mois = $pdo->query("SELECT SUM(montant_paye) FROM paiements WHERE MONTH(date_paiement) = MONTH(CURDATE())")->fetchColumn() ?: 0;
    $alertes_expiration = $pdo->query("SELECT COUNT(*) FROM souscriptions WHERE date_fin <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND date_fin >= CURDATE()")->fetchColumn();
} catch (Exception $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>

<link rel="stylesheet" href="public/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
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
        background: rgba(15, 23, 42, 0.3);
        z-index: -1;
    }

    /* On rend le conteneur principal transparent */
    main { background: transparent !important; }
</style>

<video autoplay muted loop playsinline id="video-bg">
    <source src="assets/videos/background.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div class="space-y-8 p-8 rounded-[30px] border-[8px] border-slate-900 shadow-2xl bg-slate-900/40 backdrop-blur-sm animate__animated animate__fadeInUp">
    
    <div class="flex justify-between border rounded-[20px] bg-teal-500 items-center shadow-lg p-4">
        <div class="flex items-center space-x-4">
            <div class="bg-white/20 p-2 rounded-xl">
                <img src="assets/images/logogym.png" class="w-10 h-10">
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Tableau de Bord</h2>
                <p class="text-slate-100 font-medium italic drop-shadow-sm">Content de vous revoir, <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?> !</p>
            </div>
        </div>
        
        <div class="text-right">
            <p class="text-sm font-bold text-white uppercase tracking-widest"><?= date('d F Y') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/95 p-6 rounded-[30px] shadow-xl border border-white/20 flex items-center space-x-4 transform hover:scale-105 transition duration-300">
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center"><img src="assets/images/members.png" class="w-10 h-10"></div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase">Membres</p>
                <p class="text-2xl font-black text-slate-800"><?= $total_membres ?></p>
            </div>
        </div>

        <div class="bg-white/95 p-6 rounded-[30px] shadow-xl border border-white/20 flex items-center space-x-4 transform hover:scale-105 transition duration-300">
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center"><img src="assets/images/actif.png" class="w-10 h-10"></div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase">Actifs</p>
                <p class="text-2xl font-black text-slate-800"><?= $membres_actifs ?></p>
            </div>
        </div>

        <div class="bg-white/95 p-6 rounded-[30px] shadow-xl border border-white/20 flex items-center space-x-4 transform hover:scale-105 transition duration-300">
            <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center">
              <img src="assets/images/bagmoney.png" class="w-6 h-6">
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Recettes</p>
                <p class="text-xl font-black text-slate-800">
                    <?= formatArgent($recette_mois) ?> 
                    <span class="text-[10px] opacity-50">HTG</span>
                </p>
            </div>
        </div>

        <div class="bg-white/95 p-6 rounded-[30px] shadow-xl border border-white/20 flex items-center space-x-4 transform hover:scale-105 transition duration-300">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center"><img src="assets/images/warning.png" class="w-10 h-10"></div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase">Alertes</p>
                <p class="text-2xl font-black text-slate-800"><?= $alertes_expiration ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white/95 rounded-[40px] p-8 shadow-2xl border border-white/20">
            <h3 class="text-xl font-black text-slate-800 mb-6 uppercase">Dernières inscriptions</h3>
            <div class="space-y-4">
                <?php
                $derniers = $pdo->query("SELECT * FROM membres ORDER BY id DESC LIMIT 5")->fetchAll();
                foreach($derniers as $d): ?>
                <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-teal-100/70 transition-all duration-300 border border-transparent hover:border-slate-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center font-bold text-white shadow-inner">
                            <?= strtoupper(substr($d['nom'], 0, 1)) ?>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800"><?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']) ?></p>
                            <p class="text-xs text-slate-400"><?= htmlspecialchars($d['email']) ?></p>
                        </div>
                    </div>
                    <span class="text-xs font-black px-3 py-1 rounded-full bg-teal-100 text-teal-700">ACTIF</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-4">
            <a href="ajouter_membre.php" class="block p-8 bg-teal-500 text-white rounded-[30px] shadow-lg hover:bg-teal-400 transition transform hover:-translate-y-2 group">
                <div class="bg-white/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-12 transition">
                    <img src="assets/images/add.png" class="w-8 h-8">
                </div>
                <p class="font-black uppercase tracking-tighter">Nouveau Membre</p>
            </a>
            <a href="scanner.php" class="block p-8 bg-slate-900 text-white rounded-[30px] shadow-lg hover:bg-slate-800 transition transform hover:-translate-y-2 border border-slate-700 group">
                <div class="bg-white/10 w-12 h-12 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <img src="assets/images/security.png" class="w-8 h-8">
                </div>
                <p class="font-black uppercase tracking-tighter">Scanner d'entrée</p>
            </a>
        </div>
    </div>
</div>

</main> 
<script>
document.addEventListener("DOMContentLoaded", function() {
    const mainContainer = document.querySelector('.animate__animated');

    // On cible tous les liens vers d'autres pages
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"])');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // On vérifie que c'est bien un lien interne
            if (link.hostname === window.location.hostname) {
                e.preventDefault(); // On bloque le départ immédiat
                const destination = this.href;

                // On lance l'animation de sortie
                mainContainer.classList.remove('animate__fadeInUp');
                mainContainer.classList.add('animate__fadeOutDown');

                // On change de page juste après l'animation (500ms environ)
                setTimeout(() => {
                    window.location.href = destination;
                }, 500);
            }
        });
    });
});
</script>
</body> 
</html>