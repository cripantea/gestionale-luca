# 📊 DATI CLIENTI - DOVE SONO?

## ✅ **I DATI CI SONO!**

Nel database locale ci sono **13 clienti reali**:

```
1. MDL Unipol - 450€/mese
2. Vismara - 454.17€/mese
3. Dental Equipe - 150€/mese (GHL + WhatsApp)
4. La Boutique del Benessere - 150€/mese (GHL + WhatsApp)
5. BellezzaPiù - 150€/mese (GHL + WhatsApp)
6. Fiorella Rigamonti - 100€/mese
7. Fairy Tooth - 100€/mese
8. Dea Bags - 100€/mese
9. Mad for Hair - 39.17€/mese (GHL + WhatsApp)
10. Studio 3 - 39.17€/mese
11. I Tre Gioielli - 27€/mese
12. Ariel - 47€/mese
13. Studio Albani 51 - 0€/mese (WhatsApp)
```

---

## 🎯 **IL PROBLEMA**

I dati sono in **2 POSTI DIVERSI**:

### 1️⃣ **Database LOCALE** (il tuo computer)
- ✅ File: `database/database.sqlite`
- ✅ Dati: 13 clienti completi
- ✅ URL: http://127.0.0.1:8000

### 2️⃣ **Database PRODUZIONE** (server remoto)
- ❌ Dati: database vuoto o vecchio
- ❌ URL: il tuo dominio pubblico

---

## 🔍 **COME VEDERE I DATI**

### Opzione A: Vedi Dati LOCALI (sul tuo computer)

```bash
# 1. Avvia server locale
php artisan serve

# 2. Apri browser
http://127.0.0.1:8000

# 3. Login e vai su Clienti
# Dovresti vedere tutti i 13 clienti!
```

---

## 🚀 **COME PORTARE I DATI IN PRODUZIONE**

### Metodo 1: Export/Import Database

```bash
# 1. Esporta dati locali
php artisan db:seed --class=RealClientsSeeder --force

# 2. Oppure esporta tutto il database
sqlite3 database/database.sqlite .dump > backup.sql

# 3. Sul server di produzione, importa:
# (Dipende dal tipo di database in produzione)
```

### Metodo 2: Riesegui Seeder in Produzione

Sul server remoto (via SSH):

```bash
# 1. Pull del codice
git pull origin main

# 2. Esegui migrazioni
php artisan migrate

# 3. Esegui seeder clienti
php artisan db:seed --class=RealClientsSeeder

# 4. Verifica
php artisan tinker
>>> \App\Models\Contact::count()
```

### Metodo 3: Deploy con Envoy (automatico)

```bash
# Nel tuo computer
php artisan envoy run deploy

# Poi sul server, esegui manualmente:
php artisan db:seed --class=RealClientsSeeder
```

---

## 📝 **VERIFICA DATI LOCALI**

```bash
# Conta clienti
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM contacts;"

# Mostra tutti i clienti
sqlite3 database/database.sqlite "SELECT name, accordo_economico_mensile FROM contacts ORDER BY name;"

# Verifica MRR totale
sqlite3 database/database.sqlite "SELECT SUM(accordo_economico_mensile) as mrr_totale FROM contacts;"
```

**MRR Totale atteso:** ~1.806€/mese

---

## 🐛 **SE NON VEDI I DATI IN LOCALE**

```bash
# 1. Verifica che il database esista
ls -lh database/database.sqlite

# 2. Riesegui le migrazioni
php artisan migrate:fresh

# 3. Riesegui i seeder
php artisan db:seed --class=RealClientsSeeder
php artisan db:seed --class=SpeseSeeder

# 4. Riavvia server
php artisan serve
```

---

## 🎯 **NEXT STEPS**

1. **Ora**: Apri http://127.0.0.1:8000 e verifica che vedi i clienti
2. **Se OK**: Decidi se vuoi portare i dati in produzione
3. **Deploy**: Segui "Metodo 2" per portare i dati sul server remoto

---

## ⚠️ **IMPORTANTE**

Il seeder `RealClientsSeeder.php` è già committato e pushato su GitHub!

Quindi:
- ✅ Il codice è salvato
- ✅ Puoi rieseguirlo quando vuoi
- ✅ Funziona sia in locale che in produzione

**Basta eseguire:** `php artisan db:seed --class=RealClientsSeeder`
