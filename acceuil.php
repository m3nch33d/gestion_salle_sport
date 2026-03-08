<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Dechouke Grès Fitness</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Inter:wght@400;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow: hidden;
            background-color: #000;
        }

        .hero-section {
            position: relative;
            height: 100vh;
            width: 100vw;
            background-image: url('assets/images/acceuilgym.png'); 
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(13, 85, 80, 0.75); 
            z-index: 1;
        }

        /* --- STYLE COOPER BLACK --- */
        .brand-container {
            transform: translateY(-12vh); /* Remonte le texte pour la superposition */
            z-index: 10;
        }

        .brand-text {
            /* On applique Cooper Black ici */
            font-family: "Cooper Black", "Alfa Slab One", serif;
            letter-spacing: -1px; /* Resserrer légèrement pour coller au logo */
        }

        .page-exit {
            animation: fadeOut 0.8s forwards;
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(1.05); filter: blur(10px); }
        }

        @keyframes arrowMove {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(8px); }
        }

        .animate-arrow {
            display: inline-block;
            animation: arrowMove 1.5s infinite ease-in-out;
        }

        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: upright;
            font-size: 8vh; 
            letter-spacing: 2vh; 
            line-height: 1;
            text-transform: uppercase;
        }

        .vertical-container {
            position: absolute;
            left: 2rem;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            z-index: 10;
        }

        .content-z { z-index: 10; }
    </style>
</head>
<body>

    <div id="main-container" class="hero-section">
        
        <div class="vertical-container">
           <h1 class="vertical-text text-white font-black opacity-80 drop-shadow-[0_5px_15px_rgba(0,0,0,0.5)]">
              WELCOME
           </h1>
        </div>

        <div class="text-center brand-container px-4">
            <img src="assets/images/logogym.png" alt="Logo" class="w-32 md:w-48 mx-auto mb-1 drop-shadow-2xl transform translate-y-[10px]">
            
            <h2 class="brand-text text-white text-5xl md:text-7xl leading-[0.9] italic">
                Dechouke Grès<br>
                <span class="text-6xl md:text-9xl">Fitness</span>
            </h2>
        </div>

        <div class="absolute bottom-12 right-12 content-z">
            <button onclick="goToLogin()" class="bg-white text-blue-900 flex items-center gap-3 px-8 py-3 rounded-full font-black text-2xl shadow-2xl hover:bg-teal-400 transition-colors group">
                NEXT 
                <span class="animate-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </button>
        </div>

    </div>

    <script>
        function goToLogin() {
            const container = document.getElementById('main-container');
            container.classList.add('page-exit');
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 700);
        }
    </script>
</body>
</html>