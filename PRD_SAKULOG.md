# Product Requirements Document (PRD)
# SakuLog - Personal Cash Flow Management System

## 1. Product Overview
### Product Name
SakuLog

### Product Description
SakuLog adalah aplikasi berbasis web yang membantu pengguna mencatat pemasukan dan pengeluaran pribadi, memantau saldo terkini, serta menghasilkan laporan keuangan bulanan secara otomatis. 

Aplikasi ini ditujukan untuk individu (khususnya mahasiswa) yang ingin mengelola keuangan pribadi dengan lebih terstruktur tanpa perlu menggunakan pencatatan manual di buku atau spreadsheet.

---

## 2. Background
Banyak pengguna (terutama mahasiswa) mengalami kesulitan dalam mengelola keuangan pribadi karena tidak memiliki sistem pencatatan yang terorganisir. Akibatnya pengguna tidak mengetahui:
- Total pemasukan yang diterima (dari part-time, beasiswa, dll).
- Total pengeluaran yang telah dilakukan.
- Saldo yang tersedia saat ini.
- Pola pengeluaran bulanan.
- Kondisi keuangan secara keseluruhan.

SakuLog hadir sebagai solusi untuk membantu pengguna memonitor arus kas secara sederhana dan efektif.

---

## 3. Problem Statement
Pengguna sering mengalami masalah berikut:
1. Tidak mengetahui jumlah pemasukan yang diterima setiap bulan.
2. Tidak mengetahui total pengeluaran yang telah dilakukan.
3. Sulit menghitung saldo yang tersisa secara akurat.
4. Tidak memiliki laporan keuangan bulanan yang terstruktur.
5. Pencatatan masih dilakukan secara manual sehingga rentan terjadi kesalahan atau terselip.

---

## 4. Goals
### Business Goal
- Menyediakan sistem pencatatan keuangan pribadi yang mudah dan intuitif untuk digunakan.
- Membantu pengguna mengelola keuangan secara lebih disiplin dan teratur.

### User Goal
- Mencatat pemasukan dengan cepat.
- Mencatat pengeluaran dengan mudah.
- Melihat saldo saat ini secara otomatis tanpa hitung manual.
- Melihat laporan bulanan secara real-time.
- Mengetahui kategori pengeluaran terbesar.

---

## 5. Target User
### Primary User: Mahasiswa / Daily Worker
Karakteristik:
- Memiliki pemasukan tidak tetap atau multi-sumber (beasiswa, part-time, freelance, atau uang saku).
- Memiliki pengeluaran rutin harian dan bulanan (kos, makan, tugas kuliah).
- Membutuhkan aplikasi pencatatan keuangan yang super sederhana (tidak rumit seperti akuntansi korporat).

---

## 6. User Stories
- **Autentikasi:** Sebagai pengguna, saya ingin mendaftarkan akun dan masuk ke sistem agar data keuangan saya aman dan tidak tercampur dengan orang lain.
- **Dashboard:** Sebagai pengguna, saya ingin melihat ringkasan keuangan (saldo, total masuk, total keluar) dengan cepat begitu membuka aplikasi.
- **Transaksi Pemasukan & Pengeluaran:** Sebagai pengguna, saya ingin menambah, mengubah, dan menghapus data transaksi agar histori pencatatan saya selalu akurat.
- **Laporan & Visualisasi:** Sebagai pengguna, saya ingin melihat visualisasi grafik pengeluaran bulanan agar saya tahu ke mana perginya uang saya.

---

## 7. Functional Requirements

### FR-01 Autentikasi (Register, Login, Logout)
Sistem menyediakan fitur manajemen akun pengguna.
- **Acceptance Criteria:**
  - Registrasi membutuhkan Nama, Email unik, dan Password yang kuat.
  - Login membutuhkan Validasi Email dan Password.
  - Pengguna diarahkan ke Dashboard setelah berhasil login.
  - Halaman internal (Dashboard & Transaksi) terlindungi oleh middleware auth (tidak bisa diakses tanpa login).

### FR-02 Dashboard Interaktif
Sistem menampilkan ringkasan keuangan di bulan berjalan.
- **Data yang Ditampilkan:**
  - Saldo Saat Ini (Akumulasi keseluruhan)
  - Total Pemasukan (Khusus bulan ini)
  - Total Pengeluaran (Khusus bulan ini)
  - Histori transaksi terbaru (Limit 5-10 transaksi terakhir)

