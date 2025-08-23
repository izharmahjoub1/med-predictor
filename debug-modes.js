// 🔧 SCRIPT DE TEST SIMPLE - MODE VOCAL

console.log('🔧 SCRIPT DE TEST MODE VOCAL - DÉMARRAGE...');

// Fonction de test simple
function testModeVocal() {
    console.log('🧪 Test simple du mode vocal...');
    
    // 1. Vérifier l'existence
    const vocalSection = document.getElementById('vocal-mode-section');
    if (!vocalSection) {
        console.error('❌ Section vocale non trouvée !');
        return;
    }
    
    console.log('✅ Section vocale trouvée');
    
    // 2. Forcer l'affichage
    vocalSection.style.cssText = '';
    vocalSection.classList.remove('hidden');
    vocalSection.style.display = 'block';
    vocalSection.style.visibility = 'visible';
    vocalSection.style.opacity = '1';
    vocalSection.style.height = 'auto';
    vocalSection.style.overflow = 'visible';
    vocalSection.style.position = 'relative';
    vocalSection.style.zIndex = '9999';
    
    // 3. Masquer le mode manuel
    const manualSection = document.getElementById('manual-mode-section');
    if (manualSection) {
        manualSection.style.display = 'none';
        manualSection.classList.add('hidden');
    }
    
    // 4. Activer le bouton vocal
    const vocalButton = document.getElementById('mode-vocal');
    const manualButton = document.getElementById('mode-manuel');
    
    if (vocalButton) {
        vocalButton.className = 'mode-selector active bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 transition-colors';
    }
    
    if (manualButton) {
        manualButton.className = 'mode-selector bg-gray-100 text-gray-700 p-4 rounded-lg hover:bg-gray-200 transition-colors';
    }
    
    console.log('✅ Mode vocal forcé à visible');
    console.log('✅ Mode manuel masqué');
    console.log('✅ Bouton vocal activé');
    
    // 5. Vérification finale
    setTimeout(() => {
        const isVisible = vocalSection.offsetParent !== null && 
                        !vocalSection.classList.contains('hidden') && 
                        vocalSection.style.display !== 'none';
        
        console.log('🔍 VÉRIFICATION FINALE:');
        console.log('  - Section vocale visible:', isVisible);
        console.log('  - Classes:', vocalSection.className);
        console.log('  - Display:', vocalSection.style.display);
        console.log('  - Hidden:', vocalSection.classList.contains('hidden'));
        
        if (isVisible) {
            console.log('🎉 MODE VOCAL VISIBLE !');
        } else {
            console.error('❌ MODE VOCAL TOUJOURS INVISIBLE !');
        }
    }, 100);
}

// Attendre que la page soit chargée
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('🚀 Page chargée, test disponible');
    });
} else {
    console.log('🚀 Page déjà chargée, test disponible');
}

// Exposer la fonction globalement
window.testModeVocal = testModeVocal;

console.log('🔧 FONCTION DISPONIBLE:');
console.log('  - testModeVocal() : Test et affichage du mode vocal');
