🏋️‍♂️ Dechouke Grès Fitness v1.0
Système de Gestion de Flux et de Fidélisation pour Centres de Fitness

📖 À Propos du ProjetDechouke Grès Fitness est une solution logicielle robuste conçue pour optimiser l'administration quotidienne des salles de sport. Né d'un besoin de modernisation des processus au Cap-Haïtien, ce projet remplace les registres manuels par un écosystème numérique intelligent, capable de gérer les cycles de vie des membres et d'assurer une communication proactive.

🌟 Points Forts du Logiciel
Architecture Orientée Services : Séparation stricte entre la logique métier (PHP) et la présentation (Tailwind CSS).

Système d'Alerte SMTP : Intégration de PHPMailer pour une gestion automatisée des relances (Rappels de fin d'abonnement).

Génération de QR Codes On-Device : Utilisation de la bibliothèque endroid/qr-code pour un fonctionnement 100% hors-ligne (indispensable pour les zones à connectivité variable).

UX/UI Cinématique : Interface utilisateur immersive utilisant les codes esthétiques du Chiaroscuro et des tons Teal-300.

🛠️ Stack Technique
Technologie       Utilisation
Backend           PHP 8.2 (PDO, Architecture Modulaire)
Frontend          Tailwind CSS CLI, Animate.css
DatabaseMySQL     (Gestion des relations et clés étrangères)
Dependency Mgmt   Composer
Mailing           PHPMailer (SMTP Google/App Passwords)


GESTION_SALLE_SPORT/
├── assets/                # Ressources statiques
│   ├── images/            # Logos et illustrations UI
│   ├── uploads/           # Photos de profil des membres
│   └── videos/            # Supports vidéo ou tutoriels
├── config/                # Fichiers de configuration serveur
│   ├── db.php             # Connexion PDO à la base de données
│   └── mail.php           # Configuration SMTP pour PHPMailer
├── includes/              # Composants logiques réutilisables
│   ├── auth.php           # Logique d'authentification
│   ├── header.php         # Structure commune du haut de page
│   └── securite.php       # Protection des accès (Sessions)
├── node_modules/          # Dépendances JavaScript (Tailwind CSS)
├── public/                # Dossier de distribution (Production)
│   ├── css/
│   │   └── style.css      # CSS final compilé pour le navigateur
│   └── uploads/           # Miroir de stockage des médias publics
├── src/                   # Fichiers de développement (Source)
│   └── input.css          # Fichier source pour Tailwind CLI
├── vendor/                # Dépendances PHP (Composer)
│   ├── bacon/
│   ├── composer/
│   ├── dasprid/
│   ├── endroid/           # Bibliothèque QR Code
│   ├── phpmailer/         # Bibliothèque d'envoi d'emails
│   ├── stripe/            # SDK de paiement en ligne
│   └── autoload.php       # Chargement automatique des classes
├── .env                   # Variables d'environnement (Sensible)
├── .gitignore             # Fichiers exclus du dépôt Git
├── 403.php                # Page d'erreur (Accès refusé)
├── abonnements.php        # Gestion des offres de sport
├── acceuil.php            # Dashboard principal de l'admin
├── ajouter_abonnement.php
├── ajouter_coach.php
├── ajouter_horaire.php
├── ajouter_membre.php
├── ajouter_souscription.php
├── ajouter_utilisateur.php
├── carte.php              # Générateur de cartes (Teal-300)
├── composer.json          # Configuration des packages PHP
├── composer.lock          # Verrouillage des versions packages
├── confirmation_manuelle.php
├── creer_compte.php
├── debug.php              # Outils de débogage système
├── encaisser.php          # Terminal de facturation
├── envoyer_alerte.php     # Scripts de notification
├── envoyer_rappel.php
├── export_finance.php     # Exportation des données comptables
├── export_impayes.php     # Liste des membres en retard de paiement
├── forgot_password.php
├── index.php              # Point d'entrée (Login)
├── liste_coaches.php
├── liste_horaires.php
├── liste_utilisateurs.php
├── login.php
├── logout.php
├── membres.php            # Gestion de la base de données membres
├── modifier_coach.php
├── modifier_membre.php
├── modifier_utilisateur.php
├── package-lock.json
├── package.json           # Scripts NPM pour Tailwind
├── paiements.php          # Historique des transactions
├── presences.php          # Registre de pointage
├── rapports.php           # Statistiques et graphiques
├── README.md              # Documentation du projet
├── recu.php               # Génération de reçus après paiement
├── reset_password.php
├── reset_utilisateurs.php
├── scanner.php            # Interface de scan des QR Codes
├── setup_admin.php        # Script d'installation initiale
├── souscriptions.php      # Historique des forfaits
├── supprimer_coach.php
├── supprimer_membre.php
├── supprimer_horaire.php
├── tailwind.config.js     # Configuration du framework CSS
└── voir_membre.php        # Fiche détaillée de l'adhérent

🔐 Sécurité & Intégrité des Données
Le projet implémente plusieurs couches de sécurité pour garantir la confidentialité des informations des membres :

Contrôle d'Accès de Session : Utilisation de includes/securite.php sur chaque page sensible pour vérifier l'authentification de l'administrateur avant tout rendu HTML.

Protection contre les Injections SQL : Utilisation systématique des requêtes préparées (PDO) pour toutes les interactions avec la base de données.

Validation des Entrées : Filtrage des emails via FILTER_VALIDATE_EMAIL et échappement des sorties avec htmlspecialchars() pour prévenir les attaques XSS.

Gestion des Médias : Isolation des fichiers téléchargés dans le dossier assets/uploads/ avec des restrictions de lecture pour empêcher l'exécution de scripts malveillants.


👥 Équipe de Développement

Augustin Carl-Mencheed - Architecture Backend & UI/UX Design
Celestin Caleb - Collaboration & Database Design


🎯 Conclusion & Perspectives
Le développement de Dechouke Grès Fitness a permis de répondre à une problématique réelle : la transition numérique des infrastructures sportives locales. En combinant une architecture PHP moderne, une gestion de base de données relationnelle et une interface utilisateur immersive, ce projet démontre qu'il est possible d'allier performance technique et esthétique visuelle.

📈 Évolutions Futures (Roadmap)
Bien que la version 1.0 soit entièrement fonctionnelle, plusieurs axes d'amélioration sont envisagés :

Module de Paiement Mobile : Intégration de passerelles locales pour permettre le renouvellement des abonnements à distance.

Application Compagnon : Développement d'une interface mobile pour que les membres puissent consulter leur historique d'entraînement.

Analytique Avancée : Utilisation de graphiques dynamiques pour prédire les périodes de forte affluence et optimiser la gestion du personnel.
