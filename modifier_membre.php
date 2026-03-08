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

<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Modifier le Membre</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Nom</label>
                <input type="text" name="nom" value="<?= $m['nom'] ?>" class="w-full p-3 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Prénom</label>
                <input type="text" name="prenom" value="<?= $m['prenom'] ?>" class="w-full p-3 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Téléphone</label>
                <input type="text" name="telephone" value="<?= $m['telephone'] ?>" class="w-full p-3 border rounded-lg">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700">Statut</label>
                <select name="statut" class="w-full p-3 border rounded-lg">
                    <option value="actif" <?= $m['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= $m['statut'] == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold">Enregistrer les modifications</button>
        </form>
    </div>
</div>