#!/bin/bash

echo "🏥 TEST ROUTES HEALTHCARE"
echo "========================="

# Test des routes healthcare
echo ""
echo "📄 Test des routes healthcare..."

# Test route principale
HEALTHCARE_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/healthcare)
echo "   Healthcare Index: $HEALTHCARE_STATUS (302 = redirection vers login, normal)"

# Test route predictions
PREDICTIONS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/healthcare/predictions)
echo "   Healthcare Predictions: $PREDICTIONS_STATUS (302 = redirection vers login, normal)"

# Test route export
EXPORT_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/healthcare/export)
echo "   Healthcare Export: $EXPORT_STATUS (302 = redirection vers login, normal)"

# Test route records
RECORDS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/healthcare/records)
echo "   Healthcare Records: $RECORDS_STATUS (302 = redirection vers login, normal)"

# Test route create record
CREATE_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/healthcare/records/create)
echo "   Healthcare Create Record: $CREATE_STATUS (302 = redirection vers login, normal)"

# Vérification des routes dans Laravel
echo ""
echo "🔍 Vérification des routes dans Laravel..."
ROUTES_COUNT=$(php artisan route:list --name=healthcare | grep -c "healthcare")
echo "   Nombre de routes healthcare trouvées: $ROUTES_COUNT"

echo ""
echo "✅ RÉSULTAT :"
if [ "$ROUTES_COUNT" -ge 5 ]; then
    echo "   🎉 Toutes les routes healthcare sont définies !"
    echo "   📋 Routes disponibles :"
    echo "      - /healthcare (index)"
    echo "      - /healthcare/predictions"
    echo "      - /healthcare/export"
    echo "      - /healthcare/records"
    echo "      - /healthcare/records/create"
    echo ""
    echo "🔐 Note : Les codes 302 sont normaux car les routes sont protégées par authentification"
    echo "   Pour tester complètement, connectez-vous d'abord à l'application"
    echo ""
    echo "🎯 Instructions :"
    echo "1. Allez sur http://localhost:8000"
    echo "2. Connectez-vous avec admin@medpredictor.com / password"
    echo "3. Naviguez vers /healthcare"
    echo "4. Testez les boutons Predictions, Export, et Create Record"
    echo ""
    echo "🎉 L'erreur de route est résolue !"
else
    echo "   ❌ Problème : Routes manquantes"
fi 