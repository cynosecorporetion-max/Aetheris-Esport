# shixuan MLBB & HOK — Website Joki

Website joki Mobile Legends + database MySQL untuk dijalankan di XAMPP.

## 📂 Struktur File

```
shixuan-mlbb-hok/
├── index.php          → Halaman utama (landing page)
├── process_order.php  → Endpoint penyimpanan order ke DB
├── admin.php          → Halaman admin lihat semua pesanan
├── config.php         → Konfigurasi koneksi MySQL
├── database.sql       → File SQL untuk dibuat di phpMyAdmin
└── README.md
```

## 🚀 Cara Install di XAMPP

1. **Copy folder** ini ke `C:\xampp\htdocs\shixuan-mlbb-hok`
2. **Jalankan XAMPP** → Start **Apache** & **MySQL**
3. Buka **phpMyAdmin** → http://localhost/phpmyadmin
4. Klik tab **Import** → pilih file `database.sql` → klik **Go**
   (Database `shixuan_mlbb` otomatis dibuat beserta tabelnya)
5. Buka website: **http://localhost/shixuan-mlbb-hok/**
6. Lihat pesanan masuk: **http://localhost/shixuan-mlbb-hok/admin.php**

## 🗄️ Tabel Database

| Tabel       | Fungsi                                    |
|-------------|-------------------------------------------|
| `paket`     | Daftar paket harga joki (Starter–Mythic)  |
| `pesanan`   | Data pesanan dari form website            |
| `testimoni` | Daftar testimoni customer                 |
| `faq`       | Daftar pertanyaan & jawaban               |
| `admin`     | User login admin (default: admin/admin123)|

## ⚙️ Konfigurasi DB

Edit `config.php` jika user/password MySQL kamu berbeda dari default XAMPP:

```php
$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'shixuan_mlbb';
```
