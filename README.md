# KasKantor

Aplikasi pencatatan kas masuk dan keluar untuk kantor atau unit usaha kecil, dibangun dengan **CodeIgniter 4** (PHP) dan MySQL. Proyek ini dibuat sebagai portofolio untuk menunjukkan struktur MVC CI4, validasi, migration/seeder, auth berbasis role, dan REST API.

> Tangkapan layar: tambahkan di folder `docs/` lalu tautkan di sini (dashboard, daftar transaksi, laporan bulanan).

## Fitur

- **Login, logout, dan role** (`admin`, `kasir`, `viewer`) dengan session dan filter
- **Transaksi kas**: tambah, ubah, hapus, filter tanggal/jenis/kategori/akun, pagination
- **Validasi** sisi server dengan pesan bahasa Indonesia; kategori harus cocok dengan jenis transaksi
- **Ringkasan**: total saldo, saldo per akun, kas masuk/keluar bulan ini, grafik 6 bulan
- **Laporan bulanan**: saldo awal, mutasi, saldo akhir, rekap per kategori, unduh CSV, cetak
- **Master data** (admin): akun kas/bank dan kategori
- **Jejak audit**: login, perubahan transaksi, dan perubahan master data tercatat dengan user dan IP
- **REST API** JSON untuk transaksi
- **Migration dan seeder** untuk membuat skema dan data contoh

## Keamanan

- Password di-hash dengan `password_hash` (bcrypt), session di-regenerate saat login
- CSRF aktif untuk semua form, header keamanan (`secureheaders`) aktif
- Batas percobaan login: 5 kali per menit per IP
- Akses dibatasi per role lewat filter `auth` dan `role`
- Semua output di-escape dengan `esc()`, query lewat Query Builder (parameter ter-bind)
- Akun yang sudah dipakai transaksi tidak bisa dihapus (hanya dinonaktifkan)

## Struktur

```
app/
  Config/Routes.php, Filters.php       routing dan filter
  Controllers/                         Auth, Dashboard, Transactions, Reports,
                                       Accounts, Categories, Audit, Profile
  Controllers/Api/TransactionApi.php   ResourceController (JSON)
  Models/                              User, Account, Category, Transaction, Audit
  Filters/                             AuthFilter, RoleFilter
  Helpers/kas_helper.php               rupiah(), tgl(), audit(), can()
  Database/Migrations/                 5 migration
  Database/Seeds/                      user, master, transaksi contoh
  Views/                               layout + halaman
public/                                index.php, assets/
database.sql                           skema + data contoh untuk import manual
```

## Menjalankan lokal

Syarat: PHP 8.1+ (ekstensi `intl`, `mbstring`, `mysqlnd`), MySQL/MariaDB.

```bash
cp env.example .env
# isi app.baseURL dan database.default.* di .env, lalu:
php spark migrate
php spark db:seed DatabaseSeeder
php spark serve
```

Buka http://localhost:8080.

## Akun contoh

| Role   | Email                  | Akses                                   |
|--------|------------------------|-----------------------------------------|
| admin  | admin@kaskantor.test   | semua, termasuk master data dan audit   |
| kasir  | kasir@kaskantor.test   | lihat dan catat transaksi, laporan      |
| viewer | viewer@kaskantor.test  | hanya lihat                             |

Password awal semua akun: `password123`. **Ganti lewat menu "Ganti password" setelah deploy.**

## Deploy ke hosting PHP (cPanel / shared hosting)

1. Upload seluruh isi folder ke hosting. Idealnya arahkan *document root* ke folder `public/`.
   Jika tidak bisa, file `.htaccess` di root sudah meneruskan semua request ke `public/`.
2. Buat database MySQL, lalu import `database.sql` lewat phpMyAdmin (atau jalankan `php spark migrate` dan `db:seed` jika ada akses terminal; pilih salah satu).
3. Salin `env.example` menjadi `.env`, isi `app.baseURL` (akhiri dengan `/`) dan kredensial database. Biarkan `CI_ENVIRONMENT = production`.
4. Pastikan folder `writable/` bisa ditulis oleh web server (permission 755 atau 775).
5. Buka situs, login, lalu ganti password semua akun.

## REST API

Login dulu (cookie session). Semua respons JSON.

| Method | Endpoint                 | Keterangan                                   |
|--------|--------------------------|----------------------------------------------|
| GET    | `/api/transactions`      | daftar, query: `page`, `from`, `to`, `type`  |
| GET    | `/api/transactions/{id}` | detail                                       |
| POST   | `/api/transactions`      | buat (admin, kasir), body JSON               |
| PUT    | `/api/transactions/{id}` | ubah (admin, kasir)                          |
| DELETE | `/api/transactions/{id}` | hapus (admin, kasir)                         |

Contoh body:

```json
{ "trx_date": "2026-11-02", "type": "masuk", "account_id": 1, "category_id": 1, "amount": 1500000, "description": "Penjualan" }
```

Tanpa login: `401`. Role `viewer` menulis: `403`. Data tidak valid: `400` dengan daftar error.

## Catatan CI4 dibanding CI3

- Namespace dan autoload PSR-4 (`App\Controllers\...`), tidak lagi `CI_Controller`
- Model berbasis `CodeIgniter\Model` dengan `allowedFields`, timestamp otomatis, dan pagination bawaan
- Filter (`before`/`after`) menggantikan hook dan sebagian besar pola autentikasi di constructor
- Routing eksplisit (`$routes->get/post/group/resource`), auto-routing dimatikan secara default
- Konfigurasi lewat class `Config\*` dan file `.env`; migration dan seeder dijalankan dengan `php spark`
