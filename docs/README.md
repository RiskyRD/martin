# 🧩 Project Akhir Native Fullstack Development – Tecart SE

## 👥 Anggota Kelompok

| Nama                                   | NIM        | Peran               |
| -------------------------------------- | ---------- | ------------------- |
| I Kadek Risky Raju Danendra            | 2505551050 | Fullstack Developer |
| Ni Ketut Lela Berliani                 | 2505551102 | Frontend Developer  |
| I Nyoman Kesava Mas Dhananjaya Rangkan | 2505551022 | Backend Developer   |

## 💡 Deskripsi Project

Tuliskan tujuan aplikasi dan manfaatnya.

## ⚙️ Teknologi yang Digunakan

-   Backend: Native PHP
-   Frontend: HTML5, CSS3, JavaScript
-   Database: MySQL / MariaDB

## 🚀 Cara Menjalankan Project

1. Clone repository:
    ```bash
    git clone https://github.com/username/project-name.git
    ```
2. Pindahkan ke htdocs (XAMPP).
3. Import database dari /docs/database.sql.
4. Jalankan di browser: http://localhost/project-name/

## 📊 Dokumentasi Tambahan

-   [Flow Aplikasi](./FLOW_APP.md)
-   [Panduan Pengguna](./USER_GUIDE.md)

## Dokumentasi Instalasi

1. Install Library dengan

```
composer install
```

2. untuk mengeksekusi ddl

```
php ./bin.php execute:ddl
```

3. untuk menyambung symlink dari assets/ ke public/assets

```
php ./bin.php asset:symlink
```

4. untuk running webserver

```
php -S localhost:8000 -t public/
```
