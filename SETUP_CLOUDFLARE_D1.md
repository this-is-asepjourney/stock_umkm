# Panduan Setup Dual Database (MySQL & Cloudflare D1) di Laravel

Panduan ini menjelaskan cara mengonfigurasi Laravel agar dapat menggunakan **MySQL** (sebagai database utama) dan **Cloudflare D1** (sebagai database sekunder) secara bersamaan.

---

## 1. Persiapan Awal
Sebelum memulai, pastikan Anda telah membuat database D1 di Cloudflare dan memiliki tiga informasi berikut dari Dashboard Cloudflare:
- **Account ID** (Bisa dilihat di URL dashboard atau sidebar Cloudflare).
- **Database ID** (ID unik dari database D1 yang Anda buat).
- **API Token** (Buat token dengan izin: `D1: Edit` atau `D1: Admin`).

## 2. Install Package Driver D1
Karena Laravel tidak mendukung D1 secara *native*, jalankan perintah ini di terminal untuk menginstal driver komunitas yang mendukung Cloudflare D1 via HTTP REST API:

```bash
composer require ntanduy/cloudflare-d1-database
```

## 3. Konfigurasi `config/database.php`
Buka file `config/database.php`. Gulir ke bagian array `'connections'`, dan tambahkan konfigurasi `'d1'` tepat di bawah konfigurasi `'mysql'`.

```php
'connections' => [

    // 1. KONEKSI UTAMA: MySQL
    'mysql' => [
        'driver' => 'mysql',
        'url' => env('DB_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            (PHP_VERSION_ID >= 80500 ? Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA) => env('MYSQL_ATTR_SSL_CA') ? (is_file(env('MYSQL_ATTR_SSL_CA')) ? env('MYSQL_ATTR_SSL_CA') : base_path(env('MYSQL_ATTR_SSL_CA'))) : null,
        ]) : [],
    ],

    // 2. KONEKSI SEKUNDER: Cloudflare D1
    'd1' => [
        'driver' => 'd1',
        'prefix' => '',
        'database' => env('CLOUDFLARE_DATABASE_ID', ''),
        'account_id' => env('CLOUDFLARE_ACCOUNT_ID', ''),
        'token' => env('CLOUDFLARE_API_TOKEN', ''),
    ],

    // ... koneksi lainnya
],
```

## 4. Konfigurasi `.env`
Buka file `.env` Anda. Pastikan `DB_CONNECTION` tetap menggunakan `mysql`. Kemudian, tambahkan variabel Cloudflare di bagian bawah konfigurasi database Anda:

```env
# Database Utama (MySQL)
DB_CONNECTION=mysql
DB_HOST=mysql-stockumkm-...aivencloud.com
DB_PORT=21320
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=password_database_anda
MYSQL_ATTR_SSL_CA=ssl/ca.pem
DB_OPTIONS_SSL_MODE=REQUIRED

# Database Sekunder (Cloudflare D1)
CLOUDFLARE_ACCOUNT_ID=isi_dengan_account_id_cloudflare
CLOUDFLARE_DATABASE_ID=isi_dengan_database_id_d1
CLOUDFLARE_API_TOKEN=isi_dengan_api_token
```
> **Catatan:** Jangan lupa tambahkan juga variabel `CLOUDFLARE_*` ini di Vercel Dashboard (Settings > Environment Variables) jika Anda men-deploy ke Vercel.

---

## 5. Menjalankan Migrasi
Secara default, jika Anda menjalankan `php artisan migrate`, tabel akan dibuat di MySQL. 
Jika Anda ingin menjalankan migrasi khusus untuk D1 (misalnya tabel `logs`), Anda harus menetapkan koneksinya menggunakan flag `--database`:

```bash
php artisan migrate --database=d1
```

*(Saran: Pisahkan file migrasi yang ditujukan untuk D1 ke folder tersendiri dan eksekusi menggunakan flag `--path` agar tidak tercampur dengan migrasi MySQL).*

---

## 6. Cara Penggunaan di Kode Aplikasi

### A. Melalui Model Eloquent (Disarankan)
Jika Anda memiliki tabel yang disimpan di D1 (misal: tabel `logs`), Anda hanya perlu menambahkan `protected $connection = 'd1';` di dalam model tersebut.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    // Arahkan model ini agar selalu menggunakan Cloudflare D1
    protected $connection = 'd1'; 
    
    protected $fillable = ['action', 'user_id', 'description'];
}
```
Sekarang, setiap kali Anda memanggil `Log::create(...)` atau `Log::all()`, datanya akan otomatis masuk/keluar dari D1. Sementara model lain (seperti `User`, `Product`) akan tetap menggunakan MySQL.

### B. Melalui Query Builder
Jika Anda butuh memanggil tabel D1 tanpa melalui Model, Anda bisa menggunakan `DB::connection('d1')`.

```php
use Illuminate\Support\Facades\DB;

// Mengambil data dari MySQL
$users = DB::table('users')->get();

// Mengambil data dari Cloudflare D1
$logs = DB::connection('d1')->table('logs')->orderBy('created_at', 'desc')->get();
```

---
**Selesai!** Aplikasi Anda kini berjalan menggunakan Arsitektur Dual Database. 🚀
