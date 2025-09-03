## FPGrowth Bakery Analytics

Analitik keranjang belanja untuk penjualan roti menggunakan algoritma FP-Growth. Dibangun dengan Laravel 11, aplikasi ini membantu menemukan pola pembelian (frequent itemsets) dan rekomendasi bundling produk berdasarkan data transaksi.

### Fitur
- **Autentikasi & manajemen pengguna**: register, login, profil, peran sederhana (`id_priv`)
- **Manajemen data transaksi**: CRUD, unggah Excel, hapus massal
- **Analisis FP-Growth**: parameter dinamis minimal support & confidence
- **Ringkasan metrik**: total transaksi, frekuensi item, frequent patterns
- **Audit log**: pencatatan aktivitas pengguna
- **UI**: Blade + Bootstrap dengan DataTables dan Toastr

### Tumpukan Teknologi
- **Backend**: Laravel ^11.9 (PHP ^8.2), Eloquent ORM, Middleware Auth
- **Frontend**: Blade, Bootstrap, DataTables, Toastr
- **Utilitas**: graphp/graphviz (struktur/pohon)

### Struktur Rute Utama
- **Auth**: `/register`, `/login`, `/dashboard`, `/logout`
- **Data**: `GET /data`, `POST /data`, `POST /data/update`, `GET /data/hapus/{id}`, `DELETE /data/hapus-semua`, `POST /data/upload-excel`
- **FPG**: `GET /fpg`, `POST /fpg/proses1..4`, `GET /fpg/hasil`
- **Panduan**: `GET/POST /panduan`, `POST /panduan/update`, `GET /panduan/hapus/{id}`
- **User**: `GET /user`, `POST /user`, `POST /user/update`, `POST /user/updatepass`, `GET /user/profil`, `POST /user/profil/update`, `POST /user/profil/updatepass`, `GET /user/hapus/{id}`
- **Log**: `GET /log`, `GET /log/hapus/{id}`
- **Utilitas Dev**: `/migrate`, `/storage-link`, `/bersihkan`

### Prasyarat
- PHP 8.2+
- Composer
- Database (MySQL/MariaDB atau SQLite)
- Ekstensi PHP umum untuk Laravel (mbstring, openssl, pdo, tokenizer, xml, ctype, json, fileinfo)

### Instalasi
1) Clone repo
```bash
git clone <url-repo-anda>.git
cd SIMANIEZ
```
2) Install dependensi PHP
```bash
composer install
```
3) Siapkan environment
```bash
cp .env.example .env
php artisan key:generate
```
4) Konfigurasi database di file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_db
DB_USERNAME=user_db
DB_PASSWORD=pass_db
```
5) Migrasi & storage link
```bash
php artisan migrate
php artisan storage:link
```
6) Jalankan server
```bash
php artisan serve
```
Untuk Laragon (Windows), tempatkan project di `C:\laragon\www` dan akses via domain yang dikonfigurasi (mis. `http://SIMANIEZ.test`).

### Alur Penggunaan Singkat
1) Login lalu kelola data transaksi di menu `Data` (tambah manual/unggah Excel/hapus massal)
2) Buka menu `FPG`, set rentang tanggal, minimal support & confidence, jalankan proses `proses1..4`
3) Tinjau hasil frequent patterns dan insight bundling produk
4) Lihat riwayat aktivitas di menu `Log`

### Skrip Composer Terkait
```json
{
  "post-create-project-cmd": [
    "@php artisan key:generate --ansi",
    "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
    "@php artisan migrate --graceful --ansi"
  ]
}
```

### Kontribusi
- Ajukan issue untuk bug/fitur
- Buat pull request dengan deskripsi perubahan yang jelas

### Lisensi
MIT
