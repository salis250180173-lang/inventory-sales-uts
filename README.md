<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



# Integrated Laravel Inventory & Sales Management System

Aplikasi web Sistem Manajemen Inventaris dan Penjualan berbasis Laravel yang dikembangkan untuk memenuhi tugas proyek UTS Pemrograman Web / Sistem Terintegrasi. Aplikasi ini dirancang dengan menerapkan aturan integritas database, keamanan server-side, kontrol konkurensi, dan agregasi data yang ketat.

---

## 1. Project Overview
Aplikasi ini memfasilitasi pengelolaan inventaris barang (produk, kategori, supplier) serta pencatatan transaksi masuk (*purchases/stock-in*) dan keluar (*sales/stock-out*). Sistem ini dilengkapi dengan manajemen hak akses berbasis role (**Owner**, **Admin**, **User/Kasir**) untuk memastikan kontrol penuh terhadap operasional inventaris toko.

## 2. System Requirements
Pastikan perangkat Anda telah terinstal software berikut sebelum menjalankan project:
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL / MariaDB

## 3. Installation & Setup Steps
Ikuti langkah-langkah berikut di terminal (CLI) untuk menginstal dan menjalankan project dari repositori bersih:

```bash
# 1. Clone repositori ke komputer lokal
git clone <repository-url>
cd <nama-folder-project>

# 2. Install dependencies PHP
composer install

# 3. Salin file environment (.env.example menjadi .env)
cp .env.example .env

# 4. Generate application key
php artisan key:generate


4. Environment Configuration (.env)Buka file .env yang baru dibuat, lalu sesuaikan konfigurasi database MySQL Anda:Code snippetDB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_sales_db
DB_USERNAME=root
DB_PASSWORD=


5. Migration & SeedingJalankan perintah migrasi database beserta pengisian data dummy (seeder) secara otomatis:Bashphp artisan migrate:fresh --seed

# Install frontend assets jika diperlukan
npm install
npm run build


6. Demo Accounts (Akun Uji Coba)Gunakan kredensial akun berikut untuk masuk ke dalam aplikasi sesuai dengan role masing-masing:

Owner / PimpinanEmail: owner@example.comPassword: passwordHak Akses: Akses penuh dashboard, kelola master data, transaksi, serta otoritas khusus untuk membatalkan (cancel) transaksi.
Admin / Petugas GudangEmail: admin@example.comPassword: passwordHak Akses: Mengelola data produk, kategori, supplier, dan transaksi pembelian (stock-in).
User / KasirEmail: user@example.comPassword: passwordHak Akses: Melakukan transaksi penjualan (stock-out).


7. Main Features (Fitur Utama)Authentication & Authorization: Sistem login aman dan pembatasan hak akses berbasis role di sisi server.
Master Data Management: CRUD lengkap untuk Kategori, Produk (dengan validasi SKU unik & harga non-negatif), dan Supplier.
Purchase Management (Stock-In): Pencatatan pembelian barang dari supplier yang otomatis menambah stok produk.
Sale Management (Stock-Out): Pencatatan penjualan dengan kontrol konkurensi (lockForUpdate), pencegahan overselling (stok minus), dan transaksi atomik (DB::transaction).
Dashboard & Low-Stock List: Ringkasan data aktual dari database (total produk, kategori, total transaksi, pendapatan, serta daftar peringatan stok menipis).
Search, Filter & Pagination: Pencarian data dan filter lanjutan yang tetap stabil saat berpindah halaman (withQueryString()).8. Running the ApplicationSetelah semua konfigurasi selesai, jalankan server lokal Laravel:Bashphp artisan serve
Buka browser dan akses link yang tertera (biasanya http://127.0.0.1:8000).

About Laravel (Default Documentation)
Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:   
Simple, fast routing engine.   
Powerful dependency injection container.  
Multiple back-ends for session and cache storage.
Expressive, intuitive database ORM.  
Database agnostic schema migrations.   
Robust background job processing.   
Real-time event broadcasting.   

Laravel is accessible, powerful, and provides tools required for large, robust applications.

Security VulnerabilitiesIf you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via taylor@laravel.com. All security vulnerabilities will be promptly addressed

LicenseThe Laravel framework is open-sourced software licensed under the MIT license.

<FollowUp>
Apakah file README.md-nya sudah berhasil disalin dengan aman?
</FollowUp>

<ElicitationsGroup>
</ElicitationsGroup>


8. Database Architecture & ERD (Entity Relationship Diagram)
Berikut adalah rancangan struktur relasi tabel database pada sistem inventaris ini:

*(Catatan: Jika kamu punya gambar ERD, ganti teks di bawah dengan kode gambar: `![ERD](path/gambar.png)`)*

```text
+-------------------+       1        N       +----------------------+
|     categories    |<-----------------------|       products       |
+-------------------+                        +----------------------+
| id (PK)           |                        | id (PK)              |
| name              |                        | category_id (FK)     |
| description       |                        | name                 |
+-------------------+                        | sku (Unique)         |
                                             | stock                |
                                             | minimum_stock        |
