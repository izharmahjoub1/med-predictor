#!/usr/bin/env python3

import re

# Lire le fichier Blade
with open('resources/views/fifa-portal-integrated.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Lire la nouvelle fonction
with open('new_function.txt', 'r', encoding='utf-8') as f:
    new_function = f.read()

# Pattern pour trouver la fonction displayLicenseTable
pattern = r'(\s+)// Afficher le tableau des licences\s+function displayLicenseTable\(licenses, tbodyElement\) \{.*?\}(\s+)// Afficher les primes de formation'

# Remplacer la fonction
replacement = r'\1' + new_function + r'\2// Afficher les primes de formation'

# Appliquer le remplacement
new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)

# Écrire le nouveau contenu
with open('resources/views/fifa-portal-integrated.blade.php', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("✅ Fonction displayLicenseTable remplacée avec succès !")
