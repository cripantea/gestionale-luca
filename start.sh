#!/bin/bash

# Script per avviare il Gestionale Luca
# Usa questo script per avviare rapidamente il progetto

echo "🚀 Avvio Gestionale Luca..."
echo ""

# Verifica che il database esista
if [ ! -f "database/database.sqlite" ]; then
    echo "❌ Database non trovato. Creazione in corso..."
    touch database/database.sqlite
    php artisan migrate --seed
fi

# Avvia il server Laravel
echo "✅ Avvio server Laravel su http://127.0.0.1:8000"
echo ""
echo "📧 Credenziali di accesso:"
echo "   Email: admin@gestionale.test"
echo "   Password: password"
echo ""
echo "Premi CTRL+C per fermare il server"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

php artisan serve

