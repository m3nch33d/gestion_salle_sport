<?php 
require_once 'config/db.php';
include 'includes/header.php'; 

// 1. Récupération des coaches pour le menu déroulant
$coaches = $pdo->query("SELECT id, nom, prenom FROM coaches ORDER BY nom ASC")->fetchAll();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $coach_id = $_POST['coach_id'];
    $jours_array = $_POST['jour']; 
    
    // NOUVO: Nou kole tout jou yo ansanm ak yon slach "/"
    $jours_string = implode('/', $jours_array); 
    
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $activite = $_POST['activite'];
    $capacite = $_POST['capacite'];

    try {
        // Nou pèsonalize ensèsyon an pou sere yon sèl liy olye plizyè
        $stmt = $pdo->prepare("INSERT INTO horaires (coach_id, jour, heure_debut, heure_fin, activite, capacite) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$coach_id, $jours_string, $heure_debut, $heure_fin, $activite, $capacite]);
        
        echo "<script>window.location.href='liste_horaires.php';</script>";
        exit();
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<style>
    body {
        background: linear-gradient(rgba(212, 219, 236, 0.17), rgba(180, 191, 218, 0.16)), 
                    url('assets/images/gymgris.JPEG') no-repeat center center fixed;
        background-size: cover;
    }
    main { background: transparent !important; }
    
    /* Style pou bèl bwat seleksyon an */
    select[multiple] {
        height: 160px;
        scrollbar-width: thin;
        scrollbar-color: #2dd4bf #1e293b;
    }
    select option {
        padding: 12px;
        background: #1e293b;
        color: white;
        margin-bottom: 2px;
        border-radius: 8px;
    }
    select option:checked {
        background: #2dd4bf !important;
        color: #0f172a;
        font-weight: bold;
    }
</style>

<div class="p-4 md:p-8 min-h-screen">
    <div class="max-w-4xl mx-auto mb-6 md:mb-10 bg-slate-800/40 backdrop-blur-md p-6 md:p-8 rounded-[25px] md:rounded-[30px] border border-white/10 shadow-2xl flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter">
                Nouveau <span class="text-teal-400">Planning</span>
            </h1>
            <p class="text-slate-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest">Saisie Groupée </p>
        </div>
        <a href="liste_horaires.php" class="text-teal-400 hover:text-white text-xs font-bold uppercase transition-colors px-4 py-2 border border-teal-500/30 rounded-xl sm:border-none">
            ← Annuler
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white/5 backdrop-blur-xl p-6 md:p-10 rounded-[30px] md:rounded-[40px] border border-white/10 shadow-2xl">
        
        <?php if($message): ?>
            <div class="bg-red-500/20 text-red-400 p-4 rounded-2xl mb-6 border border-red-500/30 text-sm font-bold">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
            
            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Coach Responsable</label>
                <select name="coach_id" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all text-sm">
                    <option value="">-- Choisir le coach --</option>
                    <?php foreach($coaches as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Jours de Disponibilité (Ctrl + Clic)</label>
                <select name="jour[]" multiple required 
                   class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all custom-scrollbar text-sm">
                    <option value="Lundi">Lundi</option>
                    <option value="Mardi">Mardi</option>
                    <option value="Mercredi">Mercredi</option>
                    <option value="Jeudi">Jeudi</option>
                    <option value="Vendredi">Vendredi</option>
                    <option value="Samedi">Samedi</option>
                    <option value="Dimanche">Dimanche</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Activité</label>
                <input type="text" name="activite" placeholder="Ex: Musculation" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all text-sm">
            </div>

            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Capacité Max</label>
                <input type="number" name="capacite" value="15" min="1" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all text-sm">
            </div>

            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Heure Début</label>
                <input type="time" name="heure_debut" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all text-sm">
            </div>

            <div class="space-y-2">
                <label class="block text-teal-400 text-[10px] font-black uppercase ml-4 tracking-[0.2em]">Heure Fin</label>
                <input type="time" name="heure_fin" required 
                    class="w-full bg-slate-900/50 border border-white/10 rounded-2xl p-4 text-white focus:border-teal-500 outline-none transition-all text-sm">
            </div>

            <div class="md:col-span-2 pt-4">
                <button type="submit" 
                    class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 md:py-5 rounded-2xl uppercase tracking-[0.2em] md:tracking-[0.3em] transition-all transform hover:scale-[1.01] shadow-xl shadow-teal-500/20 text-xs md:text-sm">
                    Enregistrer le planning
                </button>
            </div>
        </form>
    </div>
</div>

<?php echo "</main></body></html>"; ?>