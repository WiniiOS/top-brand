<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TOP BRANDS</title>
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Figtree', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">

  <header class="text-center mb-10">
    <h1 class="text-4xl font-bold text-gray-800">TOP BRANDS</h1>
    <p class="text-gray-600 mt-2">Découvrez les marques les plus populaires de ton pays</p>
  </header>

  <section id="brandsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl w-full">
    <!-- Cartes dynamiques injectées ici -->
  </section>

</body>
<script>
    const container = document.getElementById('brandsContainer');

    async function fetchBrands() {
        try {
        const response = await fetch('http://localhost:8000/api/brands', {
            headers: {
            'CF-IPCountry': 'CM', // Tu peux changer ça dynamiquement si besoin
            'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        // console.log('response.ok',response.ok);

        const brands = await response.json();

        brands.forEach(brand => {
            const card = document.createElement('div');
            card.className = "bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:scale-105 cursor-pointer";

            card.innerHTML = `
            <img src="${brand.brand_image}" alt="${brand.brand_name}" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800">${brand.brand_name}</h2>
            </div>
            `;

            card.addEventListener('click', () => {
            alert(`Vous avez cliqué sur ${brand.brand_name}`);
            });

            container.appendChild(card);
        });
        } catch (error) {
        console.error('Erreur lors de la récupération des marques :', error);
        container.innerHTML = `<p class="text-red-500">Impossible de charger les marques pour le moment.</p>`;
        }
    }

    fetchBrands();
</script>
</html>