+-------------------+       1        N       | purchase_price       |
|     suppliers     |<-----------------------| selling_price        |
+-------------------+                        +----------------------+
| id (PK)           |                                   |
| name              |                                   | 1
| phone             |                                   |
| address           |                                   | N (Has Many)
+-------------------+                        +----------------------+
                                             |    purchase_items    |
                                             +----------------------+
                                             | id (PK)              |
                                             | purchase_id (FK)     |
+-------------------+       1        N       | product_id (FK)      |
|       users       |<-----------------------| quantity             |
+-------------------+                        | unit_price           |
| id (PK)           |                        | subtotal             |
| name              |                        +----------------------+
| email             |                                   |
| password          |                                   | N
| role              |                                   |
+-------------------+                                   | 1
          |                                  +----------------------+
          | 1                                |      purchases       |
          |                                  +----------------------+
          | N (Has Many)                     | id (PK)              |
          +--------------------------------->| user_id (FK)         |
          |                                  | supplier_id (FK)     |
          |                                  | purchase_number      |
          |                                  | total_amount         |
          |                                  | purchased_at         |
          |                                  +----------------------+
          | 1
          |
          | N (Has Many)                     +----------------------+
          +--------------------------------->|      sale_items      |
                                             +----------------------+
                                             | id (PK)              |
                                             | sale_id (FK)         |
                                             | product_id (FK)      |
                                             | quantity             |
                                             | unit_price           |
                                             | subtotal             |
                                             +----------------------+
                                                        |
                                                        | N
                                                        |
                                                        | 1
                                             +----------------------+
                                             |        sales         |
                                             +----------------------+
                                             | id (PK)              |
                                             | user_id (FK)         |
                                             | sale_number          |
                                             | total_amount         |
                                             | status               |
                                             | sold_at              |
                                             +----------------------+
Penjelasan Relasi:
Categories to Products (1 to Many): Satu kategori memiliki banyak produk.

Suppliers to Purchases (1 to Many): Satu supplier dapat melakukan beberapa transaksi pembelian.

Users to Purchases & Sales (1 to Many): User (Admin/Owner) mencatat banyak transaksi masuk dan keluar.

Products to PurchaseItems & SaleItems (1 to Many): Produk dicatat dalam baris rincian transaksi pembelian dan penjualan.

9. Running the Application
Setelah semua konfigurasi selesai, jalankan server lokal Laravel:

Bash
php artisan serve
Buka browser dan akses link yang tertera (biasanya http://127.0.0.1:8000).


![ERD Sistem Inventaris](public/images/erd-inventaris.png)


<FollowUp>
Bagian ERD atau README-nya sudah jelas, atau ada hal lain yang ingin disesuaikan lagi?
</FollowUp>

<ElicitationsGroup>
</ElicitationsGroup>




