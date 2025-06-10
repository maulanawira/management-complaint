<<<<<<< HEAD
# Setup Management Complaint System

## Requirements
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL
- XAMPP (untuk MySQL dan Apache)

## Installation Steps
1. Clone repository ini
2. Install dependencies dengan `composer install`
3. Copy `.env.example` ke `.env`
4. Setup database di `.env`
5. Import database dari `database/backups/database_backup.sql`
6. Generate application key dengan `php artisan key:generate`
7. Jalankan dengan `php artisan serve`

## Database Setup
1. Buka phpMyAdmin
2. Buat database baru dengan nama `management_complaint`
3. Import file `database/backups/database_backup.sql`
=======
# management-complaint
Sistem Informasi Management Complaint
>>>>>>> 15feffcddeb7b99479c3a8dcd1b04793ecf1ce89
