# 🚨 Fix Immediato Asset 404

## Problema Identificato
Il file `manifest.json` **NON viene generato** durante `npm run build`.

## Causa
Le devDependencies potrebbero non essere installate correttamente, oppure il build fallisce silenziosamente.

---

## ✅ Soluzione Rapida

### Opzione 1: Rebuild Manuale sul Server (PIÙ VELOCE)

```bash
# 1. Connettiti al server
ssh cristi@srv961648.hstgr.cloud

# 2. Vai alla directory corrente
cd /var/www/html/dashboard/current

# 3. Pulisci e reinstalla dipendenze
rm -rf node_modules package-lock.json
npm install

# 4. Ricompila gli asset
npm run build

# 5. Verifica che il manifest esista
ls -la public/build/manifest.json

# 6. Se esiste, ricarica la pagina nel browser
```

### Opzione 2: Nuovo Deploy con Fix (dopo commit)

```bash
# 1. Commit le modifiche che ho fatto
git add .
git commit -m "Fix: npm install con devDependencies e logging build"
git push origin main

# 2. Deploy
vendor/bin/envoy run deploy

# 3. Il build ora mostrerà output dettagliato e si fermerà se fallisce
```

---

## 🔍 Debug: Cosa Controllare

### Sul server, esegui:
```bash
cd /var/www/html/dashboard/current

# Controlla versioni Node/NPM
node -v  # Deve essere >= 18
npm -v   # Deve essere >= 9

# Controlla se le devDependencies sono installate
ls -la node_modules/@vitejs/plugin-vue
ls -la node_modules/vite
ls -la node_modules/laravel-vite-plugin

# Prova a buildare manualmente e guarda gli errori
npm run build
```

---

## ⚠️ Possibili Cause del Problema

### 1. Node/NPM Troppo Vecchi
```bash
# Controlla versione
node -v

# Se < 18, aggiorna:
# (su Ubuntu/Debian)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 2. DevDependencies Non Installate
Il problema era che `npm install` in produzione potrebbe non installare le devDependencies necessarie per il build.

**Ho fixato** cambiando a:
```bash
npm ci --include=dev || npm install --include=dev
```

### 3. Build Fallisce Silenziosamente
**Ho fixato** aggiungendo:
- Logging dettagliato
- Check del exit code
- Stop del deploy se il build fallisce

---

## 🎯 Task Envoy Aggiornati

### `checkAssets` - Verifica dettagliata
```bash
vendor/bin/envoy run checkAssets
```
Mostra tutto il contenuto di `public/build/`

### `rebuildAssets` - Ricompila sul server corrente
```bash
vendor/bin/envoy run rebuildAssets
```
Pulisce e ricompila gli asset sulla release corrente (più veloce di un full deploy)

---

## 📝 Prossimi Passi

### OPZIONE A: Fix Veloce (5 minuti)
1. SSH nel server
2. `cd /var/www/html/dashboard/current`
3. `npm install && npm run build`
4. Ricarica il browser

### OPZIONE B: Fix Permanente (10 minuti)
1. Commit le mie modifiche
2. Push su GitHub
3. `vendor/bin/envoy run deploy`
4. Il problema non si ripresenterà più

---

## 🆘 Se Ancora Non Funziona

Esegui questo e mandami l'output:

```bash
ssh cristi@srv961648.hstgr.cloud
cd /var/www/html/dashboard/current
echo "=== Node Version ==="
node -v
echo "=== NPM Version ==="
npm -v
echo "=== Vite Installed? ==="
ls -la node_modules/vite/bin/vite.js
echo "=== Try Build ==="
npm run build
```

---

**Prova l'Opzione A (fix veloce) ADESSO e fammi sapere!** 🚀