### FR-03 Manajemen Transaksi (CRUD)
Pengguna dapat mengelola data transaksi mereka sendiri.
- **Aturan Input Data:**
  - Tanggal (Default: Hari ini)
  - Jenis Transaksi (Pemasukan / Pengeluaran)
  - Kategori (Dropdown dinamis sesuai jenis transaksi)
  - Nominal (Wajib diisi, harus angka positif > 0)
  - Keterangan (Opsional)

### FR-04 Kategori Transaksi Bawaan (Default)
Sistem menyediakan kategori awal yang relatable dengan mahasiswa:
- **Kategori Pemasukan:** Gaji Part Time, Uang Saku, Beasiswa, Freelance, Lainnya.
- **Kategori Pengeluaran:** Makan & Minum, Transportasi, Internet/Pulsa, Hiburan, Uang Kos, Print/Tugas Kuliah, Lainnya.

### FR-05 Perhitungan Saldo Otomatis
Sistem menghitung saldo pengguna secara otomatis menggunakan formula matematika dasar:
`Saldo Saat Ini = Total Semua Pemasukan - Total Semua Pengeluaran`

### FR-06 Laporan & Filter Bulanan
Pengguna dapat memfilter dan melihat total pencatatan berdasarkan Bulan dan Tahun tertentu.

### FR-07 Grafik Keuangan (Visualisasi)
Sistem menampilkan visualisasi data pada halaman laporan:
1. **Pie Chart:** Distribusi persentase pengeluaran berdasarkan kategori.
2. **Bar Chart:** Perbandingan total pemasukan vs pengeluaran dari bulan ke bulan.

### FR-08 Insight Keuangan Sederhana
Sistem memberikan text-insight otomatis berbasis data, seperti:
- *"Pengeluaran terbesar Anda bulan ini ada di kategori **Makan & Minum**."*

---

## 8. Non-Functional Requirements
- **Performance:** Halaman utama dan laporan harus dimuat dalam waktu kurang dari 3 detik.
- **Security:** Password wajib di-hash menggunakan algoritma aman (Bcrypt). Proteksi terhadap CSRF dan SQL Injection.
- **Usability:** Antarmuka bersih, minimalis (konsep hitam-putih/modern modern), dan responsif diakses via Smartphone (Mobile-friendly).
- **Data Integrity:** Penghapusan data transaksi akan langsung mengkalkulasi ulang total saldo secara real-time.

---

## 9. Database Requirements

### Tabel: users
| Field | Tipe | Keterangan |
|---|---|---|
| id | bigint (PK) | Auto Increment |
| name | varchar(255) | |
| email | varchar(255) | Unique |
| password | varchar(255) | Hashed |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: categories
| Field | Tipe | Keterangan |
|---|---|---|
| id | bigint (PK) | Auto Increment |
| name | varchar(100) | |
| type | enum('income', 'expense')| |

### Tabel: transactions
| Field | Tipe | Keterangan |
|---|---|---|
| id | bigint (PK) | Auto Increment |
| user_id | bigint (FK) | Relasi ke `users(id)` ON DELETE CASCADE |
| category_id | bigint (FK) | Relasi ke `categories(id)` |
| type | enum('income', 'expense')| |
| amount | decimal(15,2) | Angka positif |
| description | text | Nullable |
| transaction_date | date | |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## 10. MVP Scope (Fase 1)
Fitur awal yang wajib selesai:
- Fitur Auth (Register, Login, Logout)
- Dashboard Ringkasan Saldo
- CRUD Transaksi Keuangan
- Grafik Pie Chart Pengeluaran Bulanan

## 11. Future Enhancements (Fase 2)
- Eksport laporan ke PDF / Excel
- Fitur Set Target Tabungan (Budgeting Goal)
- Peringatan (Alert) jika pengeluaran membengkak melebihi batas bulanan.
- Dark Mode toggle.

## 12. Success Metrics
- Pengguna berhasil mencatat transaksi dalam waktu kurang dari 10 detik.
- Selisih perhitungan saldo sistem dengan uang riil pengguna adalah 0 (Akurat).
- Grafik berhasil memetakan pola pengeluaran tanpa error data *null*.