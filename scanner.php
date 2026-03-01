<?php 
include 'includes/header.php'; 
require_once 'config/db.php'; 

$message = "";
$status = "";

// Logique d'enregistrement d'une entrée
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_membre'])) {
    $id_membre = intval($_POST['id_membre']);
    
    // 1. Vérifier si le membre existe et est actif
    $stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
    $stmt->execute([$id_membre]);
    $membre = $stmt->fetch();

    if ($membre) {
        if ($membre['statut'] == 'actif') {
            // 2. Enregistrer la présence
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

<div class="flex flex-col items-center justify-center min-h-[70vh]">
    <div class="bg-slate-900 p-10 rounded-[50px] shadow-2xl border border-teal-500/30 w-full max-w-lg text-center">
        
        <div class="mb-8">
            <div class="text-5xl mb-4">🛡️</div>
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Scanner d'Entrée</h2>
            <p class="text-teal-400 font-bold text-sm italic">Système de contrôle NeuroCode</p>
        </div>

        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-2xl font-bold text-sm <?= $status == 'success' ? 'bg-teal-500/20 text-teal-400 border border-teal-500/50' : 'bg-red-500/20 text-red-400 border border-red-500/50' ?>">
                <?= $status == 'success' ? '✅' : '❌' ?> <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="relative">
                <input type="number" name="id_membre" placeholder="Scanner ou Entrer ID..." 
                       class="w-full bg-slate-800 border-2 border-slate-700 focus:border-teal-500 p-5 rounded-3xl text-center text-2xl font-black text-white outline-none transition-all"
                       autofocus required>
            </div>
            
            <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-5 rounded-3xl transition transform hover:scale-105 shadow-lg shadow-teal-500/20">
                VALIDER L'ACCÈS
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-slate-800">
            <a href="presences.php" class="text-slate-500 hover:text-teal-400 font-bold text-xs uppercase tracking-widest transition">
                📋 Voir l'historique des entrées
            </a>
        </div>
    </div>
</div>

</main>
</body>
</html>