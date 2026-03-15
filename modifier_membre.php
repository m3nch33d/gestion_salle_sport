<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $tel = $_POST['telephone'];
    $statut = $_POST['statut'];

    $sql = "UPDATE membres SET nom=?, prenom=?, telephone=?, statut=? WHERE id=?";
    $pdo->prepare($sql)->execute([$nom, $prenom, $tel, $statut, $id]);
    
    echo "<script>window.location.href='membres.php';</script>";
}
?>

<style>
    body {
        margin: 0;
        background: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070') no-repeat center center fixed;
        background-size: cover;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.9) 100%);
        z-index: -1;
    }

    .glass-card {
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 40px; 
        box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.6);
    }

    .input-glass {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .input-glass:focus {
        border-color: #2dd4bf !important;
        background: rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 0 15px rgba(45, 212, 191, 0.3);
    }

    .neon-text {
        color: #2dd4bf !important;
        text-shadow: 0 0 15px rgba(45, 212, 191, 0.5);
    }

    label {
        color: #2dd4bf !important;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        margin-left: 4px;
    }

    /* Ajustement responsive du titre pour éviter les débordements */
    @media (max-width: 640px) {
        .title-responsive { font-size: 2.25rem !important; }
        .glass-card { padding: 2rem !important; border-radius: 30px; }
    }
</style>

<div class="container mx-auto px-4 py-8 md:py-12 relative z-10">
    
    <div class="mb-6 md:mb-10 text-center">
        <span class="text-teal-400 font-bold uppercase tracking-[0.3em] text-[10px]">Profil du Membre</span>
        <h1 class="title-responsive text-4xl md:text-5xl font-black tracking-tighter text-white mt-2 uppercase italic leading-none">
            Modifier <span class="neon-text">Membre</span>
        </h1>
    </div>

    <div class="max-w-2xl mx-auto glass-card p-8 md:p-14">
        <form method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($m['nom']) ?>" required 
                           class="w-full input-glass px-5 py-3 font-bold outline-none">
                </div>

                <div class="space-y-2">
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($m['prenom']) ?>" required 
                           class="w-full input-glass px-5 py-3 font-bold outline-none">
                </div>
            </div>

            <div class="space-y-2">
                <label>Numéro de Téléphone</label>
                <input type="text" name="telephone" value="<?= htmlspecialchars($m['telephone']) ?>" required 
                       class="w-full input-glass px-5 py-3 font-bold outline-none">
            </div>

            <div class="space-y-2">
                <label>Statut de l'abonnement</label>
                <select name="statut" class="w-full input-glass px-5 py-3 font-bold outline-none appearance-none cursor-pointer">
                    <option value="actif" <?= $m['statut'] == 'actif' ? 'selected' : '' ?> class="bg-slate-900">Actif</option>
                    <option value="inactif" <?= $m['statut'] == 'inactif' ? 'selected' : '' ?> class="bg-slate-900">Inactif</option>
                </select>
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 rounded-2xl transition-all shadow-xl shadow-teal-500/20 uppercase tracking-widest text-sm">
                    Sauvegarder
                </button>
                <a href="membres.php" class="w-full bg-white/5 hover:bg-white/10 text-white border border-white/10 font-bold py-4 rounded-2xl transition-all text-center uppercase tracking-widest text-sm">
                    Retour
                </a>
            </div>
        </form>
    </div>
</div>