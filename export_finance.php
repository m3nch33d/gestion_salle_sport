<?php
require_once 'includes/securite.php';
require_once 'config/db.php';

// Nettoyage pour éviter les caractères blancs parasites
ob_clean(); 

$sql = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
        FROM paiements 
        WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
        GROUP BY MONTH(date_paiement)
        ORDER BY mois DESC";

$stmt = $pdo->query($sql);
$donnees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nom_mois = [1=>"Janvier", 2=>"Fevrier", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Aout", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Decembre"];

$filename = "Export_Finances_Gres_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
// Utilisation du point-virgule pour une meilleure compatibilité Excel
fputcsv($output, ['MOIS', 'REVENUS (HTG)'], ';'); 

foreach ($donnees as $ligne) {
    fputcsv($output, [$nom_mois[$ligne['mois']], $ligne['total']], ';');
}

fclose($output);
exit;