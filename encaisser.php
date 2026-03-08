<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

<<<<<<< HEAD
// 1. Récupérer les souscriptions qui n'ont pas encore été payées (ou pour un nouveau mois)
// On joint les membres et les abonnements pour avoir un affichage clair
=======
// 1. Récupérer les souscriptions
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
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
        
<<<<<<< HEAD
        echo "<div class='bg-green-500 text-white p-4 text-center'>Paiement enregistré avec succès !</div>";
        echo "<script>setTimeout(() => { window.location.href='paiements.php'; }, 1500);</script>";
    } catch (PDOException $e) {
        echo "<div class='bg-red-500 text-white p-4'>Erreur : " . $e->getMessage() . "</div>";
=======
        echo "<div class='fixed top-5 left-1/2 transform -translate-x-1/2 z-50 bg-emerald-500 text-white px-8 py-4 rounded-2xl shadow-2xl font-bold'>Paiement enregistré avec succès !</div>";
        echo "<script>setTimeout(() => { window.location.href='paiements.php'; }, 1500);</script>";
    } catch (PDOException $e) {
        echo "<div class='bg-red-500 text-white p-4 relative z-50'>Erreur : " . $e->getMessage() . "</div>";
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
    }
}
?>

<<<<<<< HEAD
<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="妥9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Encaisser un Paiement</h2>
        </div>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Adhérent & Contrat</label>
                <select name="id_souscription" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none appearance-none">
                    <option value="">-- Choisir la vente à encaisser --</option>
                    <?php foreach($souscriptions as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['nom'] . ' ' . $s['prenom']) ?> - <?= htmlspecialchars($s['libelle']) ?> (<?= $s['prix'] ?> HTG)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Montant reçu (HTG)</label>
                <input type="number" step="0.01" name="montant" placeholder="Ex: 1500" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Méthode de paiement</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="methode" value="cash" checked class="text-green-600">
                        <span class="ml-2">Cash</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="methode" value="moncash" class="text-green-600">
                        <span class="ml-2">MonCash</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition duration-300 shadow-lg transform hover:-translate-y-1">
                Confirmer le Paiement
            </button>
        </form>
    </div>
</div>
=======
<style>
    body {
        background: url('assets/images/background.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }

    /* Effet de flou sur le fond pour faire ressortir la fenêtre */
    .glass-container {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(25px) saturate(160%);
        -webkit-backdrop-filter: blur(25px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 35px;
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: white !important;
        transition: all 0.3s ease;
    }

    .glass-input:focus {
        background: rgba(255, 255, 255, 0.2) !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
    }

    /* Style pour les options du select */
    select option {
        background: #1e293b;
        color: white;
    }

    .payment-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .payment-card:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
    }

    input[type="radio"]:checked + span {
        color: #10b981;
        font-weight: 800;
    }

    .payment-card {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
/* Animation quand on clique */
.payment-card:active {
    transform: scale(0.95);
}
</style>

<div class="container mx-auto px-4 py-12 relative z-10">
    <div class="max-w-xl mx-auto glass-container p-10 shadow-2xl">
        
        <div class="flex items-center mb-10">
            <div class="bg-emerald-500/20 p-4 rounded-2xl mr-5">
                <span class=""> <img src="assets/images/bagmoney.png" class="w-8 h-8"> </span> 
            </div>
            <div>
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Encaisser</h2>
                <p class="text-emerald-100/60 text-xs font-bold uppercase tracking-widest">Transaction sécurisée</p>
            </div>
        </div>

        <form action="" method="POST" class="space-y-8">
    <div>
        <label class="block text-xs font-black text-teal-500 uppercase mb-3 tracking-widest">Adhérent & Contrat</label>
        <select name="id_souscription" required class="glass-input w-full px-5 py-4 rounded-2xl outline-none appearance-none cursor-pointer relative z-20">
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
        <input type="number" step="0.01" name="montant" placeholder="0.00" required class="glass-input w-full px-5 py-4 rounded-2xl text-2xl font-bold outline-none relative z-20">
    </div>

    <div>
        <label class="block text-xs font-black text-teal-500 uppercase mb-4 tracking-widest">Mode de Règlement</label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <div class="relative">
                <input type="radio" name="methode" value="cash" id="pay_cash" checked class="peer hidden">
                <label for="pay_cash" class="flex flex-col items-center justify-center p-4 rounded-2xl cursor-pointer text-white border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-teal-300 peer-checked:bg-teal-500/20 peer-checked:ring-2 peer-checked:ring-teal-500/50">
                    <span class="text-2xl mb-1"><img src="assets/images/1000HTG.png" class="w-10 h-10"></span>
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Cash</span>
                </label>
            </div>

            <div class="relative">
                <input type="radio" name="methode" value="moncash" id="pay_moncash" class="peer hidden">
                <label for="pay_moncash" class="flex flex-col items-center justify-center p-4 rounded-2xl cursor-pointer text-white border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-teal-300 peer-checked:bg-teal-500/20 peer-checked:ring-2 peer-checked:ring-teal-500/50">
                    <span class="text-2xl mb-1"><img src="assets/images/Moncash.png" class="w-10 h-10"></span>
                    <span class="text-[10px] font-bold uppercase tracking-tighter">MonCash</span>
                </label>
            </div>

            <div class="relative">
                <input type="radio" name="methode" value="natcash" id="pay_natcash" class="peer hidden">
                <label for="pay_natcash" class="flex flex-col items-center justify-center p-4 rounded-2xl cursor-pointer text-white border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-teal-300 peer-checked:bg-teal-500/20 peer-checked:ring-2 peer-checked:ring-teal-500/50">
                    <span class="text-2xl mb-1"><img src="assets/images/Natcash.png" class="w-10 h-10"></span>
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Natcash</span>
                </label>
            </div>

        </div>
    </div>

    <div class="pt-4">
        <button type="submit" class="w-full bg-teal-500 hover:bg-teal-300 text-slate-900 font-black py-5 rounded-2xl transition-all duration-300 shadow-2xl shadow-emerald-500/30 transform hover:-translate-y-1 uppercase tracking-widest text-sm relative z-30">
            Confirmer l'encaissement
        </button>
    </div>
</form>
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
