<?php 
// 1. Nou soti nan dosye sa a pou n al chèche config ak header
require_once '../config/db.php';
include '../includes/header.php'; 

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $specialite = $_POST['specialite']; 
    $telephone = $_POST['telephone'];

    try {
        $stmt = $pdo->prepare("INSERT INTO coaches (nom, prenom, specialite, telephone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $specialite, $telephone]);
        // Redireksyon apre siksè
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
                    url('../assets/images/coachgym.png') no-repeat center center fixed;
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
                Nouveau <span class="text-teal-400">Coach</span>
            </h1>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Enregistrement Spécialité (#6)</p>
        </div>
        <a href="liste_coaches.php" class="text-slate-400 hover:text-white text-xs font-bold uppercase transition-colors">
            ← Retour à la liste
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
                <input type="text" name="nom" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all placeholder:text-slate-600">
            </div>

            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Prénom</label>
                <input type="text" name="prenom" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all">
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Spécialité (Egzijans #6)</label>
                <input type="text" name="specialite" placeholder="Ex: Musculation, Boxe, Yoga..." required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all">
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Numéro de Téléphone</label>
                <input type="text" name="telephone" 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all">
            </div>

            <div class="md:col-span-2 pt-4">
                <button type="submit" 
                    class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-5 rounded-2xl uppercase tracking-[0.3em] transition-all transform hover:scale-[1.01] shadow-xl shadow-teal-500/20">
                    Enregistrer l'Entraîneur
                </button>
            </div>
        </form>
    </div>
</div>

<?php echo "</main></body></html>"; ?>