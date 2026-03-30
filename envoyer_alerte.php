<?php
require_once 'includes/securite.php';
require_once 'config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$id_membre = $_GET['id'] ?? null;
$date_fin = $_GET['date'] ?? '';

if ($id_membre && !empty($date_fin)) {
    
    $stmt = $pdo->prepare("SELECT nom, prenom, email FROM membres WHERE id = ?");
    $stmt->execute([$id_membre]);
    $membre = $stmt->fetch();

    if ($membre) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'augustinkarl321@gmail.com'; 
            $mail->Password   = 'edef phtk vqvf efgq'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('augustinkarl321@gmail.com', 'Dechouke Grès Fitness');
            $mail->addAddress($membre['email'], $membre['prenom']);

            $mail->isHTML(true);
            $mail->Subject = "Rappel de fin d'abonnement - Dechouke Grès Fitness 🏋️‍♂️";
            
            $date_formatee = date('d/m/Y', strtotime($date_fin));

            // On utilise la couleur #5eead4 (Teal-300) pour les éléments demandés
            $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <div style='max-width: 600px; margin: auto; border: 1px solid #14b8a6; border-radius: 10px; padding: 25px; background-color: #ffffff;'>
                    
                    <p style='font-size: 18px;'>Bonjour <strong style='color: #5eead4;'>" . htmlspecialchars($membre['prenom']) . "</strong>,</p>
                    
                    <p>Nous espérons que vous profitez pleinement de vos séances d'entraînement parmi nous.</p>
                    
                    <p>Ce petit message pour vous informer que votre abonnement actuel arrivera à son terme le <strong style='color: #14b8a6;'>" . $date_formatee . "</strong>. 
                    Afin d'éviter toute interruption de votre accès aux équipements et de maintenir votre progression, nous vous invitons à passer à la réception pour régulariser votre situation avant cette date.</p>
                    
                    <p>Nous avons hâte de continuer à vous accompagner dans vos objectifs.</p>
                    
                    <div style='margin-top: 30px; border-top: 1px solid #f1f5f9; pt-20px;'>
                        <p style='margin-bottom: 0;'>Sportivement,</p>
                        <strong style='color: #5eead4; font-size: 16px;'>L’équipe de Dechouke Grès Fitness.</strong>
                    </div>
                </div>
            </body>
            </html>";

            $mail->send();
            
            header("Location: rapports.php?msg=success");
            exit;

        } catch (Exception $e) {
            die("Erreur lors de l'envoi : {$mail->ErrorInfo}");
        }
    }
} else {
    header("Location: rapports.php");
    exit;
}