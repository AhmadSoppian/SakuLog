# Task 1: Frontend Development - Halaman Register & Login (SakuLog)

## 📌 Deskripsi Tugas
[cite_start]SakuLog adalah aplikasi pencatatan keuangan pribadi berbasis web yang membantu pengguna, khususnya mahasiswa, mengelola pemasukan dan pengeluaran[cite: 1, 2]. Pada tahap pertama ini, tugas Anda adalah membangun antarmuka (*frontend*) untuk sistem autentikasi pengguna, yaitu halaman Register dan Login.

## 🎨 UI/UX Guidelines
* **Tema Desain:** Modern, bersih (*clean*), dan minimalis.
* **Skema Warna:** Monokromatik (Hitam dan Putih). Gunakan ruang kosong (*white space*) secara maksimal untuk memberikan kesan rapi, serta kontras yang tajam antara teks, tombol, dan latar belakang.
* **Responsivitas:** Wajib *mobile-friendly* dan menyesuaikan dengan baik di ukuran layar *desktop*.

## 📋 Spesifikasi Halaman

### 1. Halaman Register (Pendaftaran)
Buat *form* pendaftaran dengan *input fields* yang disesuaikan dengan kebutuhan tabel *database*:
* **Nama Lengkap** (Sesuai kolom `name`)
* **Email** (Sesuai kolom `email`)
* **Password** (Sesuai kolom `password`)
* **Konfirmasi Password** (Untuk validasi kecocokan *password* di sisi klien)
* **Tombol Submit:** Berlabel "Daftar" atau "Register".
* **Navigasi:** Tambahkan teks "Sudah punya akun? Masuk di sini" yang dapat diklik dan mengarah ke halaman Login.

### 2. Halaman Login (Masuk)
Buat *form* masuk dengan *input fields* berikut:
* **Email**
* **Password**
* **Tombol Submit:** Berlabel "Masuk" atau "Login".
* **Navigasi:** Tambahkan teks "Belum punya akun? Daftar di sini" yang dapat diklik dan mengarah ke halaman Register.


## 💾 Referensi Skema Database Tabel `users`
*Form* yang dibuat harus selaras dengan struktur tabel `users` di *database* berikut (sebagai acuan untuk pembuatan *payload* / state data):
* `id` (interger primary key)
* `name` (String)
* `email` (String, Unique)
* `password` (String / Hashed)
* `created_at` (Timestamp)
* `updated_at` (Timestamp)




## 🎯 Expected Output (Deliverables)
1.  Kode *frontend* (komponen UI) untuk halaman Register dan Login.
2.  Desain yang secara ketat mengimplementasikan skema warna hitam dan putih.
3.  Validasi *form* di sisi klien (contoh: format email harus valid, *password* minimal 8 karakter, konfirmasi *password* harus sesuai).