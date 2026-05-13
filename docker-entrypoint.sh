#!/bin/bash

echo "Esperando base de datos..."
sleep 5

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Limpiando caché..."
php artisan config:clear
php artisan cache:clear

echo "Iniciando Apache..."
apache2-foreground