<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFA Debug - Test Simple</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0f0f0; }
        .test-card { background: white; padding: 20px; border-radius: 8px; margin: 10px 0; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div id="fifa-debug">
        <h1>🔍 FIFA Ultimate - Debug Simple</h1>
        
        <div class="test-card">
            <h2>✅ Test 1: Données PHP</h2>
            <p><strong>Joueur:</strong> {{ $player->first_name ?? 'Non défini' }} {{ $player->last_name ?? 'Non défini' }}</p>
            <p><strong>Email:</strong> {{ $player->email ?? 'Non défini' }}</p>
            <p><strong>Club:</strong> {{ $player->club->name ?? 'Non défini' }}</p>
        </div>

        <div class="test-card">
            <h2>✅ Test 2: Vue.js Minimal</h2>
            <p>Message Vue.js: <span class="success">@{{ message }}</span></p>
            <button @click="testClick">Cliquez pour tester Vue.js</button>
        </div>

        <div class="test-card">
            <h2>✅ Test 3: Navigation Basique</h2>
            <div>
                <button 
                    @click="activeTab = 'test1'" 
                    :class="activeTab === 'test1' ? 'success' : ''"
                >Test 1</button>
                <button 
                    @click="activeTab = 'test2'" 
                    :class="activeTab === 'test2' ? 'success' : ''"
                >Test 2</button>
            </div>
            
            <div v-show="activeTab === 'test1'" class="test-card">
                <h3>Contenu Test 1</h3>
                <p>Onglet 1 affiché correctement!</p>
            </div>
            
            <div v-show="activeTab === 'test2'" class="test-card">
                <h3>Contenu Test 2</h3>
                <p>Onglet 2 affiché correctement!</p>
            </div>
        </div>

        <div class="test-card">
            <h2>✅ Test 4: Statut</h2>
            <p><strong>Status Vue.js:</strong> <span class="success" v-text="status"></span></p>
            <p><strong>Timestamp:</strong> <span v-text="timestamp"></span></p>
        </div>
    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    message: 'Vue.js fonctionne!',
                    activeTab: 'test1',
                    status: 'ACTIF',
                    timestamp: new Date().toLocaleString('fr-FR')
                }
            },
            methods: {
                testClick() {
                    this.message = 'Clic détecté! Vue.js fonctionne parfaitement!';
                    this.timestamp = new Date().toLocaleString('fr-FR');
                }
            },
            mounted() {
                console.log('Vue.js Debug mounted successfully');
                alert('🎉 Vue.js est monté et fonctionne!');
            }
        }).mount('#fifa-debug');
    </script>
</body>
</html>
