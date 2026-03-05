<?php
require_once '../config/db.php';

try {
    // Men nouvo kòd SQL la ki pi klè pou PHP pa fè erè
    $sql = "SELECT coaches.*, GROUP_CONCAT(specialities.nom SEPARATOR ', ') as expertise 
            FROM coaches 
            LEFT JOIN coach_speciality ON coaches.id = coach_speciality.coach_id 
            LEFT JOIN specialities ON coach_speciality.speciality_id = specialities.id 
            GROUP BY coaches.id";
            
    $stmt = $pdo->query($sql);
    $coachs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Coachs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black text-gray-800 uppercase italic tracking-tight">Gestion Coachs</h1>
            <a href="ajouter.php" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold shadow-md hover:bg-indigo-700 transition">+ Ajouter</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Coach</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Expertises</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (count($coachs) > 0): ?>
                        <?php foreach ($coachs as $coach): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($coach['prenom'] . ' ' . $coach['nom']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($coach['email']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full italic"><?= $coach['expertise'] ?: 'Aucune' ?></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="modifier.php?id=<?= $coach['id'] ?>" class="text-indigo-600 hover:underline text-sm font-bold mr-3">Modifier</a>
                                <a href="suprimer.php?id=<?= $coach['id'] ?>" class="text-red-500 hover:underline text-sm font-bold" onclick="return confirm('Efase coach sa?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="p-10 text-center text-gray-400 italic">Aucun coach trouvé.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>