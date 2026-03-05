<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Coach</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-indigo-600 p-6 text-white items-center">
            <center> <h2 class="text-4xl font-bold uppercase"> Nouveau Coach </h2></center
            <a href="index.php" class="text-indigo-100 hover:text-white text-sm"></a>
        </div>

        <form action="process_coach.php" method="POST" class="p-8 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">PRÉNOM</label>
                    <input type="text" name="prenom" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NOM</label>
                    <input type="text" name="nom" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">EMAIL</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">TÉLÉPHONE</label>
                    <input type="text" name="telephone" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">SPÉCIALITÉS</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="spec[]" value="1" class="w-4 h-4 text-indigo-600">
                        <span class="text-sm text-gray-600">Musculation</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="spec[]" value="2" class="w-4 h-4 text-indigo-600">
                        <span class="text-sm text-gray-600">Yoga</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="spec[]" value="3" class="w-4 h-4 text-indigo-600">
                        <span class="text-sm text-gray-600">Crossfit</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="spec[]" value="4" class="w-4 h-4 text-indigo-600">
                        <span class="text-sm text-gray-600">Boxe</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-center gap-4 mt-10">
                <a href="index.php" class="bg-gray-500 text-white py-2 px-6 rounded-lg text-xs font-bold hover:bg-gray-700 transition uppercase tracking-wider">
                    Retour
                </a>

                <button type="submit" class="bg-orange-600 text-white py-2 px-6 rounded-lg text-xs font-black hover:bg-black transition shadow-md uppercase tracking-wider">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</body>
</html>