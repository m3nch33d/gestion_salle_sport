<?php 
require_once 'config/db.php';
include 'includes/header.php'; 


$query = "SELECT h.*, c.nom, c.prenom FROM horaires h 
          JOIN coaches c ON h.coach_id = c.id ORDER BY h.id DESC";
$horaires = $pdo->query($query)->fetchAll();

function koutJou($str) {
    if(!$str) return "";
    $jours = explode('/', $str);
    $rezilta = [];
    foreach($jours as $j) {
        $rezilta[] = strtoupper(substr(trim($j), 0, 3));
    }
    return implode('/', $rezilta);
}
?>



<link rel="stylesheet" href="public/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        background: linear-gradient(rgba(206, 211, 223, 0.43), rgba(198, 203, 214, 0.4)), 
                    url('assets/images/back.JPEG') no-repeat center center fixed;
        background-size: cover; 
        overflow-x: hidden;
    }
    main { background: transparent !important; }

    /* Glassmorphism Header */
    .glass-header {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 35px;
        padding: 25px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .glass-title h1 {
        color: #2dd4bf; 
        font-size: 28px;
        font-weight: 900;
        text-transform: uppercase;
        margin: 0;
    }
    
    .glass-title p { color: #475569; font-size: 13px; font-weight: 500; }

    .btn-new {
        background: rgba(45, 212, 191, 0.2);
        border: 2px solid #2dd4bf;
        color: #0d9488;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
    }
    .btn-new:hover { background: #2dd4bf !important; color: white !important; transform: scale(1.05); }

    .white-card {
        background: white;
        border-radius: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .grid-layout {
        display: grid;
        grid-template-columns: 1.5fr 1.5fr 2fr 1.2fr 1fr;
        align-items: center;
        padding: 20px 45px;
    }

    .table-header {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        border-bottom: 1px solid #f1f5f9;
    }

    .status-pill { 
        background: #002D62; 
        color: white; 
        padding: 8px 18px; 
        border-radius: 10px; 
        font-size: 12px; 
        font-weight: 800; 
        display: inline-block;
    }

    .line-teal {
        position: absolute;
        left: 0;
        width: 5px;
        height: 50%;
        background: #2dd4bf;
        border-radius: 0 5px 5px 0;
    }
</style>

<div id="main-content" class="p-4  md:p-10 animate__animated animate__fadeInUp">   
    <div class="glass-header">
        <div class="glass-title">
            <h1>GESTION DES HORAIRES</h1>
            <p>Planification des séances et gestion des coachs</p>
        </div>
        <a href="ajouter_horaire.php" class="btn-new">
            <span><img src="assets/images/add.png" class="w-10 h-10"></span> Nouvel Horaire
        </a>
    </div>

    <div class="white-card ">
        <div class="grid-layout table-header shadow-xl">
            <div>Jour</div>
            <div class="text-center">Heure</div>
            <div>Activité / Coach</div>
            <div class="text-center">Capacité</div>
            <div class="text-right">Actions</div>
        </div>

        <div class="divide-y divide-slate-50">
            <?php foreach($horaires as $h): ?>
            <div class="grid-layout relative hover:bg-teal-100/70 transition-colors duration-500 ease-in-out">
                <div class="line-teal opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div>
                    <span class="status-pill"><?= koutJou($h['jour']) ?></span>
                </div>
                
                <div class="text-center text-teal-500 font-black text-[15px] tracking-tight">
                    <?= date('H:i', strtotime($h['heure_debut'])) ?> - <?= date('H:i', strtotime($h['heure_fin'])) ?>
                </div>
                
                <div>
                    <div class="font-black text-slate-800 uppercase text-sm leading-none"><?= htmlspecialchars($h['activite']) ?></div>
                    <div class="text-[10px] text-slate-400 italic mt-1">Coach: <?= htmlspecialchars($h['prenom'] . ' ' . $h['nom']) ?></div>
                </div>
                
                <div class="text-center">
                    <span class="font-black text-slate-800 text-lg"><?= $h['capacite'] ?></span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase ml-1">Places</span>
                </div>
                
                <div class="text-right">
                    <a href="supprimer_horaire.php?id=<?= $h['id'] ?>" 
                       onclick="return confirmerAction(event, this.href)"
                       class="bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                        Retirer
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
// Fonction pour confirmer et animer la sortie
function confirmerAction(e, url) {
    e.preventDefault();
    if (confirm('Voulez-vous supprimer cet horaire ?')) {
        const container = document.getElementById('main-content');
        container.classList.remove('animate__fadeInUp');
        container.classList.add('animate__fadeOutDown');
        setTimeout(() => { window.location.href = url; }, 500);
    }
    return false;
}

// Transition de sortie automatique sur les liens
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