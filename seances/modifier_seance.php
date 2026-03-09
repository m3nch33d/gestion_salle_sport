<?php 
require_once '../config/db.php'; 

// 1. Nou rekipere ID seyans lan nan URL la
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// 2. Nou chèche done seyans sa a nan baz de done a
try {
    $stmt = $pdo->prepare("SELECT * FROM seances WHERE id = ?");
    $stmt->execute([$id]);
    $seance = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$seance) {
        die("Séance non trouvée.");
    }

    // Rekipere lis coach yo pou dropdown nan
    $query_coaches = $pdo->query("SELECT id, nom FROM coaches ORDER BY nom ASC");
    $coaches = $query_coaches->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// 3. Si itilizatè a klike sou "Enregistrer les modifications"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $coach_id = $_POST['coach_id'];
    $date_seance = $_POST['date_seance'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    try {
        $sql = "UPDATE seances SET titre=?, coach_id=?, date_seance=?, heure_debut=?, heure_fin=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titre, $coach_id, $date_seance, $heure_debut, $heure_fin, $id]);

        header("Location: index.php?status=updated");
        exit();
    } catch (PDOException $e) {
        die("Erreur mise à jour : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Séance - Elite Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #030712; color: white; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        input, select { background: rgba(255, 255, 255, 0.05) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: white !important; }
    </style>
</head>
<body class="flex items-center justify-center min-height-screen p-6">
    <div class="max-w-2xl w-full glass-card rounded-[2rem] p-10">
        <h1 class="text-3xl font-black uppercase italic mb-8 border-l-4 border-rose-500 pl-4">Modifier la <span class="text-rose-500">Séance</span></h1>
        
        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Titre de l'activité</>
                <input type="text" name="titre" value="<?= htmlspecialchars($seance['titre']) ?>" required class="w-full p-4 rounded-xl outline-none focus:border-rose-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Coach</>
                    <select name="coach_id" class="w-full p-4 rounded-xl outline-none">
                        <?php foreach($coaches as $coach): ?>
                            <option value="<?= $coach['id'] ?>" <?= $coach['id'] == $seance['coach_id'] ? 'selected' : '' ?> class="bg-slate-900">
                                <?= htmlspecialchars($coach['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Date</>
                    <input type="date" name="date_seance" value="<?= $seance['date_seance'] ?>" required class="w-full p-4 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Heure Début</>
                    <input type="time" name="heure_debut" value="<?= $seance['heure_debut'] ?>" required class="w-full p-4 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Heure Fin</>
                    <input type="time" name="heure_fin" value="<?= $seance['heure_fin'] ?>" class="w-full p-4 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Capacité Max</>
                    <input type="number" name="capacite_max" value="<?= $seance['capacite_max'] ?>" required class="w-full p-4 rounded-xl">
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-rose-600 hover:bg-teal-500 p-4 rounded-xl font-bold uppercase tracking-widest text-sm transition-all">
                    Sauvegarder
                </button>
                <a href="index.php" class="flex-1 text-center border border-white/10 p-4 rounded-xl font-bold uppercase tracking-widest text-sm hover:bg-white/5 transition-all">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</body>
</html>