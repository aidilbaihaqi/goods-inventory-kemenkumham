# Sistem Inventaris Barang Digital
## Kementerian Hukum dan HAM Provinsi Kepulauan Riau

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Filament-3.x-F59E0B?style=for-the-badge&logo=php&logoColor=white" alt="Filament 3.x">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL 8.0+">
</p>

## Tentang Aplikasi

Sistem Inventaris Barang Digital adalah aplikasi web modern yang dirancang khusus untuk mengelola inventaris barang di lingkungan Kementerian Hukum dan HAM Provinsi Kepulauan Riau. Aplikasi ini dibangun menggunakan framework Laravel dengan antarmuka admin yang menggunakan Filament PHP, memberikan pengalaman pengguna yang intuitif dan profesional.

### Fitur Utama

- 📦 **Manajemen Barang** - Pendaftaran dan tracking barang dengan kode unik
- 📊 **Laporan Komprehensif** - Generate laporan inventaris yang detail
- 👥 **Multi-User Access** - Sistem role-based dengan akses bertingkat
- 🔒 **Keamanan Data** - Perlindungan data tingkat enterprise
- 📱 **Responsive Design** - Akses dari berbagai perangkat
- ⚡ **Otomatisasi Proses** - Workflow otomatis untuk inventaris

## Persyaratan Sistem

Sebelum menginstall aplikasi, pastikan sistem Anda memenuhi persyaratan berikut:

- **PHP** >= 8.1
- **Composer** (untuk dependency management)
- **Node.js** >= 16.x dan **npm** (untuk asset compilation)
- **MySQL** >= 8.0 atau **MariaDB** >= 10.3
- **Web Server** (Apache/Nginx)

### Extensions PHP yang Diperlukan

```
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD PHP Extension
- ZIP PHP Extension
```

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/your-username/goods-inventory-kemenkumham.git
cd goods-inventory-kemenkumham
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Konfigurasi Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=goods_inventory
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Migrasi Database

```bash
# Jalankan migrasi database
php artisan migrate

# (Opsional) Jalankan seeder untuk data contoh
php artisan db:seed
```

### 6. Compile Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Storage Link

```bash
# Buat symbolic link untuk storage
php artisan storage:link
```

### 8. Jalankan Aplikasi

```bash
# Development server
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Konfigurasi Tambahan

### File Upload Configuration

Untuk mengoptimalkan upload file, edit konfigurasi di `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
```

### Queue Configuration (Opsional)

Untuk performa yang lebih baik, konfigurasikan queue:

```bash
# Jalankan queue worker
php artisan queue:work
```

## Akses Admin

Setelah instalasi selesai, buat user admin pertama:

```bash
php artisan make:filament-user
```

Kemudian akses panel admin di: `http://localhost:8000/admin`

## Testing

Jalankan test suite untuk memastikan aplikasi berfungsi dengan baik:

```bash
# Jalankan semua test
php artisan test

# Jalankan test dengan coverage
php artisan test --coverage
```

## Deployment Production

### 1. Optimasi untuk Production

```bash
# Cache konfigurasi
php artisan config:cache

# Cache route
php artisan route:cache

# Cache view
php artisan view:cache

# Optimasi autoloader
composer install --optimize-autoloader --no-dev
```

### 2. Web Server Configuration

#### Apache (.htaccess)

File `.htaccess` sudah disediakan di folder `public/`

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Struktur Aplikasi

```
app/
├── Filament/           # Filament admin panel resources
│   ├── Resources/      # CRUD resources
│   ├── Pages/          # Custom pages
│   └── Widgets/        # Dashboard widgets
├── Models/             # Eloquent models
├── Exports/            # Excel export classes
└── Services/           # Business logic services

database/
├── migrations/         # Database migrations
├── seeders/           # Database seeders
└── factories/         # Model factories

resources/
├── views/             # Blade templates
├── css/               # Stylesheets
└── js/                # JavaScript files
```

## Troubleshooting

### Error: "Class not found"

```bash
# Clear dan regenerate autoloader
composer dump-autoload
```

### Error: "Permission denied"

```bash
# Set permission untuk storage dan cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error: Database connection

- Pastikan MySQL/MariaDB berjalan
- Periksa kredensial database di file `.env`
- Pastikan database sudah dibuat

## Kontribusi

Jika Anda ingin berkontribusi pada pengembangan aplikasi ini:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Aplikasi ini menggunakan lisensi [MIT License](https://opensource.org/licenses/MIT).

## Dukungan

Untuk dukungan teknis atau pertanyaan, silakan hubungi:

- **Email**: support@kemenkumham-kepri.go.id
- **Dokumentasi**: Lihat file `PENJELASAN_APLIKASI.txt` untuk dokumentasi lengkap

---

**Dikembangkan dengan ❤️ untuk Kemenkumham Provinsi Kepulauan Riau**
