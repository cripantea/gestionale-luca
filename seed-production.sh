#!/bin/bash

# Script per seedare il database di produzione con i dati reali

echo "🚀 SEEDING DATABASE PRODUZIONE"
echo "================================"
echo ""

SERVER="cristi@srv961648.hstgr.cloud"
APP_DIR="/var/www/html/dashboard/current"

echo "📡 Connessione al server..."
ssh $SERVER << 'ENDSSH'

cd /var/www/html/dashboard/current

echo ""
echo "🔄 Running migrations..."
php artisan migrate --force

echo ""
echo "👥 Seeding clients (RealClientsSeeder)..."
php artisan db:seed --class=RealClientsSeeder --force

echo ""
echo "💸 Seeding expenses (SpeseSeeder)..."
php artisan db:seed --class=SpeseSeeder --force

echo ""
echo "✅ COMPLETATO!"
echo ""
echo "📊 Verifica dati:"
php artisan tinker --execute="
echo '================================' . PHP_EOL;
echo 'Total Clients: ' . \App\Models\Contact::count() . PHP_EOL;
echo 'Total Spese: ' . \App\Models\Spesa::count() . PHP_EOL;
echo 'MRR Totale: ' . number_format(\App\Models\Contact::sum('accordo_economico_mensile'), 2) . '€' . PHP_EOL;
echo '================================' . PHP_EOL;
"

ENDSSH

echo ""
echo "✅ DATI CARICATI IN PRODUZIONE!"
echo ""
echo "🌐 Apri il sito e vai su Clienti per verificare"
