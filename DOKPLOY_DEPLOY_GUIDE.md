# Panduan Deployment Laravel Sangat Aman di Dokploy

Dokumen ini berisi panduan langkah-demi-langkah untuk mendeploy aplikasi Laravel (Stock UMKM) ke **Dokploy** dengan standar keamanan tinggi (Production-Ready & Secure).

---

## 🛡️ Prinsip Keamanan Utama (Security Best Practices)
Sebelum memulai, pastikan Anda memahami 3 pilar keamanan kontainer Laravel:
1. **Jangan Pernah Mengemas Kredensial**: File `.env` asli tidak boleh dimasukkan ke Git/Image Docker. Semua rahasia dimasukkan melalui panel Dokploy.
2. **Nonaktifkan Debug Mode**: `APP_DEBUG` wajib bernilai `false` di production agar detail error/kredensial tidak bocor saat sistem mengalami kendala.
3. **Isolasi Database**: Jangan membuka port database (3306) ke publik. Gunakan jaringan internal Dokploy (Docker Network) untuk komunikasi antar kontainer.

---

## 📋 Langkah-Langkah Setup Deployment Aman

### Langkah 1: Siapkan Environment Variables Secara Aman di Dokploy
Saat membuat aplikasi baru di Dashboard Dokploy, masuk ke menu **Environment Variables** dan tambahkan variabel berikut secara manual. Jangan gunakan file `.env` lokal!

| Key | Value | Keterangan |
| :--- | :--- | :--- |
| `APP_NAME` | `Stock UMKM` | Nama aplikasi Anda |
| `APP_ENV` | `production` | Wajib set ke `production` untuk mengaktifkan optimasi |
| `APP_DEBUG` | `false` | **SANGAT PENTING!** Mencegah kebocoran data kredensial saat error |
| `APP_URL` | `https://domain-anda.com` | URL domain production Anda |
| `APP_KEY` | `base64:xxx...` | Generate dari local (`php artisan key:generate --show`) dan paste ke sini |
| `DB_CONNECTION` | `mysql` | Driver database |
| `DB_HOST` | `mysql-container-name` | Gunakan nama kontainer database internal Dokploy (bukan IP publik) |
| `DB_PORT` | `3306` | Port internal database |
| `DB_DATABASE` | `nama_database` | Nama database production |
| `DB_USERNAME` | `user_secure` | Username database production |
| `DB_PASSWORD` | `password_sangat_kuat`| Password yang panjang dan kompleks |

---

### Langkah 2: Jaringan Internal Database (Docker Network)
Agar database MySQL Anda aman dari serangan hacker luar:
1. Di Dokploy, saat membuat database MySQL, pastikan opsi **Expose Port to Public** dalam kondisi **NONAKTIF** (Disabled).
2. Buat database tersebut berada di dalam network yang sama dengan aplikasi Anda (biasanya `dokploy-network`).
3. Konfigurasikan `DB_HOST` di env aplikasi menggunakan **service name/kontainer name** database di Dokploy (misalnya: `dokploy-database-mysql`), bukan menggunakan IP VPS publik Anda.

---

### Langkah 3: Konfigurasi SSL/HTTPS Otomatis
Keamanan transmisi data wajib dilindungi menggunakan enkripsi SSL (HTTPS):
1. Masuk ke setelan aplikasi Anda di Dokploy.
2. Pada bagian **Domains**, masukkan domain Anda (misal: `stock.umkmanda.com`).
3. Centang opsi **Automatic SSL (Let's Encrypt)**. Dokploy akan mengurus pembuatan dan pembaruan sertifikat SSL secara otomatis.
4. Di sisi Laravel, pastikan Anda menggunakan HTTPS dengan menambahkan konfigurasi berikut di `app/Providers/AppServiceProvider.php` agar semua aset dimuat via HTTPS di production:
   ```php
   use Illuminate\Support\Facades\URL;

   public function boot(): void
   {
       if (config('app.env') === 'production') {
           URL::forceScheme('https');
       }
   }
   ```

---

### Langkah 4: Otomatisasi Migrasi Database dengan Aman (Pre-deploy Command)
Untuk menghindari kegagalan migrasi di production atau downtime saat proses deploy:
1. Di Dokploy, buka setelan aplikasi Anda lalu cari kolom **Build Settings** atau **Lifecycle Hooks**.
2. Masukkan perintah migrasi di bagian **Pre-deploy Command** atau **Post-deployment Command**:
   ```bash
   php artisan migrate --force
   ```
   *Menggunakan flag `--force` wajib di production agar Laravel menjalankan migrasi tanpa meminta konfirmasi interaktif.*

---

### Langkah 5: Keamanan File dan Permissions (Sudah Terpasang di Dockerfile)
Sistem operasi Linux di dalam Docker dilindungi dengan membatasi hak akses file. [Dockerfile](file:///c:/xampp/htdocs/stock_umkm/Dockerfile) Anda telah diatur agar:
1. Pemilik seluruh folder aplikasi diserahkan kepada user non-root standar web server, yaitu `www-data`:
   ```dockerfile
   RUN chown -R www-data:www-data /var/www/html
   ```
2. Hanya folder yang benar-benar membutuhkan akses tulis saja (`storage` dan `bootstrap/cache`) yang diberikan hak akses `775`, sedangkan sisanya dilindungi dari modifikasi runtime:
   ```dockerfile
   && chmod -R 775 /var/www/html/storage \
   && chmod -R 775 /var/www/html/bootstrap/cache
   ```

---

### Langkah 6: Monitoring Logs Secara Rutin
Jika terjadi keanehan atau aplikasi tidak bisa diakses:
1. Buka dashboard **Dokploy**, masuk ke aplikasi Anda, lalu pilih tab **Logs**.
2. Di sana Anda dapat melihat log runtime Apache/PHP secara real-time.
3. Untuk melihat log spesifik dari Laravel, Anda dapat masuk ke konsol kontainer (Terminal Dokploy) dan mengetikkan:
   ```bash
   tail -f /var/www/html/storage/logs/laravel.log
   ```

---

💡 **Saran Tambahan**: Lakukan backup database MySQL Anda secara terjadwal menggunakan fitur **Backup** bawaan Dokploy ke cloud storage eksternal (seperti AWS S3 atau R2) demi mengamankan data transaksi UMKM jika terjadi kendala pada server utama.
