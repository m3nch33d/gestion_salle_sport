<?php
// 1. Koneksyon ak baz de done a
require_once '../config/db.php';

try {
    // 2. Rekipere tout seyans yo ak non coach la (itilize yon JOIN)
    $sql = "SELECT s.*, c.nom, c.prenom 
            FROM seances s 
            LEFT JOIN coaches c ON s.coach_id = c.id 
            ORDER BY s.date_seance ASC, s.heure_debut ASC";
            
    $stmt = $pdo->query($sql);
    $seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planning des Séances</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl font-black text-gray-900 uppercase italic">Planning Gym</h1>
                <p class="text-gray-500">Gérez les séances et les horaires</p>
            </div>
            <a href="ajouter.php" class="bg-orange-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:bg-black transition-all">
                + Nouvelle Séance
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($seances) > 0): ?>
                <?php foreach ($seances as $s): ?>
                <div class="bg-white p-6 rounded-3xl shadow-sm border-l-8 border-orange-500 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold">
                            <?= date('d M Y', strtotime($s['date_seance'])) ?>
                        </span>
                        <span class="text-gray-400 text-xs font-mono uppercase">
                            Capacité: <?= $s['capacite_max'] ?>
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-black text-gray-800 mb-1 uppercase tracking-tight">
                        <?= htmlspecialchars($s['titre']) ?>
                    </h3>
                    
                    <p class="text-gray-500 text-sm mb-4">
                        Coach: <span class="font-bold text-gray-700">
                            <?= htmlspecialchars($s['prenom'] . ' ' . $s['nom']) ?>
                        </span>
                    </p>
                    
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Début</p>
                            <p class="font-bold text-gray-700"><?= substr($s['heure_debut'], 0, 5) ?></p>
                        </div>
                        <div class="text-gray-300">→</div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Fin</p>
                            <p class="font-bold text-gray-700"><?= substr($s['heure_fin'], 0, 5) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full bg-white p-12 rounded-3xl text-center shadow-sm">
                    <p class="text-gray-400 text-lg">Aucune séance planifiée pour le moment.</p>
                    <a href="ajouter.php" class="text-orange-600 font-bold underline mt-2 inline-block">Ajouter la première séance</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>