#!/bin/bash

# Script pour remplacer la fonction displayLicenseTable

# Créer un fichier temporaire avec la nouvelle fonction
cat > temp_function.js << 'EOF'
        // Afficher le tableau des licences
        function displayLicenseTable(licences, tbodyElement) {
            if (!licences || licences.length === 0) {
                return;
            }
            
            const licensesContent = document.getElementById('licenses-content');
            if (!licensesContent) return;
            
            // Créer le contenu complet des licences
            licensesContent.innerHTML = `
                <div class="licenses-container space-y-6">
                    <!-- Club Formateur Principal -->
                    <div class="club-formateur-section bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-3 text-blue-800">🏆 Club Formateur Principal</h3>
                        <div class="flex items-center space-x-4">
                            <div class="club-info">
                                <p class="text-sm font-medium text-blue-900">${licences[0].club || 'Club inconnu'}</p>
                                <p class="text-xs text-blue-700">Premier club de formation</p>
                            </div>
                            <div class="formation-badge">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Club Formateur
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des licences -->
                    <div class="licenses-section">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Historique des Licences (${licences.length} licence(s) trouvée(s))</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Période</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Club</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Association</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Source</th>
                                    </tr>
                                </thead>
                                <tbody class="licenses-table-body bg-white divide-y divide-gray-200">
                                    ${licences.map((licence, index) => `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <span class="font-medium">${licence.date_debut || 'N/A'}</span> → <span class="font-medium">${licence.date_fin || '—'}</span>
                                                ${index === 0 ? '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Licence actuelle</span>' : ''}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${licence.club || 'N/A'}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${licence.association || 'N/A'}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${licence.type_licence || 'N/A'}</span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">${licence.source_donnee || 'N/A'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="training-compensation-content">
                        <h4 class="text-lg font-semibold mb-4 text-gray-800">Primes de Formation FIFA</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Aucune prime calculée pour le moment</p>
                        </div>
                    </div>
                </div>
            `;
        }
EOF

echo "Fonction temporaire créée. Maintenant remplaçons dans le fichier Blade..."
