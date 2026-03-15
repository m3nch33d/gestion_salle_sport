<?php 
include 'includes/header.php'; 
require_once 'config/db.php'; 

$message = "";
$status = "";

// Logique d'enregistrement d'une entrée
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_membre'])) {
    $id_membre = intval($_POST['id_membre']);
    
    $stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
    $stmt->execute([$id_membre]);
    $membre = $stmt->fetch();

    if ($membre) {
        if ($membre['statut'] == 'actif') {
            $ins = $pdo->prepare("INSERT INTO presences (id_membre, date_presence, heure_entree) VALUES (?, CURDATE(), CURTIME())");
            if ($ins->execute([$id_membre])) {
                $message = "ENTRÉE VALIDÉE : " . $membre['prenom'] . " " . $membre['nom'];
                $status = "success";
            }
        } else {
            $message = "ACCÈS REFUSÉ : Membre inactif ou abonnement expiré.";
            $status = "error";
        }
    } else {
        $message = "ERREUR : ID Membre introuvable.";
        $status = "error";
    }
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        margin: 0;
        background-image: url('assets/images/gymblanc.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        overflow-x: hidden; /* Evite scroll sou kote */
    }

    body::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(5px);
        z-index: 0;
    }

    .scanner-wrapper {
        position: relative;
        z-index: 10;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px; /* Mwens padding sou bò pou mobil */
    }

    .glass-card {
        background: rgba(15, 23, 42, 0.5) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        width: 100%; /* Pran tout lajè a */
    }

    /* Ajisteman pou ti ekran */
    @media (max-width: 640px) {
        .glass-card {
            padding: 30px 20px !important; /* Diminye padding anndan an */
            border-radius: 35px !important; /* Yon ti jan mwens wonn sou mobil */
        }
        .glass-card h2 {
            font-size: 1.5rem !important; /* Tèks pi piti */
        }
        .bg-teal-500\/20 {
            padding: 15px !important;
        }
        .bg-teal-500\/20 img {
            width: 60px !important;
            height: 60px !important;
        }
    }

    .glass-input {
        background: rgba(0, 0, 0, 0.3) !important;
        border: 2px solid rgba(20, 184, 166, 0.2) !important;
        color: white !important;
    }
    
    main { background: transparent !important; }
</style>

<div class="scanner-wrapper">
    <div id="main-content" class="glass-card p-10 rounded-[50px] max-w-lg text-center animate__animated animate__zoomIn">
        
        <div class="mb-8">
            <div class="flex justify-center mb-6">
                <div class="bg-teal-500/20 p-6 rounded-full border border-teal-500/40 shadow-lg shadow-teal-500/20">
                    <img src="assets/images/cadenas.png" class="w-16 h-16 md:w-24 md:h-24 object-contain" alt="Cadenas">
                </div>
            </div>
            
            <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter">Scanner d'Entrée</h2>
            <p class="text-teal-400 font-bold text-sm italic">Système de contrôle </p> 
            <p class="text-white font-bold text-sm opacity-80 uppercase mt-1">Dechouke Grès FITNESS</p>
        </div>

        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-2xl font-bold text-xs md:text-sm animate__animated animate__headShake <?= $status == 'success' ? 'bg-teal-500/20 text-teal-400 border border-teal-500/50' : 'bg-red-500/20 text-red-400 border border-red-500/50' ?>">
                <?= $status == 'success' ? '✅' : '❌' ?> <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="relative">
                <input type="number" name="id_membre" id="id_membre" placeholder="ID membre..." 
                       class="w-full glass-input p-4 md:p-5 rounded-3xl text-center text-base font-black outline-none transition-all focus:border-teal-500"
                       autofocus required>
            </div>
            
            <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 md:py-5 rounded-3xl transition transform hover:scale-[1.02] shadow-lg shadow-teal-500/20">
                VALIDER L'ACCÈS
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-white/10">
             <img src="assets/images/historicity.png" class="w-6 h-6 md:w-8 md:h-8 inline-block mr-2">
            <a href="presences.php" class="text-slate-400 hover:text-teal-400 font-bold text-[10px] md:text-xs uppercase tracking-widest transition">
                Voir l'historique des entrées
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    const inputField = document.getElementById('id_membre');

    // Garde le focus sur l'input même après un clic ailleurs (pour le scanner)
    document.addEventListener('click', () => inputField.focus());

    // Animation de sortie vers le bas sur les liens
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"])');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (link.hostname === window.location.hostname) {
                e.preventDefault();
                const destination = this.href;
                
                container.classList.remove('animate__zoomIn');
                container.classList.add('animate__fadeOutDown');
                
                setTimeout(() => {
                    window.location.href = destination;
                }, 500);
            }
        });
    });
});
</script>

</main>
</body>
</html>