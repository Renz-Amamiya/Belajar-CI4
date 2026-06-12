# 🛒 Belajar CI4 — Aplikasi Toko Sederhana

Proyek pembelajaran **CodeIgniter 4** yang mengimplementasikan aplikasi toko/e-commerce sederhana dengan fitur manajemen produk, keranjang belanja, dan autentikasi pengguna. Dibangun sebagai bagian dari studi framework PHP modern menggunakan pola arsitektur **MVC**.

---

## ✨ Fitur

- 🔐 **Autentikasi** — Login & logout dengan session, validasi input, dan password hashing (`password_verify`)
- 📦 **Manajemen Produk (CRUD)** — Tambah, lihat, edit, dan hapus produk beserta upload foto
- 🛒 **Keranjang Belanja** — Tambah item, ubah kuantitas, hapus item, dan kosongkan keranjang menggunakan Cart service CI4
- 📄 **Export PDF** — Download daftar produk dalam format PDF menggunakan Dompdf
- 🛡️ **Auth Filter** — Proteksi route dengan custom filter, hanya user yang login yang bisa mengakses halaman utama
- 🗄️ **Soft Delete** — Data produk dan user menggunakan soft delete (`deleted_at`)
- 🌱 **Seeder & Migration** — Database siap pakai dengan seeder untuk data awal

---

## 🗂️ Struktur Proyek

```
Belajar-CI4/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php       # Login & logout
│   │   ├── ProdukController.php     # CRUD produk + export PDF
│   │   ├── TransaksiController.php  # Manajemen keranjang
│   │   └── Home.php                 # Halaman utama, FAQ, profil, kontak
│   ├── Models/
│   │   ├── ProductModel.php         # Model produk (soft delete, timestamps)
│   │   └── UserModel.php            # Model user (soft delete, timestamps)
│   ├── Filters/
│   │   └── Auth.php                 # Filter cek session isLoggedIn
│   ├── Database/
│   │   ├── Migrations/              # Tabel: user, product, transaction, transaction_detail
│   │   └── Seeds/
│   │       ├── UserSeeder.php       # Data awal user
│   │       └── ProductSeeder.php   # Data awal produk
│   └── Views/
│       ├── layout.php               # Layout utama dengan NiceAdmin template
│       ├── layout_clear.php         # Layout tanpa sidebar (untuk login)
│       ├── v_login.php              # Halaman login
│       ├── v_home.php               # Halaman beranda
│       ├── v_produk.php             # Halaman daftar produk
│       ├── v_keranjang.php          # Halaman keranjang belanja
│       ├── produk/
│       │   ├── index.php            # List produk (admin)
│       │   ├── modal_add.php        # Modal tambah produk
│       │   ├── modal_edit.php       # Modal edit produk
│       │   └── download_pdf.php    # Template PDF produk
│       └── components/
│           ├── header.php
│           ├── sidebar.php
│           └── footer.php
├── public/
│   ├── index.php                    # Entry point aplikasi
│   ├── img/                         # Foto produk yang diupload
│   └── NiceAdmin/                   # Template admin (Bootstrap-based)
└── composer.json
```

---

## 🔀 Routing

| Method | URL | Controller | Keterangan |
|--------|-----|-----------|------------|
| GET | `/` | `Home::index` | Beranda (butuh login) |
| GET/POST | `/login` | `AuthController::login` | Halaman login |
| GET | `/logout` | `AuthController::logout` | Logout & hapus session |
| GET | `/produk` | `ProdukController::index` | Daftar produk |
| POST | `/produk` | `ProdukController::create` | Tambah produk |
| POST | `/produk/edit/{id}` | `ProdukController::edit` | Edit produk |
| GET | `/produk/delete/{id}` | `ProdukController::delete` | Hapus produk |
| GET | `/produk/download` | `ProdukController::download` | Export PDF |
| GET | `/keranjang` | `TransaksiController::index` | Lihat keranjang |
| POST | `/keranjang` | `TransaksiController::cart_add` | Tambah ke keranjang |
| POST | `/keranjang/edit` | `TransaksiController::cart_edit` | Update kuantitas |
| GET | `/keranjang/delete/{rowid}` | `TransaksiController::cart_delete` | Hapus item |
| GET | `/keranjang/clear` | `TransaksiController::cart_clear` | Kosongkan keranjang |

> Semua route kecuali `/login` dilindungi oleh filter `auth`.

---

## 🗄️ Database

Terdapat 4 tabel utama yang dibuat via migration:

- **`user`** — `id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`, `deleted_at`
- **`product`** — `id`, `nama`, `harga`, `jumlah`, `foto`, `created_at`, `updated_at`, `deleted_at`
- **`transaction`** — data header transaksi
- **`transaction_detail`** — detail item per transaksi

---

## ⚙️ Persyaratan Sistem

- PHP **8.2** atau lebih tinggi
- Ekstensi PHP: `intl`, `mbstring`, `json`, `mysqlnd`
- Composer
- MySQL / MariaDB
- Web server (Apache/Nginx) atau `php spark serve`

---

## 🚀 Cara Instalasi

### 1. Clone repository

```bash
git clone https://github.com/Renz-Amamiya/Belajar-CI4.git
cd Belajar-CI4
```

### 2. Install dependensi

```bash
composer install
```

### 3. Konfigurasi environment

```bash
cp env .env
```

Edit file `.env`, sesuaikan bagian berikut:

```env
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = nama_database_kamu
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

### 4. Buat database & jalankan migration

```bash
php spark migrate
```

### 5. Jalankan seeder (data awal)

```bash
php spark db:seed UserSeeder
php spark db:seed ProductSeeder
```

### 6. Jalankan server

```bash
php spark serve
```

Buka browser dan akses: **http://localhost:8080**

---

## 📦 Dependensi Utama

| Package | Kegunaan |
|---------|---------|
| `codeigniter4/framework` | Framework PHP utama |
| `dompdf/dompdf` | Generate PDF dari HTML |

---

## 🧑‍💻 Teknologi yang Digunakan

- **CodeIgniter 4** — PHP Framework (MVC)
- **MySQL** — Database
- **NiceAdmin** — Template admin berbasis Bootstrap
- **Dompdf** — Export laporan PDF
- **CI4 Cart Service** — Manajemen keranjang belanja

---

## 👤 Author

**Renz Amamiya**  
Mahasiswa Teknik Informatika — Universitas Dian Nuswantoro (UDINUS)  
GitHub: [@Renz-Amamiya](https://github.com/Renz-Amamiya)
