# 🧪 TEST DEBUG SPESE

## Test 1: Console Browser

1. Vai su `/spese`
2. Clicca "Modifica" su una spesa
3. Apri Console (F12)
4. Scrivi nella console:

```javascript
console.log('Props spesa:', this.$page.props.spesa);
```

**Cosa dovresti vedere:**
```json
{
  "id": 1,
  "nome": "Canva",
  "importo_totale": 150,
  "frequenza": "annuale",
  "categoria": "design",
  ...
}
```

**Se vedi `undefined` o `null`:** Il problema è nel controller/route.
**Se vedi i dati:** Il problema è nel binding Vue.

---

## Test 2: Network Tab

1. Apri DevTools → Network
2. Clicca "Modifica" su una spesa
3. Cerca la richiesta GET `/spese/{id}/edit`
4. Clicca sulla richiesta → Tab "Response"

**Cosa dovresti vedere:**
```json
{
  "component": "Spese/Edit",
  "props": {
    "spesa": {
      "id": 1,
      "nome": "Canva",
      ...
    }
  }
}
```

**Se `spesa` è vuoto:** Problema nel controller.
**Se `spesa` ha dati:** Problema nel Vue component.

---

## Test 3: Verifica Database

```bash
php artisan tinker
```

```php
// Nel tinker:
$spesa = \App\Models\Spesa::find(1);
dd($spesa->toArray());
```

**Se `null`:** Il record non esiste nel database.
**Se ha dati:** Il database è OK.

---

## Test 4: Cache Clear

```bash
# Sul server o in locale:
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
npm run build
```

---

## Test 5: Hard Refresh Browser

1. Chrome/Edge: `Ctrl+Shift+Delete` → Svuota cache
2. Firefox: `Ctrl+Shift+Delete` → Svuota cache
3. Safari: `Cmd+Option+E`
4. **Oppure**: Apri in Incognito/Private

---

## COMANDO RAPIDO VERIFICA TUTTO

```bash
cd /Users/lucapantea/hacker/gestionale-luca

# Verifica route
php artisan route:list | grep spese

# Verifica database
php artisan tinker --execute="echo \App\Models\Spesa::count() . ' spese nel DB';"

# Clear all cache
php artisan optimize:clear

# Rebuild assets
npm run build

# Test controller direttamente
php artisan tinker --execute="
\$spesa = \App\Models\Spesa::first();
echo 'ID: ' . \$spesa->id . PHP_EOL;
echo 'Nome: ' . \$spesa->nome . PHP_EOL;
echo 'Importo: ' . \$spesa->importo_totale . PHP_EOL;
"
```

---

## RISULTATI ATTESI

✅ **Routes:**
```
GET|HEAD   spese ...................... spese.index
GET|HEAD   spese/create ............... spese.create
POST       spese ...................... spese.store
GET|HEAD   spese/{spesa} .............. spese.show
GET|HEAD   spese/{spesa}/edit ......... spese.edit
PUT|PATCH  spese/{spesa} .............. spese.update
DELETE     spese/{spesa} .............. spese.destroy
```

✅ **Database:** Almeno 1 spesa presente

✅ **Assets:** File `public/build/manifest.json` presente

✅ **Console:** Props `spesa` con tutti i campi

---

## SE IL PROBLEMA PERSISTE

Incolla qui l'output di:

1. Console browser (screenshot o testo)
2. Network → Response della richiesta `/spese/{id}/edit`
3. Output di `php artisan route:list | grep spese`
4. Output di `php artisan tinker --execute="\App\Models\Spesa::first();"`
