const fs = require('fs');
const path = require('path');

// Configuration
const FR_PATH = 'resources/js/i18n/fr.json';
const EN_PATH = 'resources/js/i18n/en.json';

function generateDocumentation() {
  console.log('📚 Génération de la documentation i18n...\n');
  
  // Charger les fichiers
  const fr = JSON.parse(fs.readFileSync(FR_PATH, 'utf8'));
  const en = JSON.parse(fs.readFileSync(EN_PATH, 'utf8'));
  
  let documentation = `# Documentation i18n - FIT Application

## 📋 Vue d'ensemble

Cette documentation décrit le système d'internationalisation (i18n) de l'application FIT, qui supporte le français et l'anglais.

## 📁 Structure des fichiers

- \`resources/js/i18n/fr.json\` - Traductions françaises
- \`resources/js/i18n/en.json\` - Traductions anglaises
- \`resources/js/i18n/index.js\` - Configuration Vue i18n

## 🔑 Organisation des clés

Les clés sont organisées par catégories fonctionnelles :

### 1. Landing Page (\`landing\`)
Interface d'accueil et présentation de l'application.

### 2. Menu (\`menu\`)
Navigation principale de l'application.

### 3. Dashboard (\`dashboard\`)
Tableau de bord principal avec KPIs et actions rapides.

### 4. RPM - Recovery Performance Management (\`rpm\`)
Module de gestion de la performance et récupération des joueurs.

### 5. DTN - Direction Technique Nationale (\`dtn\`)
Module de gestion des équipes nationales et sélections.

### 6. Referee (\`referee\`)
Module de gestion des arbitres et matchs.

### 7. Player (\`player\`)
Module de gestion des joueurs et profils.

### 8. Common (\`common\`)
Éléments communs utilisés dans plusieurs modules.

## 📊 Statistiques

- **Total des clés FR:** ${countKeys(fr)}
- **Total des clés EN:** ${countKeys(en)}
- **Langues supportées:** 2 (FR, EN)
- **Modules couverts:** 8

## 🚀 Utilisation

### Dans les composants Vue.js

\`\`\`vue
<template>
  <div>
    <h1>{{ $t('dashboard.title') }}</h1>
    <p>{{ $t('dashboard.welcome') }}</p>
  </div>
</template>

<script>
export default {
  name: 'MyComponent',
  methods: {
    showMessage() {
      this.$t('common.loading')
    }
  }
}
</script>
\`\`\`

### Changement de langue

\`\`\`javascript
// Changer vers l'anglais
this.$i18n.locale = 'en'

// Changer vers le français
this.$i18n.locale = 'fr'
\`\`\`

## 🔧 Maintenance

### Ajouter une nouvelle traduction

1. Ajouter la clé dans \`fr.json\` :
\`\`\`json
{
  "newModule": {
    "newKey": "Nouvelle valeur"
  }
}
\`\`\`

2. Ajouter la traduction dans \`en.json\` :
\`\`\`json
{
  "newModule": {
    "newKey": "New value"
  }
}
\`\`\`

### Scripts de maintenance

- \`scripts/test-i18n.cjs\` - Validation des traductions
- \`scripts/fix-translations.cjs\` - Correction automatique
- \`scripts/cleanup-i18n.cjs\` - Nettoyage des fichiers

## 📝 Conventions

1. **Nommage des clés :** Utiliser des noms descriptifs en camelCase
2. **Organisation :** Grouper par module fonctionnel
3. **Cohérence :** Maintenir la même structure dans les deux langues
4. **Commentaires :** Ajouter des commentaires pour les clés complexes

## 🎯 Bonnes pratiques

1. **Toujours utiliser des clés descriptives**
   - ✅ \`dashboard.total_matches\`
   - ❌ \`dashboard.key1\`

2. **Grouper les clés logiquement**
   - ✅ \`player.profile.name\`
   - ❌ \`player_name\`

3. **Maintenir la cohérence**
   - Vérifier que toutes les clés existent dans les deux langues
   - Utiliser le script de validation régulièrement

4. **Éviter les traductions littérales**
   - Adapter le contenu au contexte culturel
   - Tester avec des locuteurs natifs

## 🔍 Validation

Exécuter régulièrement la validation :

\`\`\`bash
node scripts/test-i18n.cjs
\`\`\`

## 📈 Évolution

Le système i18n a été conçu pour être extensible :

- Ajout facile de nouvelles langues
- Support des pluriels et interpolations
- Intégration avec les outils de traduction automatique

## 🤝 Contribution

Pour contribuer aux traductions :

1. Identifier les clés manquantes ou incorrectes
2. Proposer les corrections via les scripts appropriés
3. Tester les modifications
4. Documenter les changements

---

*Dernière mise à jour : ${new Date().toLocaleDateString('fr-FR')}*
`;

  // Sauvegarder la documentation
  fs.writeFileSync('I18N_DOCUMENTATION.md', documentation, 'utf8');
  console.log('✅ Documentation générée dans I18N_DOCUMENTATION.md');
  
  // Générer un résumé des clés par catégorie
  const categorySummary = generateCategorySummary(fr);
  fs.writeFileSync('scripts/category-summary.json', JSON.stringify(categorySummary, null, 2), 'utf8');
  console.log('📊 Résumé des catégories sauvegardé dans scripts/category-summary.json');
  
  return { documentation, categorySummary };
}

function generateCategorySummary(obj) {
  const summary = {};
  
  for (const category in obj) {
    if (typeof obj[category] === 'object' && obj[category] !== null) {
      summary[category] = {
        keyCount: countKeys(obj[category]),
        sampleKeys: Object.keys(obj[category]).slice(0, 5)
      };
    }
  }
  
  return summary;
}

function countKeys(obj, count = 0) {
  for (const key in obj) {
    count++;
    if (typeof obj[key] === 'object' && obj[key] !== null) {
      count = countKeys(obj[key], count);
    }
  }
  return count;
}

// Exécution
try {
  const result = generateDocumentation();
  
  console.log('\n🎉 Documentation générée avec succès !');
  console.log('\n📋 Fichiers créés :');
  console.log('   - I18N_DOCUMENTATION.md (documentation complète)');
  console.log('   - scripts/category-summary.json (résumé des catégories)');
  
  console.log('\n🚀 Votre système i18n est maintenant documenté et prêt à l\'emploi !');
  
} catch (error) {
  console.error('❌ Erreur lors de la génération de la documentation :', error.message);
  process.exit(1);
} 