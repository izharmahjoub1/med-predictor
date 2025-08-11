#!/bin/bash

echo "🔍 Recherche de l'instance Google Cloud..."
echo ""

# Lister toutes les instances
echo "=== Instances disponibles ==="
gcloud compute instances list

echo ""

# Lister toutes les zones
echo "=== Zones disponibles ==="
gcloud compute zones list --filter="status=UP"

echo ""

# Chercher l'instance dans toutes les zones
echo "=== Recherche de l'instance dans toutes les zones ==="
for zone in $(gcloud compute zones list --filter="status=UP" --format="value(name)"); do
    echo "Recherche dans la zone: $zone"
    instances=$(gcloud compute instances list --filter="zone:$zone" --format="value(name)")
    if [ ! -z "$instances" ]; then
        echo "Instances trouvées: $instances"
        for instance in $instances; do
            echo "  - $instance (zone: $zone)"
        done
    else
        echo "  Aucune instance trouvée"
    fi
    echo ""
done

echo "=== Résumé ==="
echo "Si aucune instance n'est trouvée, l'instance peut être:"
echo "1. Arrêtée (stopped)"
echo "2. Supprimée"
echo "3. Dans un autre projet"
echo "4. Avec un nom différent"

echo ""
echo "Pour créer une nouvelle instance:"
echo "gcloud compute instances create med-predictor-instance \\"
echo "  --zone=europe-west1-b \\"
echo "  --machine-type=e2-medium \\"
echo "  --image-family=ubuntu-2004-lts \\"
echo "  --image-project=ubuntu-os-cloud \\"
echo "  --tags=http-server,https-server" 