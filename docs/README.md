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

- Backend: Native PHP
- Frontend: HTML5, CSS3, JavaScript
- Database: MySQL / MariaDB

## 🚀 Cara Menjalankan Project

1. Clone repository:
   ```bash
   git clone https://github.com/username/project-name.git
   ```
2. Install Library:
   ```
   composer install
   ```
3. Hidupkan Apache/NGINX serta MySQL pada XAMPP/LARAGON
4. Copy file .env.example lalu rename menjadi .env
5. Import database:
   ```
   php ./bin.php execute:ddl
   ```
6. Menyambungkan symlink dari assets/ ke public/assets:
   ```
   php ./bin.php asset:symlink
   ```
7. Menjalankan di webserver:
   ```
   php -S localhost:8000 -t public/
   ```
8. Buka pada browser: [http://localhost:8080/](http://localhost:8080/)
9. Login admin menggunakan:

- email : admin@example.com
- password: 12345678

10. Login kasir menggunakan:

- email : risky@example.com
- password: 12345678

## 📊 Dokumentasi Tambahan

- [Flow Aplikasi](./FLOW_APP.md)
- [Panduan Pengguna](./USER_GUIDE.md)
