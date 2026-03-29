<?php 
require_once 'config/db.php'; 
require_once 'includes/securite.php';
include 'includes/header.php'; 

// 1. Récupérer les membres et les abonnements
$membres = $pdo->query("SELECT id, nom, prenom FROM membres ORDER BY nom")->fetchAll();
$offres = $pdo->query("SELECT id, libelle, duree_mois FROM abonnements ORDER BY libelle")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_membre = $_POST['id_membre'];
    $id_abonnement = $_POST['id_abonnement'];
    $date_debut = $_POST['date_debut'];

    $stmt = $pdo->prepare("SELECT duree_mois FROM abonnements WHERE id = ?");
    $stmt->execute([$id_abonnement]);
    $duree = $stmt->fetchColumn();

    $date_fin = date('Y-m-d', strtotime("$date_debut + $duree months"));

    try {
        $sql = "INSERT INTO souscriptions (id_membre, id_abonnement, date_debut, date_fin, statut) VALUES (?, ?, ?, ?, 'actif')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_membre, $id_abonnement, $date_debut, $date_fin]);
        
        echo "<script>window.location.href='souscriptions.php';</script>";
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<style>
    /* 1. Mise en place de la vidéo d'arrière-plan */
    #video-bg {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        z-index: -1;
        object-fit: cover;
    }

    /* 2. Overlay sombre pour aider le contraste */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 0;
    }

    /* 3. L'effet Glassmorphism */
    .glass-card {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 30px;
    }

    /* 4. Style des inputs pour qu'ils s'intègrent au design sombre */
    .glass-input {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .glass-input option {
        background: #1f2937; /* Fond sombre pour les options du select */
        color: white;
    }

    label { color: rgb(2, 1, 45) !important; 
    }
</style>

<video autoplay muted loop id="video-bg">
    <source src="assets/videos/background.mp4" type="video/mp4">
</video>
<div class="overlay"></div>

<div class="container mx-auto px-4 py-16 relative z-10">
    <div class="max-w-lg mx-auto glass-card p-10 shadow-2xl">
        <h2 class="text-3xl font-black text-teal-300 mb-8 tracking-tighter uppercase italic">Nouvelle Souscription</h2>
        
        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold mb-2 uppercase tracking-wide">Choisir l'Adhérent</label>
                <select name="id_membre" required class="glass-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-teal-400 outline-none transition">
                    <option value="">Sélectionner un membre</option>
                    <?php foreach($membres as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 uppercase tracking-wide">Choisir le Forfait</label>
                <select name="id_abonnement" required class="glass-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-teal-400 outline-none transition">
                    <option value="">Sélectionner une offre</option>
                    <?php foreach($offres as $o): ?>
                        <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['libelle']) ?> (<?= $o['duree_mois'] ?> mois)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2 uppercase tracking-wide">Date de début</label>
                <input type="date" name="date_debut" value="<?= date('Y-m-d') ?>" required class="glass-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-teal-400 outline-none transition">
            </div>

            <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 rounded-xl transition transform hover:-translate-y-1 shadow-xl uppercase tracking-widest text-sm">
                Valider l'adhésion
            </button>
            
            <div class="text-center mt-6">
                <a href="souscriptions.php" class="text-xs text-white/50 hover:text-white uppercase font-bold tracking-widest transition">Annuler</a>
            </div>
        </form>
    </div>
</div>