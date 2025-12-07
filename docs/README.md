# 🧩 Project Akhir Native Fullstack Development – Tecart SE

## 👥 Anggota Kelompok

| Nama                                   | NIM        | Peran               |
| -------------------------------------- | ---------- | ------------------- |
| I Kadek Risky Raju Danendra            | 2505551050 | Fullstack Developer |
| Ni Ketut Lela Berliani                 | 2505551102 | Frontend Developer  |
| I Nyoman Kesava Mas Dhananjaya Rangkan | 2505551022 | Backend Developer   |

## 💡 Deskripsi Project

Tujuan Aplikasi:

- Mengelola data laporan transaksi toko
- Meningkatkan efesiensi pelayanan kasir toko

Manfaat Aplikasi

- Mempermudah pendataan produk dan transaksi dalam sebuah toko
- Mempermudah pembuatan laporan transaksi toko

## ⚙️ Teknologi yang Digunakan

- Backend: Native PHP, Composer (untuk Library)
- Frontend: HTML5, CSS3, JavaScript
- Database: MySQL / MariaDB

## 🚀 Cara Menjalankan Project

1. Clone repository:
   ```bash
   git clone https://github.com/RiskyRD/martin
   cd martin
   ```
2. Hidupkan Apache/NGINX & MySQL pada XAMPP/LARAGON
3. Install dependency:
   ```
   composer install
   ```
4. Menjalankan di webserver:
   ```
   composer serve
   ```
5. Opsi one-liner untuk developer
   ```
   git clone https://github.com/username/project-name.git && cd project-name && composer install && composer serve
   ```
6. Buka pada browser: [http://localhost:8080/](http://localhost:8080/)
7. Login admin menggunakan:

- email : admin@example.com
- password: 12345678

8. Login kasir menggunakan:

- email : risky@example.com
- password: 12345678

## 🛠️ TroubleShooting Umum

1. Periksa/edit file .env

   ```ini
    DB_HOST=127.0.0.1
    DB_DATABASE=martin
    DB_USERNAME=root
    DB_PASSWORD=
    APP_ENV=local
    APP_DEBUG=true
   ```

   Sesuaikan username dan password dengan database lokal masing-masing

2. Run command per bagian:
   ```
   git clone https://github.com/RiskyRD/martin
   cd martin
   composer install
   copy .env.example .env
   php bin.php execute:ddl
   php bin.php asset:symlink
   php -S localhost:8000 -t public/
   ```

## 📊 Dokumentasi Tambahan

- [Flow Aplikasi](./FLOW_APP.md)
- [Panduan Pengguna](./USER_GUIDE.md)
