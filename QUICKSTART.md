# 🎯 QUICK START - Gestionale Luca

## Avvio Rapido

```bash
# Metodo 1: Usa lo script
./start.sh

# Metodo 2: Manuale
php artisan serve
```

Poi apri il browser su: **http://127.0.0.1:8000**

## Credenziali

- **Email**: `admin@gestionale.test`
- **Password**: `password`

## Cosa Vedere

1. **Dashboard** - Tutte le task ordinate per scadenza
2. **Contatti** - 3 contatti di esempio (1 contatto + 2 clienti)
3. **Progetti** - 2 progetti attivi
4. **Task** - ~11 task già create
5. **Configurazione** > Tipi Progetto - 3 tipi di progetto
6. **Configurazione** > Template Task - 15 template task

## Come Funziona

### Workflow Tipico

1. **Crea un Contatto** → Stato: "contatto"
2. **Crea un Progetto** e associalo al contatto
   - Il contatto diventa automaticamente "cliente"
   - Le task vengono generate automaticamente dal template
3. **Gestisci le Task**:
   - Avvia task → Stato: "in corso"
   - Completa task → Stato: "completato"
   - Metti in pausa → Stato: "in pausa"
4. **Dashboard** mostra tutto ordinato per scadenza

## Stati Task

- **da_fare** 🔵 - Task non ancora iniziata
- **in_corso** 🟡 - Task in lavorazione
- **completato** 🟢 - Task completata
- **in_pausa** ⚫ - Task messa in pausa
- **in_attesa** ⚪ - Task in attesa di un'altra task

## Features Principali

✅ Gestione Contatti/Clienti
✅ Gestione Progetti con tipologie
✅ Task generate automaticamente da template
✅ Tracking tempo automatico sulle task
✅ Dashboard con prioritizzazione scadenze
✅ Design responsive con Bulma CSS
✅ Autenticazione sicura
✅ Dati di esempio già pronti

## Comandi Utili

```bash
# Resetta database e ricrea dati esempio
php artisan migrate:fresh --seed

# Compila asset per produzione
npm run build

# Modalità sviluppo con hot reload
npm run dev

# Vedi tutte le route
php artisan route:list
```

## File Importanti

- `PROGETTO_COMPLETATO.md` - Documentazione completa
- `README_PROGETTO.md` - Guida dettagliata
- `database/seeders/DatabaseSeeder.php` - Dati di esempio

## Tecnologie

- Laravel 12
- Vue.js 3 + Inertia.js
- Bulma CSS
- SQLite

## Tutto Pronto! 🚀

Il progetto è completamente funzionale. Buon lavoro!

