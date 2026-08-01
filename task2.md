## # Task 2: Backend Authentication, Database Setup, & Category Seeder

### 📌 Deskripsi Tugas
Menghubungkan halaman Register & Login yang telah dibuat pada Task 1 dengan *backend* Laravel, mengonfigurasi migrasi database untuk tabel utama, serta membuat data kategori bawaan (*default*) menggunakan database seeder agar sistem siap digunakan.

### 💾 Spesifikasi Database & Relasi
1. **Tabel `users`**: Pastikan skema migrasi bawaan Laravel mencakup kolom `id`, `name`, `email` (unique), `password`, `created_at`, dan `updated_at`.
2. **Tabel `categories`**: Buat tabel baru dengan kolom:
   - `id` (bigint, Primary Key, Auto Increment)
   - `name` (varchar 100)
   - `type` (enum: 'income', 'expense')
3. **Tabel `transactions`**: Buat tabel baru dengan kolom:
   - `id` (bigint, Primary Key, Auto Increment)
   - `user_id` (bigint, Foreign Key ke `users.id` dengan aksi `ON DELETE CASCADE`)
   - `category_id` (bigint, Foreign Key ke `categories.id`)
   - `type` (enum: 'income', 'expense')
   - `amount` (decimal 15,2)
   - `description` (text, nullable)
   - `transaction_date` (date)
   - `created_at` & `updated_at` (timestamp)

### 📋 Alur Logika Backend & Fitur
* **Registrasi & Validasi**: Menerima input dari form Register. Validasi format email harus benar, email belum terdaftar, password minimal 8 karakter, dan konfirmasi password cocok. Lakukan *hashing* password, simpan ke database, lakukan login otomatis, lalu arahkan (*redirect*) ke `/dashboard`.
* **Login & Validasi**: Menerima input dari form Login. Validasi kecocokan kredensial, buat session login aktif, lalu arahkan ke `/dashboard`.
* **Keamanan Rute (Middleware)**: Amankan seluruh rute internal (`/dashboard`, `/transactions`, `/reports`) menggunakan middleware `auth` agar tidak dapat diakses tanpa login.
* **Database Seeder**: Buat seeder otomatis untuk mengisi data awal pada tabel `categories`:
  - *Kategori Pemasukan*: Gaji Part Time, Uang Saku, Beasiswa, Freelance, Lainnya.
  - *Kategori Pengeluaran*: Makan & Minum, Transportasi, Internet/Pulsa, Hiburan, Uang Kos, Print/Tugas Kuliah, Lainnya.

### 🎯 Expected Output (Deliverables)
1. File migrasi database (`users`, `categories`, `transactions`) dan file DatabaseSeeder.
2. Controller backend untuk memproses Register, Login, dan Logout.
3. Hak akses rute yang terproteksi middleware dengan benar.

---