# 🔍 DEBUG SPESE EDIT - TEST COMPLETO

## ✅ VERIFICA BACKEND (già fatto - tutto OK)

```bash
✅ Database locale: 15 spese presenti
✅ Database produzione: 36 spese presenti
✅ Manifest locale: presente
✅ Manifest produzione: presente
✅ Controller edit(): funziona (Inertia\Response)
✅ Routes: tutte presenti
```

---

## 🧪 TEST FRONTEND - FAI QUESTI PASSAGGI

### 1️⃣ **Apri Console Browser**

**LOCALE (raccomandato per test):**
```
http://localhost:8000/spese
```

**PRODUZIONE:**
```
https://tuo-dominio.com/spese
```

### 2️⃣ **Clicca "Modifica" su una spesa**

### 3️⃣ **Apri DevTools (F12) → Console**

Dovresti vedere questi log (li ho appena aggiunti):

```javascript
🔍 DEBUG Spesa/Edit.vue
Props spesa: {id: 1, nome: "Canva", importo_totale: 150, ...}
Nome: Canva
Importo: 150
Form inizializzato: {nome: "Canva", importo_totale: 150, ...}
```

---

## 📊 POSSIBILI RISULTATI E SOLUZIONI

### ✅ **CASO 1: Vedi i log con dati**

```javascript
Props spesa: {id: 1, nome: "Canva", ...}
Nome: Canva
Importo: 150
```

**Soluzione:** Il problema è SOLO visivo/CSS
- Fai hard refresh: `Ctrl+Shift+R` (Windows) o `Cmd+Shift+R` (Mac)
- Svuota cache browser completamente
- Prova in modalità Incognito

---

### ❌ **CASO 2: `Props spesa: undefined` o `null`**

**Problema:** Il controller non passa la spesa a Inertia

**Test aggiuntivo:**
1. Apri DevTools → Network
2. Clicca "Modifica" su una spesa
3. Cerca la richiesta `GET /spese/{id}/edit`
4. Clicca sulla richiesta → Tab "Response"

**Dovresti vedere:**
```json
{
  "component": "Spese/Edit",
  "props": {
    "spesa": {
      "id": 1,
      "nome": "Canva",
      "importo_totale": 150,
      ...
    }
  }
}
```

**Se `props.spesa` è vuoto/null:** C'è un problema nel controller o route binding

---

### ❌ **CASO 3: `Props spesa: {}` (oggetto vuoto)**

**Problema:** La spesa viene passata ma è vuota

**Verifica:**
```bash
# Nel terminale:
cd /Users/lucapantea/hacker/gestionale-luca
php artisan tinker

# Nel tinker:
$spesa = \App\Models\Spesa::first();
dd($spesa->toArray());
```

**Se tutti i campi sono `null`:** Problema nel database

---

### ❌ **CASO 4: Non vedi nessun log**

**Problema:** JavaScript non viene caricato o hai una versione cached

**Soluzioni:**
1. Svuota cache completamente: `Ctrl+Shift+Delete`
2. Verifica in DevTools → Network che i file `.js` vengano caricati
3. Cerca errori JavaScript in Console

---

## 🛠️ COMANDI RAPIDI DEBUG

### Test Database:
```bash
cd /Users/lucapantea/hacker/gestionale-luca
php artisan tinker --execute="
\$spesa = \App\Models\Spesa::first();
echo 'ID: ' . \$spesa->id . PHP_EOL;
echo 'Nome: ' . \$spesa->nome . PHP_EOL;
echo 'Importo: ' . \$spesa->importo_totale . PHP_EOL;
echo 'Frequenza: ' . \$spesa->frequenza . PHP_EOL;
echo 'Categoria: ' . (\$spesa->categoria ?? 'null') . PHP_EOL;
echo 'Data rinnovo: ' . (\$spesa->data_prossimo_rinnovo ?? 'null') . PHP_EOL;
"
```

### Test Controller Diretto:
```bash
php artisan tinker --execute="
\$spesa = \App\Models\Spesa::first();
\$controller = new \App\Http\Controllers\SpesaController();
\$response = \$controller->edit(\$spesa);
echo 'Response class: ' . get_class(\$response) . PHP_EOL;
"
```

### Clear Cache + Rebuild:
```bash
# Clear tutte le cache
php artisan optimize:clear

# Rebuild assets
npm run build

# Restart server (se usa php artisan serve)
# Ctrl+C poi:
php artisan serve
```

---

## 📸 SCREENSHOT CHE MI SERVONO

Se il problema persiste, mandami screenshot di:

1. **Console browser** (F12 → Console) con i log `🔍 DEBUG`
2. **Network tab** (F12 → Network) della richiesta `/spese/{id}/edit` → Response
3. **Elements tab** (F12 → Elements) del form `<input name="nome">` per vedere se ha `value="..."`

---

## 🎯 SE ANCORA NON FUNZIONA

Esegui questo comando e incollami l'output:

```bash
cd /Users/lucapantea/hacker/gestionale-luca

echo "=== DATABASE ===" 
php artisan tinker --execute="\$s = \App\Models\Spesa::first(); print_r(\$s->toArray());"

echo "\n=== MANIFEST ===" 
test -L public/build/manifest.json && echo "Symlink OK" || echo "Symlink KO"

echo "\n=== CONTROLLER ===" 
grep -A 5 "public function edit" app/Http/Controllers/SpesaController.php

echo "\n=== ROUTES ===" 
php artisan route:list | grep "spese.*edit"

echo "\n=== ASSETS ===" 
ls -lh public/build/assets/Edit-*.js | tail -1
```

---

## 📋 CHECKLIST RAPIDA

- [ ] Ho svuotato la cache del browser
- [ ] Ho fatto hard refresh (Ctrl+Shift+R)
- [ ] Ho provato in modalità Incognito
- [ ] Ho aperto la Console (F12) e cercato i log `🔍 DEBUG`
- [ ] Ho controllato Network tab per la response
- [ ] Ho verificato che non ci siano errori JavaScript in Console
- [ ] Ho eseguito `php artisan optimize:clear`
- [ ] Ho eseguito `npm run build`

---

**🔥 IMPORTANTE: TESTA IN LOCALE PRIMA (http://localhost:8000)**

Se funziona in locale ma non in produzione, il problema è nel deploy!
