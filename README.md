# Management Complaint System - Setup Instructions

## Deskripsi
Sistem Informasi Management Complaint Karyawan.

## Requirements
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL
- XAMPP (untuk local development)
- Git

## Installation Steps

### 1. Clone Repository
```bash
git clone https://github.com/maulanawira/management-complaint.git
cd management-complaint
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
1. **Start XAMPP**
   - Buka XAMPP Control Panel
   - Start Apache dan MySQL

2. **Create Database**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Create database baru dengan nama: `management_complaint`

3. **Import Database**
   - Pilih database `management_complaint`
   - Klik tab "Import"
   - Choose file: `database/backups/database_backup.sql`
   - Klik "Go"

### 5. Configure Environment (.env)
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=management_complaint
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Set Storage Permissions (Linux/Mac)
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 7. Run Application
```bash
php artisan serve
```

Akses aplikasi di: http://localhost:8000

## Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "Permission denied"
```bash
sudo chmod -R 755 storage
sudo chmod -R 755 bootstrap/cache
```

### Error: Database connection
- Pastikan MySQL di XAMPP sudah running
- Cek konfigurasi database di file `.env`
- Pastikan database `management_complaint` sudah dibuat

### Error: "Key not found"
```bash
php artisan key:generate
php artisan config:clear
```

## Default Login
- **Admin:** 1. adminlisa@company.com / password 2. adminnuri@company.com / password
- **Karyawan:** 1. karyawanhilman@company.com / password
- **Supervisor:** 1. supervisorkevin@company.com / password

## Contact
Developer: Maulana Wirayudha
Email: yudhaena7@gmail.com
GitHub: https://github.com/maulanawira