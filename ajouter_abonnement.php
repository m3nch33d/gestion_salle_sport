<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';
require_once 'includes/securite.php'; 
include 'includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libelle = $_POST['libelle'];
    $duree = $_POST['duree_mois'];
    $prix = $_POST['prix'];
    $avantages = $_POST['avantages'];

    try {
        $sql = "INSERT INTO abonnements (libelle, duree_mois, prix, avantages) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$libelle, $duree, $prix, $avantages]);
        
        echo "<div class='fixed top-5 left-1/2 transform -translate-x-1/2 z-50 bg-teal-500 text-white p-4 rounded-xl shadow-lg'>Succès ! Redirection en cours...</div>";
        echo "<script>setTimeout(() => { window.location.href='abonnements.php'; }, 1500);</script>";

    } catch (PDOException $e) {
        die("<div class='bg-red-600 text-white p-8'><h1>Erreur SQL :</h1>" . $e->getMessage() . "</div>");
    }
}
?>

<style>
    body {
        margin: 0;
        background-image: url('assets/images/gymteal.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* La fenêtre en verre */
    .glass-container {
        background: rgba(255, 255, 255, 0.1) !important; /* Très transparent */
        backdrop-filter: blur(25px) saturate(150%);
        -webkit-backdrop-filter: blur(25px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    /* Style des inputs version verre */
    .glass-input {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    
    .glass-input:focus {
        border-color: #5eead4 !important; /* teal-300 */
        outline: none;
        background: rgba(255, 255, 255, 0.1) !important;
    }

    /* Labels et titres en teal-300 */
    .text-teal-custom {
        color: #5eead4 !important; /* teal-300 exact */
    }

    /* Bouton enregistrer */
    .btn-teal-glass {
        background-color: #5eead4 !important; /* teal-300 */
        color: #0f172a !important; /* texte sombre pour contraste */
        transition: all 0.3s ease;
    }

    .btn-teal-glass:hover {
        background-color: #2dd4bf !important; /* teal-400 */
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(94, 234, 212, 0.4);
    }
</style>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-lg mx-auto glass-container p-10 rounded-[40px] animate__animated animate__fadeIn">
        
        <h2 class="text-3xl font-black text-white mb-8 uppercase tracking-tighter shadow-xl">Nouvel Abonnement</h2>
        
        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-teal-custom uppercase tracking-widest mb-2">Nom de l'offre</label>
                <input type="text" name="libelle" placeholder="Ex: Premium Gold" required 
                       class="w-full glass-input px-5 py-4 rounded-2xl transition-all">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-teal-custom uppercase tracking-widest mb-2">Durée (mois)</label>
                    <input type="number" name="duree_mois" placeholder="12" required 
                           class="w-full glass-input px-5 py-4 rounded-2xl transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-teal-custom uppercase tracking-widest mb-2">Prix ($)</label>
                    <input type="number" name="prix" placeholder="49" required 
                           class="w-full glass-input px-5 py-4 rounded-2xl transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-teal-custom uppercase tracking-widest mb-2">Avantages</label>
                <textarea name="avantages" rows="4" placeholder="Accès 24/7, Sauna, Coach..." 
                          class="w-full glass-input px-5 py-4 rounded-2xl transition-all"></textarea>
            </div>

            <button type="submit" class="w-full btn-teal-glass font-black py-4 rounded-2xl uppercase tracking-widest text-sm shadow-xl mt-4">
                Enregistrer l'offre
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="abonnements.php" class="text-white/50 hover:text-teal-300 text-xs font-bold uppercase tracking-widest transition">
                ← Annuler et revenir
            </a>
        </div>
    </div>
</div>

</main>
</body>
</html>