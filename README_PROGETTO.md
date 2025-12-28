# Gestionale Luca

Sistema di gestione contatti, clienti, progetti e task.

## Caratteristiche

### Gestione Contatti/Clienti
- Stato iniziale: **Contatto** (potenziale cliente)
- Quando viene associato un progetto, diventa automaticamente **Cliente**
- Campi: nome, email, telefono, azienda, note

### Gestione Progetti
- Ogni progetto è associato a un cliente
- Tipologie di progetto configurabili (Sviluppo Sito, Marketing WhatsApp, SEO, etc.)
- Ogni tipologia ha dei template di task predefiniti
- Quando si crea un progetto, vengono automaticamente generate le task dal template

### Gestione Task
- **Stati task**:
  - `da_fare`: Task non ancora iniziata
  - `in_corso`: Task in lavorazione
  - `completato`: Task completata
  - `in_pausa`: Task messa in pausa
  - `in_attesa`: Task in attesa di un'altra task

- **Tracking tempo**: Il sistema calcola automaticamente il tempo di lavoro su ogni task
- **Scadenze**: Ogni task ha una scadenza (modificabile), di default basata sul template
- **Dipendenze**: Una task può essere bloccata da un'altra task

### Dashboard
- Visualizza tutte le task ordinate per scadenza
- Evidenzia in rosso le task **scadute**
- Evidenzia in giallo le task **in scadenza oggi**
- Mostra statistiche: task scadute, in scadenza oggi, prossime, totali

### Template Task
- Ogni tipo di progetto ha dei template di task configurabili
- Campi: nome, descrizione, giorni di scadenza default, ordine di esecuzione

### Eventi API GoHighLevel
- Sistema pronto per integrare chiamate API trigger (da implementare secondo necessità)
- Possibili eventi: contatto diventa cliente, task completata, task in scadenza, etc.

## Stack Tecnologico

- **Backend**: Laravel 12
- **Frontend**: Vue.js 3 + Inertia.js
- **CSS**: Bulma CSS
- **Database**: SQLite (predefinito, modificabile in .env)

## Installazione

1. **Clona/copia il progetto**

2. **Installa dipendenze**
```bash
composer install
npm install --legacy-peer-deps
```

3. **Configura ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configura database**
Il file `.env` è già configurato per SQLite. Per usare un altro database, modifica:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestionale
DB_USERNAME=root
DB_PASSWORD=
```

5. **Esegui migrations e seeder**
```bash
php artisan migrate
php artisan db:seed
```

6. **Compila asset**
```bash
npm run build
```

7. **Avvia server**
```bash
php artisan serve
```

## Credenziali Default

Dopo aver eseguito il seeder:
- **Email**: admin@gestionale.test
- **Password**: password

## Dati di Esempio

Il seeder crea automaticamente:
- 1 utente admin
- 3 tipi di progetto (Sviluppo Sito, Marketing WhatsApp, SEO)
- Template task per ogni tipo di progetto
- 3 contatti (1 contatto, 2 clienti)
- 2 progetti con task generate
- Alcune task con stati diversi per testare il sistema

## Sviluppo

Per lavorare in modalità development con hot reload:
```bash
# Terminale 1: Server Laravel
php artisan serve

# Terminale 2: Vite dev server
npm run dev
```

## Struttura Database

### Tabelle Principali

- **contacts**: Contatti/Clienti
- **project_types**: Tipologie di progetto
- **projects**: Progetti
- **task_templates**: Template task per tipo progetto
- **tasks**: Task dei progetti

### Relazioni

- Contact -> Projects (1:N)
- ProjectType -> Projects (1:N)
- ProjectType -> TaskTemplates (1:N)
- Project -> Tasks (1:N)
- Task -> Task (dipendenza blocked_by)

## Personalizzazione

### Aggiungere un nuovo tipo di progetto

1. Vai in "Configurazione > Tipi Progetto"
2. Crea un nuovo tipo
3. Vai in "Configurazione > Template Task"
4. Aggiungi i template task per il nuovo tipo

### Modificare gli eventi API

Modifica i modelli per aggiungere eventi Laravel:
```php
// In app/Models/Contact.php
protected static function booted()
{
    static::updated(function ($contact) {
        if ($contact->wasChanged('status') && $contact->status === 'cliente') {
            // Trigger API GoHighLevel
            // Http::post('gohighlevel-api-url', [...]);
        }
    });
}
```

## Route Principali

- `/dashboard` - Dashboard con tutte le task
- `/contacts` - Gestione contatti
- `/projects` - Gestione progetti
- `/tasks` - Gestione task
- `/project-types` - Configurazione tipi progetto
- `/task-templates` - Configurazione template task

## API Task Actions

- POST `/tasks/{task}/start` - Avvia task
- POST `/tasks/{task}/complete` - Completa task
- POST `/tasks/{task}/pause` - Metti in pausa task
- POST `/tasks/{task}/resume` - Riprendi task

## Note

- Il sistema traccia automaticamente il tempo di lavoro quando una task viene avviata/completata/messa in pausa
- Le scadenze sono personalizzabili per ogni task
- Il layout è responsive e funziona su mobile
- Bulma CSS fornisce componenti pronti all'uso

## Prossimi Sviluppi Suggeriti

- [ ] Implementare integrazione API GoHighLevel completa
- [ ] Aggiungere notifiche email per task in scadenza
- [ ] Sistema di allegati file per task e progetti
- [ ] Report e statistiche avanzate
- [ ] Calendario visuale delle task
- [ ] Filtri e ricerca avanzata
- [ ] Esportazione dati (PDF, Excel)
- [ ] Sistema di commenti per task
- [ ] Gestione team e assegnazione task a utenti specifici

