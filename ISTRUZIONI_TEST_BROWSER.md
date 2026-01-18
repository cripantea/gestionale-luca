# 🧪 TEST BROWSER - ISTRUZIONI PRECISE

## ✅ PROOF BACKEND FUNZIONA

```bash
=== SPESA DAL DB ===
ID: 1
Nome: Canva
Importo: 150.00
Frequenza: annuale

✅ Database: OK
✅ Controller: carica la spesa correttamente
✅ Inertia: passa 'spesa' come prop
```

---

## 🔍 ADESSO DEVI TESTARE NEL BROWSER

### 1️⃣ **Apri il sito**

```
http://localhost:8000/spese
```

(oppure il tuo URL di produzione)

### 2️⃣ **Apri DevTools PRIMA di cliccare**

- **Chrome/Edge**: F12 o Cmd+Option+I (Mac)
- **Firefox**: F12 o Cmd+Option+K (Mac)

### 3️⃣ **Vai su tab CONSOLE**

Deve essere vuota o con pochi log.

### 4️⃣ **Clicca "Modifica" su Canva**

### 5️⃣ **GUARDA LA CONSOLE**

Dovresti vedere:

```javascript
=== SPESE/EDIT DEBUG ===
props: {spesa: {…}}
props.spesa: {id: 1, nome: "Canva", importo_totale: 150, ...}
✅ SPESA RICEVUTA:
  ID: 1
  Nome: Canva
  Importo: 150
  Frequenza: annuale
Form data: {nome: "Canva", importo_totale: 150, ...}
```

---

## 📊 POSSIBILI RISULTATI

### ✅ CASO 1: Vedi i log con dati

```javascript
✅ SPESA RICEVUTA:
  ID: 1
  Nome: Canva
```

**→ SIGNIFICA:** Backend funziona, props arrivano

**→ PROBLEMA:** Form non si aggiorna visivamente (problema Vue/CSS)

**→ SOLUZIONE:** Hard refresh (Cmd+Shift+R)

---

### ❌ CASO 2: Vedi "PROPS.SPESA È UNDEFINED"

```javascript
❌ PROPS.SPESA È UNDEFINED!
```

**→ SIGNIFICA:** Props non arrivano da Inertia

**→ PROBLEMA:** Middleware, Inertia config, o cache

**→ SOLUZIONE:** Verifico Inertia middleware

---

### ❌ CASO 3: Non vedi NESSUN log

**→ SIGNIFICA:** JavaScript non viene eseguito

**→ PROBLEMA:** Manifest, assets non caricati, errore JS precedente

**→ SOLUZIONE:** Guarda tab "Network" e "Console" per errori

---

## 🔍 VERIFICA NETWORK (IMPORTANTE!)

### Vai su tab **NETWORK** in DevTools

1. Clicca "Modifica"
2. Cerca la richiesta `GET /spese/1/edit`
3. Clicca sulla richiesta
4. Vai su tab **"Response"** o **"Preview"**

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

**Se `props.spesa` è vuoto o null:** Il controller non sta passando i dati.

---

## 📸 MANDAMI QUESTI SCREENSHOT

1. **Console** con i log `=== SPESE/EDIT DEBUG ===`
2. **Network** → richiesta `/spese/1/edit` → tab Response
3. **Elements** → un campo input del form per vedere se ha `value=""`

---

## 🚀 COMANDI RAPIDI SE SERVE

### Clear cache:
```bash
cd /Users/lucapantea/hacker/gestionale-luca
php artisan optimize:clear
npm run build
```

### Restart server:
```bash
php artisan serve
```

---

## ⚡ QUICK TEST

Se vuoi testare SUBITO senza browser:

```bash
cd /Users/lucapantea/hacker/gestionale-luca
php artisan tinker

# Nel tinker:
$spesa = \App\Models\Spesa::first();
$controller = new \App\Http\Controllers\SpesaController();
$response = $controller->edit($spesa);
dd($response->props);
```

**Output atteso:**
```php
array:1 [
  "spesa" => App\Models\Spesa {#...
    id: 1
    nome: "Canva"
    ...
  }
]
```

---

## 🎯 COSA STO CERCANDO

**Se props.spesa è UNDEFINED nel browser ma OK nel tinker:**
→ Problema Inertia middleware o versione props

**Se props.spesa è OK ma form vuoto:**
→ Problema Vue reactivity o v-model binding

**Se props.spesa è NULL anche nel tinker:**
→ Problema route model binding

---

**FAI IL TEST E MANDAMI GLI SCREENSHOT DELLA CONSOLE!**
