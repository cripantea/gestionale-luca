# 🔧 Fix Asset 404 dopo Deploy

## Problema
Gli asset CSS e JS danno errore 404 dopo il deploy con Envoy.

## Causa
Il problema è causato da permessi errati sulla cartella `public/build` dopo che `npm run build` la genera sul server.

---

## ✅ Soluzioni Implementate

### 1. **Envoy Aggiornato**
Ho aggiornato `Envoy.blade.php` per:
- ✅ Impostare permessi corretti (755) su `public/build` dopo la build
- ✅ Verificare che il manifest.json esista
- ✅ Impostare ownership corretta dei file

### 2. **Vite Config Migliorato**
Ho aggiunto configurazione esplicita del build in `vite.config.js`:
- ✅ Output directory esplicito
- ✅ Manifest generation confermato
- ✅ Input rollup configurato

### 3. **App Blade Semplificato**
Ho semplificato il caricamento degli asset in `app.blade.php`:
- ✅ Rimosso il caricamento dinamico delle pagine Vue (causava problemi)
- ✅ Usa solo `@vite('resources/js/app.js')` che carica tutto correttamente

---

## 🚀 Comandi da Eseguire

### Passo 1: Commit e Push delle Modifiche
```bash
git add .
git commit -m "Fix: asset 404 dopo deploy - permessi e configurazione"
git push origin main
```

### Passo 2: Fai il Deploy
```bash
vendor/bin/envoy run deploy
```

### Passo 3: Verifica gli Asset (se ancora non funziona)
```bash
vendor/bin/envoy run checkAssets
```

Questo comando mostrerà:
- ✅ Se la directory `public/build` esiste
- ✅ Se il file `manifest.json` è presente
- ✅ I permessi della directory

---

## 🔍 Debugging Manuale (se serve)

Se il problema persiste, connettiti al server:

```bash
ssh cristi@srv961648.hstgr.cloud
cd /var/www/html/dashboard/current
```

### Controlla se gli asset esistono:
```bash
ls -la public/build/
```

### Controlla il manifest:
```bash
cat public/build/manifest.json
```

### Verifica i permessi:
```bash
ls -ld public/build
# Dovrebbe essere: drwxr-xr-x (755)
```

### Se i permessi sono sbagliati, correggili manualmente:
```bash
chmod -R 755 public/build
chown -R cristi:cristi public/build
```

---

## 🌐 Verifica Configurazione Web Server

Assicurati che il tuo web server (Nginx/Apache) punti a:

```
/var/www/html/dashboard/current/public
```

E NON a:
- ❌ `/var/www/html/dashboard/public`
- ❌ `/var/www/html/dashboard/current`

### Nginx Config Esempio:
```nginx
server {
    root /var/www/html/dashboard/current/public;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location /build {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

---

## 🧪 Test in Locale Prima del Deploy

Prima di fare il deploy, testa in locale:

```bash
# Simula produzione
npm run build

# Avvia server con asset compilati
php artisan serve

# Verifica nel browser che gli asset si carichino
```

---

## ⚠️ Problemi Comuni

### 1. **404 su tutti gli asset**
- ✅ Soluzione: Verifica che `public/build` esista e abbia contenuti
- ✅ Comando: `ls -la public/build/`

### 2. **Manifest.json non trovato**
- ✅ Soluzione: Rigenera gli asset: `npm run build`
- ✅ Verifica: `cat public/build/manifest.json`

### 3. **Permessi negati (403)**
- ✅ Soluzione: `chmod -R 755 public/build`

### 4. **Symlink non funziona**
- ✅ Verifica: `ls -la /var/www/html/dashboard/current`
- ✅ Deve puntare all'ultima release

---

## 📞 Dopo il Deploy

1. Apri il sito in un browser
2. Apri la console (F12)
3. Ricarica la pagina (Ctrl+F5)
4. Verifica che NON ci siano errori 404

Se vedi ancora 404, esegui:
```bash
vendor/bin/envoy run checkAssets
```

E inviami l'output!

---

## ✅ Checklist Finale

- [ ] Ho fatto commit delle modifiche
- [ ] Ho fatto push su GitHub
- [ ] Ho eseguito `vendor/bin/envoy run deploy`
- [ ] Gli asset si caricano correttamente
- [ ] La console non mostra errori 404
- [ ] Il CSS è applicato correttamente
- [ ] Le interazioni JS funzionano

---

**Ora fai il deploy e fammi sapere se funziona!** 🚀
