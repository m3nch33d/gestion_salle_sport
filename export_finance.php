<?php
require_once 'config/db.php';

// 1. Définir le nom du fichier
$filename = "Rapport_Financier_" . date('Y-m-d') . ".csv";

// 2. Forcer le téléchargement du fichier par le navigateur
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// 3. Ouvrir la sortie PHP comme un fichier
$output = fopen('php://output', 'w');

// 4. Ajouter la ligne d'en-tête (Titres des colonnes)
fputcsv($output, ['Mois', 'Total Revenus (HTG)']);

// 5. Récupérer les données depuis la base
$sql = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
        FROM paiements 
        WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
        GROUP BY MONTH(date_paiement)
        ORDER BY mois DESC";

$nom_mois = [1=>"Janvier", 2=>"Fevrier", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Aout", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Decembre"];

$stmt = $pdo->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Transformer le numéro du mois en nom
    $ligne = [
        $nom_mois[$row['mois']],
        number_format($row['total'], 2, '.', '')
    ];
    fputcsv($output, $ligne);
}

fclose($output);
exit();
?>