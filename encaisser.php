<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// 1. Récupérer les souscriptions
$sql_souscriptions = "SELECT s.id, m.nom, m.prenom, a.libelle, a.prix 
                      FROM souscriptions s
                      JOIN membres m ON s.id_membre = m.id
                      JOIN abonnements a ON s.id_abonnement = a.id
                      ORDER BY s.id DESC";
$souscriptions = $pdo->query($sql_souscriptions)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_souscription = $_POST['id_souscription'];
    $montant = $_POST['montant'];
    $methode = $_POST['methode'];

    try {
        $sql = "INSERT INTO paiements (id_souscription, montant_paye, methode_paiement) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_souscription, $montant, $methode]);
        
        echo "<div class='fixed top-5 left-1/2 transform -translate-x-1/2 z-50 bg-emerald-500 text-white px-8 py-4 rounded-2xl shadow-2xl font-bold'>Paiement enregistré avec succès !</div>";
        echo "<script>setTimeout(() => { window.location.href='paiements.php'; }, 1500);</script>";
    } catch (PDOException $e) {
        echo "<div class='bg-red-500 text-white p-4 relative z-50'>Erreur : " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    body {
        background: url('assets/images/background.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .glass-container {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(25px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 35px;
    }
    .glass-input {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: white !important;
    }
    select option { background: #1e293b; color: white; }
</style>

<div class="container mx-auto px-4 py-12 relative z-10">
    <div class="max-w-xl mx-auto glass-container p-10 shadow-2xl">
        
        <div class="flex items-center mb-10">
            <div class="bg-emerald-500/20 p-4 rounded-2xl mr-5">
                <img src="assets/images/bagmoney.png" class="w-8 h-8">
            </div>
            <div>
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Encaisser</h2>
                <p class="text-emerald-100/60 text-xs font-bold uppercase tracking-widest">Transaction sécurisée</p>
            </div>
        </div>

        <form action="" method="POST" class="space-y-8">
            <div>
                <label class="block text-xs font-black text-teal-500 uppercase mb-3 tracking-widest">Adhérent & Contrat</label>
                <select name="id_souscription" required class="glass-input w-full px-5 py-4 rounded-2xl outline-none appearance-none cursor-pointer">
                    <option value="">Sélectionner la vente</option>
                    <?php foreach($souscriptions as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['nom'] . ' ' . $s['prenom']) ?> | <?= htmlspecialchars($s['libelle']) ?> (<?= $s['prix'] ?> HTG)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-teal-500 uppercase mb-3 tracking-widest">Montant à recevoir (HTG)</label>
                <input type="number" step="0.01" name="montant" placeholder="0.00" required class="glass-input w-full px-5 py-4 rounded-2xl text-2xl font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-black text-teal-500 uppercase mb-4 tracking-widest">Mode de Règlement</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="methode" value="CASH" checked class="peer sr-only">
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl text-white border border-white/10 bg-white/5 transition-all peer-checked:border-teal-300 peer-checked:bg-teal-500/20 peer-checked:ring-2 peer-checked:ring-teal-500/50">
                            <img src="assets/images/1000HTG.png" class="w-10 h-10 mb-2 pointer-events-none">
                            <span class="text-[10px] font-bold uppercase">Cash</span>
                        </div>
                    </label>

                    <label class="relative cursor-pointer group">
                        <input type="radio" name="methode" value="MONCASH" class="peer sr-only">
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl text-white border border-white/10 bg-white/5 transition-all peer-checked:border-teal-300 peer-checked:bg-teal-500/20 peer-checked:ring-2 peer-checked:ring-teal-500/50">
                            <img src="assets/images/Moncash.png" class="w-10 h-10 mb-2 pointer-events-none">
                            <span class="text-[10px] font-bold uppercase">MonCash</span>
                        </div>
                    </label>

                </div>
            </div>

            <button type="submit" class="w-full bg-teal-500 hover:bg-teal-300 text-slate-900 font-black py-5 rounded-2xl transition-all duration-300 shadow-2xl uppercase tracking-widest text-sm relative z-30">
                Confirmer l'encaissement
            </button>
        </form>
    </div>
</div>