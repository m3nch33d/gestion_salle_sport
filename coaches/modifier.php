<?php
require_once '../config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM coaches WHERE id = ?");
$stmt->execute([$id]);
$coach = $stmt->fetch();

if (!$coach) { die("Coach introuvable."); }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Modifier Coach</title>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow">
        <h2 class="text-2xl font-bold mb-6">Modifier le profil de <?= htmlspecialchars($coach['prenom']) ?></h2>
        
        <form action="process_coach.php" method="POST" class="space-y-4">
            <input type="hidden" name="id" value="<?= $coach['id'] ?>">
            
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="prenom" value="<?= htmlspecialchars($coach['prenom']) ?>" class="border p-3 rounded-lg w-full">
                <input type="text" name="nom" value="<?= htmlspecialchars($coach['nom']) ?>" class="border p-3 rounded-lg w-full">
            </div>
            
            <input type="email" name="email" value="<?= htmlspecialchars($coach['email']) ?>" class="border p-3 rounded-lg w-full">
            <input type="text" name="telephone" value="<?= htmlspecialchars($coach['telephone']) ?>" class="border p-3 rounded-lg w-full">

            <button type="submit" name="update" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold">Mettre à jour</button>
        </form>
    </div>
</body>
</html>