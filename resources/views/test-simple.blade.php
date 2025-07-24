<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Vue.js Simple</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-D2oWiM6l.css') }}">
</head>
<body>
    <div id="app">
        <h1>Test Vue.js</h1>
        <div id="vue-app">
            <!-- Vue.js Application will be mounted here -->
            <p>Chargement de Vue.js...</p>
        </div>
    </div>

    <script type="module" src="{{ asset('build/assets/app-BsARf4Bu.js') }}"></script>
    
    <script>
        // Test simple pour vérifier si Vue.js fonctionne
        console.log('Page chargée');
        
        // Attendre que Vue.js se monte
        setTimeout(() => {
            console.log('Vue.js devrait être monté maintenant');
            const app = document.getElementById('vue-app');
            if (app) {
                console.log('Contenu de l\'app:', app.innerHTML);
            }
        }, 2000);
    </script>
</body>
</html> 