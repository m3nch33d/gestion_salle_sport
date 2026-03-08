<?php 
// Koneksyon ak baz de done a
require_once '../config/db.php'; 

// Nou rekipere lis tout coach ou te kreye yo pou nou ka chwazi youn
try {
    $stmt = $pdo->query("SELECT id, nom, prenom FROM coaches ORDER BY prenom ASC");
    $coachs = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Programmer une Séance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-3xl shadow-xl">
        <h2 class="text-2xl font-black text-orange-600 mb-6 uppercase italic">Nouvelle Séance</h2>
        
        <form action="process_seance.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Titre de l'activité</label>
                <input type="text" name="titre" placeholder="ex: Zumba, Boxe, Yoga" required 
                       class="w-full border-2 p-3 rounded-xl focus:border-orange-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Choisir le Coach</label>
                <select name="coach_id" required class="w-full border-2 p-3 rounded-xl focus:border-orange-500 outline-none bg-white">
                    <option value="">-- Sélectionnez un coach --</option>
                    <?php foreach($coachs as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase">Date</label>
                    <input type="date" name="date_seance" required class="w-full border-2 p-3 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase">Places Max</label>
                    <input type="number" name="capacite_max" value="20" class="w-full border-2 p-3 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase">Heure Début</label>
                    <input type="time" name="heure_debut" required class="w-full border-2 p-3 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase">Heure Fin</label>
                    <input type="time" name="heure_fin" required class="w-full border-2 p-3 rounded-xl">
                </div>
            </div>

            <button type="submit" class="block mx-auto bg-orange-600 text-white py-5 px-2 rounded-xl font-black hover:bg-black transition">
    ENREGISTRER LA SÉANCE
</button>
        </form>
    </div>
</body>
</html>