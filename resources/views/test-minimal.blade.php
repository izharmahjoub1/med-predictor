<!DOCTYPE html>
<html>
<head>
    <title>Test Minimal Vue</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>
    <h1>Test Minimal Laravel + Vue</h1>
    
    <div id="app">
        <h2>@{{ message }}</h2>
        <button @click="changeMessage">Changer</button>
        <p>Joueur: {{ Auth::user()->player->first_name ?? 'Non connecté' }}</p>
    </div>
    
    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    message: 'Vue fonctionne avec Laravel!'
                }
            },
            methods: {
                changeMessage() {
                    this.message = 'Message changé!';
                }
            },
            mounted() {
                console.log('Vue minimal mounted!');
            }
        }).mount('#app');
    </script>
</body>
</html>
