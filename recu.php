<?php 
require_once 'config/db.php';

$id_paiement = $_GET['id'];

// Récupérer les détails du paiement
$sql = "SELECT p.*, m.nom, m.prenom, m.telephone, a.libelle, s.date_fin 
        FROM paiements p
        JOIN souscriptions s ON p.id_souscription = s.id
        JOIN membres m ON s.id_membre = m.id
        JOIN abonnements a ON s.id_abonnement = a.id
        WHERE p.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_paiement]);
$data = $stmt->fetch();

if (!$data) die("Paiement introuvable.");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu #<?= $data['id'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-xl mx-auto bg-white p-8 border shadow-sm rounded-lg" id="printable">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-indigo-600 uppercase">Ma Salle de Sport</h1>
                <p class="text-sm text-gray-500">Cap-Haïtien, Haïti</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-800">REÇU DE PAIEMENT</p>
                <p class="text-sm">#00<?= $data['id'] ?></p>
            </div>
        </div>

        <div class="mb-6 border-b pb-4">
            <p class="text-sm text-gray-500 italic">Client :</p>
            <p class="font-bold text-lg"><?= htmlspecialchars($data['nom'] . ' ' . $data['prenom']) ?></p>
            <p class="text-sm"><?= htmlspecialchars($data['telephone']) ?></p>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 text-left">
                    <th class="py-2">Description</th>
                    <th class="py-2 text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-3 text-gray-700">Abonnement : <?= htmlspecialchars($data['libelle']) ?><br>
                        <span class="text-xs">Valide jusqu'au <?= date('d/m/Y', strtotime($data['date_fin'])) ?></span>
                    </td>
                    <td class="py-3 text-right font-bold"><?= number_format($data['montant_paye'], 2) ?> HTG</td>
                </tr>
            </tbody>
        </table>

        <div class="text-right border-t-2 pt-4">
            <p class="text-sm text-gray-500 italic">Méthode : <?= strtoupper($data['methode_paiement']) ?></p>
            <p class="text-gray-400 text-xs mt-10">Merci de votre confiance !</p>
        </div>
    </div>

    <div class="text-center mt-10 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-lg">
            Imprimer le reçu (PDF)
        </button>
        <a href="paiements.php" class="block mt-4 text-gray-500 hover:underline">Retour</a>
    </div>
</body>
</html>