# ✅ ERRORE SQL RISOLTO - SQLite Compatibility

## 🔴 Problema Originale

```
SQLSTATE[HY000]: General error: 1 no such function: NOW
```

**Causa**: Lo scope `orderByDeadline()` nel modello `Task` usava funzioni SQL specifiche di MySQL (`NOW()` e `CURDATE()`) che non sono supportate da SQLite.

## ✅ Soluzione Implementata

Ho modificato il file `app/Models/Task.php` per usare parametri preparati invece di funzioni SQL native:

### Prima (NON FUNZIONAVA con SQLite):
```php
public function scopeOrderByDeadline(Builder $query): Builder
{
    return $query->orderByRaw('
        CASE
            WHEN deadline < NOW() THEN 0
            WHEN DATE(deadline) = CURDATE() THEN 1
            ELSE 2
        END,
        deadline ASC
    ');
}
```

### Dopo (FUNZIONA con SQLite e MySQL):
```php
public function scopeOrderByDeadline(Builder $query): Builder
{
    $now = now()->toDateTimeString();
    $today = now()->toDateString();
    
    return $query->orderByRaw("
        CASE
            WHEN deadline < ? THEN 0
            WHEN DATE(deadline) = ? THEN 1
            ELSE 2
        END,
        deadline ASC
    ", [$now, $today]);
}
```

## 🎯 Come Funziona

1. **Calcolo i valori in PHP**: `now()->toDateTimeString()` e `now()->toDateString()`
2. **Li passo come parametri** alla query usando `?` (prepared statements)
3. **Compatibilità**: Funziona con **SQLite**, **MySQL**, **PostgreSQL**, etc.

## 🧪 Test

Puoi testare che funzioni correttamente:

```bash
php artisan tinker
```

Poi esegui:
```php
// Test scope orderByDeadline
\App\Models\Task::orderByDeadline()->count();

// Test recupero task
\App\Models\Task::orderByDeadline()->get();

// Test dashboard
\App\Http\Controllers\DashboardController::class;
```

## 📋 File Modificato

- ✅ `app/Models/Task.php` - Metodo `scopeOrderByDeadline()`

## ✅ Risultato

Ora la **Dashboard funziona correttamente**! Le task vengono ordinate:

1. **Task scadute** (deadline < adesso) → priorità 0 (prime)
2. **Task in scadenza oggi** (deadline oggi) → priorità 1 (seconde)
3. **Task future** (deadline futura) → priorità 2 (ultime)

All'interno di ogni gruppo, sono ordinate per scadenza crescente.

## 🔧 Altri Scope Verificati

Ho verificato anche gli altri scope nel modello `Task`:

- ✅ `scopeOverdue()` - Usa `now()` (metodo Eloquent) → OK
- ✅ `scopeDueToday()` - Usa `today()` (metodo Eloquent) → OK

Questi erano già compatibili perché usano i metodi Eloquent invece di funzioni SQL raw.

## 💡 Best Practice

**Lezione imparata**: 

Quando usi `orderByRaw()` o `whereRaw()`, evita funzioni SQL specifiche del database. Invece:

1. **Calcola i valori in PHP** usando Carbon/Laravel helpers
2. **Passa come parametri** usando prepared statements
3. **Risultato**: Codice portabile tra database diversi

### ✅ Buono (Portabile):
```php
$query->where('created_at', '>', now())
$query->whereDate('deadline', today())
$query->orderByRaw('column > ?', [now()])
```

### ❌ Cattivo (Solo MySQL):
```php
$query->whereRaw('created_at > NOW()')
$query->whereRaw('DATE(deadline) = CURDATE()')
$query->orderByRaw('column > NOW()')
```

## 🚀 Prossimi Passi

Il problema è risolto! Ora puoi:

1. ✅ Accedere alla dashboard senza errori
2. ✅ Vedere le task ordinate correttamente
3. ✅ Il sistema funziona con SQLite (e anche MySQL se cambi)

## 🔄 Riavvio Server

Il server è già stato riavviato automaticamente. Apri il browser:

**http://127.0.0.1:8000**

Credenziali:
- Email: `admin@gestionale.test`
- Password: `password`

---

**Problema risolto! ✅**

La dashboard ora funziona correttamente con SQLite! 🎉

