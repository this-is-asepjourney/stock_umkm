# 📋 Checklist Deployment ke Dokploy (Stock UMKM)

## 🔧 Perbaikan yang Sudah Dilakukan

✅ **Dockerfile** - Fixed multi-stage build syntax error
✅ **.env** - Fixed APP_ENV typo dan APP_DEBUG set ke `false`
✅ **.env** - APP_URL perlu diubah sesuai domain Anda
✅ **.env** - DB credentials sudah dihapus (akan diset di Dokploy dashboard)

---

## 📝 Langkah-Langkah Deploy ke Dokploy

### Step 1: Push Changes ke Git
```bash
git add .
git commit -m "Fix Dockerfile dan .env untuk production deployment"
git push origin main
```

### Step 2: Di Dokploy Dashboard - Buat Aplikasi Baru

1. **Go to Dashboard** → Click **"Create New Application"**
2. **Select Repository**: Pilih repository GitHub Anda
3. **Build Settings**:
   - **Dockerfile**: ✅ (sudah dikonfigurasi)
   - **Build Command**: Kosongkan (Docker sudah handle)
   - **Start Command**: Kosongkan (Dockerfile handle)

### Step 3: Environment Variables di Dokploy Dashboard

⚠️ **PENTING**: Set ini di Dokploy, BUKAN di file .env

| Variable | Value | Keterangan |
|----------|-------|-----------|
| `APP_NAME` | `Stock UMKM` | Nama aplikasi |
| `APP_ENV` | `production` | Production mode |
| `APP_DEBUG` | `false` | **JANGAN `true`!** |
| `APP_URL` | `https://your-domain.com` | Ganti dengan domain HTTPS Anda |
| `APP_KEY` | `base64:oTWMwVjuJsooZl5OIFpyU7kjMt0N5j3dvjGj+ygpzH0=` | Sudah ada |
| `DB_CONNECTION` | `mysql` | Database driver |
| `DB_HOST` | `mysql` | Nama container database (BUKAN IP!) |
| `DB_PORT` | `3306` | Port database |
| `DB_DATABASE` | `stock_umkm` | Nama database |
| `DB_USERNAME` | `db_user` | Username database (buat user baru!) |
| `DB_PASSWORD` | `password_panjang_dan_kompleks_12345!` | **Password yang KUAT** |
| `LOG_CHANNEL` | `single` | Logging |

### Step 4: Setup MySQL Database di Dokploy

1. **Create Database Service**:
   - Click **"Services"** → **"Create Database"** → **MySQL**
   - **MySQL Version**: `8.0` atau `5.7`
   - **Root Password**: Buat password yang kuat
   - ⚠️ **DISABLE** "Expose to Public" (penting untuk keamanan!)
   - Network: Sama dengan aplikasi Anda

2. **Catat Service Name** dari MySQL container (biasanya: `mysql` atau `dokploy-mysql`)

### Step 5: Pre-deployment Command

Di **Build Settings**, set **Pre-deploy Command**:
```bash
php artisan migrate --force
php artisan cache:clear
```

### Step 6: Domain & SSL Setup

1. **Add Domain**:
   - Go to app settings → **Domains**
   - Enter: `your-domain.com` (tanpa `https://`)
   
2. **Enable SSL**:
   - Check: ✅ **"Automatic SSL (Let's Encrypt)"**
   - Dokploy akan otomatis generate certificate

### Step 7: Deploy!

1. Click **"Deploy"** button
2. Monitor logs:
   - Check **Logs** tab untuk melihat progress build
   - Tunggu sampai status menjadi **"Running"** (hijau)

---

## ✅ Setelah Deploy - Verification

### 1. Test Website
```bash
curl https://your-domain.com
# atau buka di browser
```

### 2. Check Logs (jika ada masalah)
```bash
# Di terminal Dokploy:
tail -f /var/www/html/storage/logs/laravel.log
```

### 3. Troubleshooting Umum

#### ❌ CSS/JS tidak dimuat (blank page)
```bash
# Di terminal container:
php artisan view:clear
php artisan cache:clear
php artisan optimize
```

#### ❌ Database Connection Error
- Pastikan MySQL container **running**
- Check `DB_HOST` menggunakan **service name**, bukan IP
- Verify credentials benar di environment variables

#### ❌ Permission Denied
```bash
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/bootstrap/cache
```

---

## 🔐 Security Checklist

- [ ] `APP_DEBUG=false` di production
- [ ] `DB_PASSWORD` strong dan random
- [ ] Database **NOT** exposed to public
- [ ] SSL/HTTPS enabled
- [ ] `.env` NOT committed ke Git (verify `.gitignore`)
- [ ] Backup database configured

---

## 📚 Referensi

- [Dokploy Official Docs](https://dokploy.com)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)
