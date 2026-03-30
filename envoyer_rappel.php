<?php
require_once 'includes/securite.php';
require_once 'config/db.php';

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Récupération des infos du membre
    $stmt = $pdo->prepare("SELECT nom, prenom, email FROM membres WHERE id = ?");
    $stmt->execute([$id]);
    $membre = $stmt->fetch();

    if ($membre && filter_var($membre['email'], FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);

        try {
            // --- CONFIGURATION SERVEUR SMTP ---
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'augustinkarl321@gmail.com'; 
            $mail->Password   = 'edef phtk vqvf efgq'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // --- DESTINATAIRES ---
            $mail->setFrom('augustinkarl321@gmail.com', 'Dechouke Grès Fitness');
            $mail->addAddress($membre['email'], $membre['prenom'] . ' ' . $membre['nom']);

            // --- CONTENU ---
            $mail->isHTML(true);
            $mail->Subject = 'Notification d\'expiration - Dechouke Grès Fitness';
            
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: auto; border: 1px solid #14b8a6; border-radius: 10px; padding: 25px;'>
                    <h2 style='color: #5eead4; margin-top: 0;'>Bonjour " . htmlspecialchars($membre['prenom']) . ",</h2>
                    
                    <p>Nous vous informons que votre abonnement a <strong style='color: #e11d48;'>expiré</strong>.</p>
                    
                    <p>Afin de continuer à bénéficier de nos services sans interruption et de maintenir vos résultats, nous vous invitons à procéder à son renouvellement dans les plus brefs délais directement à la réception.</p>
                    
                    <p>Si vous avez besoin d’assistance ou d’informations supplémentaires, n’hésitez pas à nous contacter lors de votre prochaine visite.</p>
                    
                    <div style='margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px;'>
                        <p style='margin-bottom: 0;'>Cordialement,</p>
                        <strong style='color: #5eead4; font-size: 16px;'>L'équipe Dechouke Grès Fitness</strong>
                    </div>
                </div>";

            $mail->send();
            header("Location: rapports.php?msg=success");
            exit;
            
        } catch (Exception $e) {
            die("Erreur lors de l'envoi : {$mail->ErrorInfo}");
        }
    } else {
        header("Location: rapports.php?msg=error_email");
        exit;
    }
} else {
    header("Location: rapports.php");
    exit;
}