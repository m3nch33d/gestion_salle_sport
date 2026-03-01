<?php 
// 1. Activer le débogage AVANT toute chose
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Inclusion des fichiers
require_once 'config/db.php'; 
include 'includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données
    $libelle = $_POST['libelle'];
    $duree = $_POST['duree_mois'];
    $prix = $_POST['prix'];
    $avantages = $_POST['avantages'];

    try {
        // Préparation de la requête
        $sql = "INSERT INTO abonnements (libelle, duree_mois, prix, avantages) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$libelle, $duree, $prix, $avantages]);
        
        // TEST : Si on arrive ici, l'insertion a réussi.
        echo "<div class='bg-green-500 text-white p-4 text-center'>Succès ! Redirection en cours...</div>";
        
        // Redirection forcée en JavaScript (plus fiable si PHP bloque)
        echo "<script>window.location.href='abonnements.php';</script>";
        exit();

    } catch (PDOException $e) {
        // Affiche l'erreur SQL précise
        die("<div class='bg-red-600 text-white p-8'><h1>Erreur SQL :</h1>" . $e->getMessage() . "</div>");
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Nouvel Abonnement</h2>
        
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom de l'offre</label>
                <input type="text" name="libelle" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Durée (mois)</label>
                    <input type="number" name="duree_mois" required class="w-full px-4 py-3 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prix</label>
                    <input type="number" name="prix" required class="w-full px-4 py-3 border rounded-lg">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Avantages</label>
                <textarea name="avantages" rows="3" class="w-full px-4 py-3 border rounded-lg"></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition">
                Enregistrer
            </button>
        </form>
    </div>
</div>