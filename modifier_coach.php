<?php 
require_once 'config/db.php';
include 'includes/header.php'; 

$message = "";
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: liste_coaches.php");
    exit();
}

// 1. Rekipere done ansyen coach la
$stmt = $pdo->prepare("SELECT * FROM coaches WHERE id = ?");
$stmt->execute([$id]);
$coach = $stmt->fetch();

if (!$coach) {
    header("Location: liste_coaches.php");
    exit();
}

// 2. Tretman modifikasyon an
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $specialite = $_POST['specialite']; 
    $telephone = $_POST['telephone'];

    try {
        $update = $pdo->prepare("UPDATE coaches SET nom = ?, prenom = ?, specialite = ?, telephone = ? WHERE id = ?");
        $update->execute([$nom, $prenom, $specialite, $telephone, $id]);
        echo "<script>window.location.href='liste_coaches.php';</script>";
        exit();
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<style>
    body {
        background: linear-gradient(rgba(226, 228, 231, 0.2), rgba(235, 236, 240, 0.25)), 
                    url('assets/images/coachgym.png') no-repeat center center fixed;
        background-size: cover;
    }
    /* Sa a asire ke background blan ki nan main an pa kouvri imaj la */
    main {
        background: transparent !important;
    }
</style>

<div class="p-4 md:p-8">
    <div class="max-w-4xl mx-auto mb-10 bg-slate-800/40 backdrop-blur-md p-8 rounded-[30px] border border-white/10 shadow-2xl flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-white uppercase tracking-tighter">
                Modifier <span class="text-teal-400">Coach</span>
            </h1>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Mise à jour Spécialité </p>
        </div>
        <a href="liste_coaches.php" class="text-slate-400 hover:text-white text-xs font-bold uppercase transition-colors">
            ← Annuler
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white/5 backdrop-blur-xl p-10 rounded-[40px] border border-white/10 shadow-2xl">
        
        <?php if($message): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-2xl mb-6 border border-red-500/30 text-sm font-bold">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Nom de l'entraîneur</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($coach['nom']) ?>" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Prénom</label>
                <input type="text" name="prenom" value="<?= htmlspecialchars($coach['prenom']) ?>" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all">
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Spécialité</label>
                <input type="text" name="specialite" value="<?= htmlspecialchars($coach['specialite']) ?>" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all">
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Numéro de Téléphone</label>
                <input type="text" name="telephone" value="<?= htmlspecialchars($coach['telephone']) ?>" 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all">
            </div>

            <div class="md:col-span-2 pt-4">
                <button type="submit" 
                    class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-5 rounded-2xl uppercase tracking-[0.3em] transition-all transform hover:scale-[1.01] shadow-xl shadow-teal-500/20">
                    Mettre à jour les informations
                </button>
            </div>
        </form>
    </div>
</div>

<?php echo "</main></body></html>"; ?>