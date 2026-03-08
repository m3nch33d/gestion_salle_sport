<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// Récupération de l'ID depuis l'URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: membres.php");
    exit();
}

// Récupération des données du membre
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
$stmt->execute([$id]);
$membre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$membre) {
    die("Membre introuvable.");
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <a href="membres.php" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour à la liste
        </a>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">
            <div class="md:w-1/3 bg-gray-200 flex items-center justify-center p-8">
                <?php if ($membre['photo'] && $membre['photo'] != 'default.png'): ?>
                    <img src="public/uploads/<?= htmlspecialchars($membre['photo']) ?>" class="w-48 h-48 rounded-full object-cover border-4 border-white shadow-lg">
                <?php else: ?>
                    <div class="w-48 h-48 rounded-full bg-blue-500 flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                        <?= strtoupper(substr($membre['nom'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="md:w-2/3 p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($membre['nom'] . ' ' . $membre['prenom']) ?></h1>
                        <p class="text-blue-600 font-medium">Adhérent #<?= $membre['id'] ?></p>
                    </div>
                    <span class="px-4 py-1 rounded-full text-sm font-semibold <?= $membre['statut'] == 'actif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                        <?= ucfirst($membre['statut']) ?>
                    </span>
                </div>

                <hr class="my-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold">Téléphone</p>
                        <p class="text-lg"><?= htmlspecialchars($membre['telephone'] ?: 'Non renseigné') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold">Email</p>
                        <p class="text-lg"><?= htmlspecialchars($membre['email'] ?: 'Non renseigné') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold">Date de naissance</p>
                        <p class="text-lg"><?= $membre['date_naissance'] ? date('d/m/Y', strtotime($membre['date_naissance'])) : 'Non renseignée' ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold">Membre depuis le</p>
                        <p class="text-lg"><?= date('d/m/Y', strtotime($membre['date_adhesion'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>