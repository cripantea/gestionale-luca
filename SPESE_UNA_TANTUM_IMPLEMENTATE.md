# ✅ Spese Una Tantum & Calcolo Profitto Corretto

## 🎯 Implementazione Completata

### 1. **Spese Una Tantum / Straordinarie**

**Migration:**
- Aggiunti campi `categoria` e `data_scadenza` alla tabella `spesas`
- Frequenza `una_tantum` già supportata nell'enum esistente

**Model Spesa:**
- Nuovi scopes: `ricorrenti()`, `unaTantum()`
- Metodi helper: `isUnaTantum()`, `isRicorrente()`

**Form Create/Edit:**
- Campo `categoria` con opzioni predefinite:
  - 🛠️ Tool & Software
  - 📱 Comunicazione
  - 📚 Formazione
  - 🎨 Design & Asset
  - 💼 Fiscale & Legale
  - 🔴 Tasse
  - 🎓 Università
  - 📦 Altro

- **Campi condizionali:**
  - Se `frequenza = una_tantum` → Mostra `data_scadenza` (obbligatoria)
  - Se `frequenza != una_tantum` → Mostra `data_prossimo_rinnovo`

**Controller:**
- Validazione aggiornata per `categoria` e `data_scadenza`
- Calcolo `importo_mensile = 0` per spese una tantum (non impattano MRR mensile)

---

### 2. **Calcolo Profitto Corretto (Regime Particolare)**

**Formula implementata:**
```
MRR Lordo: 1.320€
Contributi INPS (25%): -330€
━━━━━━━━━━━━━━━━━━━━━━━━
MRR Netto: 990€
Spese Mensili Ricorrenti: -486,50€
━━━━━━━━━━━━━━━━━━━━━━━━
Profitto Mensile: 503,50€
```

**Nel Dashboard Controller:**
```php
$mrrLordo = Contact::sum('accordo_economico_mensile');
$contributi = $mrrLordo * 0.25; // 25% del lordo
$mrrNetto = $mrrLordo - $contributi;
$speseMensili = Spesa::attive()->ricorrenti()->sum('importo_mensile');
$profittoMensile = $mrrNetto - $speseMensili;
```

**Note importanti:**
- Le spese una tantum NON vengono incluse nel calcolo mensile
- Solo le spese ricorrenti (mensili, bimensili, trimestrali, annuali) impattano il profitto mensile
- I contributi al 25% vengono calcolati sul lordo PRIMA di sottrarre le spese

---

### 3. **Dashboard Aggiornato**

**Nuove Card Statistiche:**
1. **💰 MRR Lordo**: Fatturato totale mensile
2. **💵 MRR Netto**: Dopo contributi 25% (nuovo!)
3. **✨ Profitto Mensile**: MRR Netto - Spese ricorrenti (corretto!)

**Nuova Sezione: Spese Straordinarie**
- 🚨 Evidenziata con bordo rosso
- Mostra spese una tantum nei prossimi 6 mesi
- Totale somma in alto
- Badge colorati in base all'urgenza:
  - ⚠️ Rosso: 0-30 giorni
  - 🟠 Arancio: 31-60 giorni
  - 🟡 Giallo: 61-180 giorni
- Link diretto a "Gestisci" (pagina Spese)

---

## 📝 Come Usare

### Inserire una Spesa Straordinaria:

1. Vai su **Spese** → **Nuova Spesa**
2. Compila:
   - Nome: "Tasse 2026"
   - Importo: 1500
   - Frequenza: **Una Tantum**
   - Categoria: **🔴 Tasse**
   - Data Scadenza: **15 Giugno 2026** (appare solo se Una Tantum)
   - Metodo Pagamento: Bonifico
3. Salva

### Esempio per le tue spese:

```
1. Tasse Giugno
   - Importo: 1.500€
   - Categoria: Tasse
   - Scadenza: 15/06/2026

2. Università Luglio
   - Importo: 2.000€
   - Categoria: Università
   - Scadenza: 15/07/2026

3. Tasse Novembre
   - Importo: 1.500€
   - Categoria: Tasse
   - Scadenza: 01/11/2026

4. Università Novembre
   - Importo: 1.500€
   - Categoria: Università
   - Scadenza: 01/11/2026
```

### Visualizzazione Dashboard:

Nella home vedrai:
- **Card "Profitto Mensile"** con calcolo corretto (503,50€)
- **Sezione "Spese Straordinarie"** con:
  - Lista delle 4 spese
  - Totale: 6.500€
  - Giorni mancanti per ognuna
  - Badge urgenza colorati

---

## 🔢 I Tuoi Numeri Aggiornati

**Entrate:**
- MRR Lordo: 1.320,51€
- Contributi INPS (25%): -330,13€
- **MRR Netto: 990,38€**

**Uscite Ricorrenti:**
- Spese Mensili: -486,50€

**Risultato:**
- **Profitto Mensile: 503,88€**

**Spese Straordinarie 2026:**
- Giugno: -1.500€ (Tasse)
- Luglio: -2.000€ (Università)
- Novembre: -3.000€ (Tasse + Università)
- **Totale: -6.500€**

**Cash Flow Reale:**
- Profitto mensile × 12 = 6.046,56€/anno
- Spese straordinarie = -6.500€
- **Saldo netto 2026: -453,44€** ⚠️

**💡 Implicazione:**
Con i numeri attuali, **vai in negativo** di 453€ nel 2026.

**Soluzioni:**
1. Converti 1 upsell medio da 200€/mese = +2.400€/anno ✅
2. Acquisisci 1 nuovo Fusion Core a 150€/mese = +1.800€/anno ✅
3. Aumenta prezzi del 10% su clienti esistenti = +132€/mese = +1.584€/anno ✅

**Con 1 upsell convertito:**
- Nuovo MRR: 1.520€
- Nuovo profitto: 639€/mese
- Profitto annuo: 7.668€
- Dopo spese straordinarie: **+1.168€** ✅

---

## 🎯 Prossimi Step (Non implementati)

Per completare la gestione finanziaria, puoi aggiungere:

1. **Sistema Accantonamenti** - "Devi mettere da parte 542€/mese per coprire le straordinarie"
2. **Cash Flow Forecast** - Grafico 12 mesi con previsioni
3. **Alert Automatici** - Notifiche 30/15/7 giorni prima delle scadenze
4. **Budget Tracker** - Controllo spese per categoria
5. **Tax Saver** - Accantonamento tasse automatico

---

**Status**: ✅ Implementato e Funzionante  
**Data**: 17 Gennaio 2026  
**Prossimo Deploy**: Ricordati di eseguire `php artisan migrate` sul server!
