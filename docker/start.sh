#!/bin/bash

# Démarrer Apache en arrière-plan
apache2-foreground &

# Attendre qu'Apache soit prêt
sleep 5

# Garder le conteneur en vie
tail -f /var/log/apache2/access.log

