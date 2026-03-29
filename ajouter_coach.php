<?php 
require_once 'config/db.php';
require_once 'includes/securite.php';
include 'includes/header.php'; 

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $specialite = $_POST['specialite']; 
    $telephone = $_POST['telephone'];

    try {
        $stmt = $pdo->prepare("INSERT INTO coaches (nom, prenom, specialite, telephone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $specialite, $telephone]);
        echo "<script>window.location.href='liste_coaches.php';</script>";
        exit();
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<style>
    body {
        margin: 0;
        background: linear-gradient(rgba(226, 228, 231, 0.2), rgba(235, 236, 240, 0.25)), 
                    url('assets/images/coachgym.png') no-repeat center center fixed;
        background-size: cover;
    }
    main { background: transparent !important; }

    /* Glassmorphism Effect */
    .glass-box {
        background: rgba(15, 23, 42, 0.6) !important; /* Slate-900 transparent */
        backdrop-filter: blur(20px) saturate(160%);
        -webkit-backdrop-filter: blur(20px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .glass-input {
        background: rgba(15, 23, 42, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        transition: all 0.3s ease;
    }

    .glass-input:focus {
        border-color: #14b8a6 !important; /* teal-500 */
        box-shadow: 0 0 15px rgba(20, 184, 166, 0.2);
        outline: none;
    }
</style>

<div class="p-4 md:p-8 animate__animated animate__fadeIn">
    
    <div class="max-w-4xl mx-auto mb-6 md:mb-10 glass-box p-6 md:p-8 rounded-[25px] md:rounded-[30px] flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter">
                Nouveau <span class="text-teal-400">Coach</span>
            </h1>
            <p class="text-slate-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest">Enregistrement Spécialité (#6)</p>
        </div>
        <a href="liste_coaches.php" class="text-slate-400 hover:text-white text-[10px] md:text-xs font-bold uppercase transition-colors border-b border-transparent hover:border-white">
            ← Retour à la liste
        </a>
    </div>

    <div class="max-w-4xl mx-auto glass-box p-6 md:p-10 rounded-[30px] md:rounded-[40px] shadow-2xl">
        
        <?php if($message): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-2xl mb-6 border border-red-500/30 text-sm font-bold">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-2 md:ml-4 tracking-[0.2em]">Nom</label>
                <input type="text" name="nom" required 
                    class="w-full glass-input rounded-2xl p-4 text-white placeholder:text-slate-600">
            </div>

            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-2 md:ml-4 tracking-[0.2em]">Prénom</label>
                <input type="text" name="prenom" required 
                    class="w-full glass-input rounded-2xl p-4 text-white">
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-2 md:ml-4 tracking-[0.2em]">Spécialité(s)</label>
                <input type="text" name="specialite" placeholder="Ex: Musculation, Boxe, Yoga..." required 
                    class="w-full glass-input rounded-2xl p-4 text-white">
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-2 md:ml-4 tracking-[0.2em]">Téléphone</label>
                <input type="text" name="telephone" 
                    class="w-full glass-input rounded-2xl p-4 text-white">
            </div>

            <div class="md:col-span-2 pt-4">
                <button type="submit" 
                    class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 md:py-5 rounded-2xl uppercase tracking-[0.2em] md:tracking-[0.3em] transition-all transform hover:scale-[1.01] shadow-xl shadow-teal-500/20 text-sm md:text-base">
                    Enregistrer l'Entraîneur
                </button>
            </div>
        </form>
    </div>
</div>

<?php echo "</main></body></html>"; ?>