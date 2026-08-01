## # Task 4: Transaction Management (CRUD Transaksi)

### 📌 Deskripsi Tugas
Membangun fitur inti dari SakuLog, yaitu manajemen transaksi keuangan (Pemasukan dan Pengeluaran) yang memungkinkan pengguna mencatat, melihat daftar lengkap, mengubah, dan menghapus riwayat keuangan mereka.

### 📋 Spesifikasi Fitur (CRUD)

1. **Form Tambah & Ubah Transaksi (Create & Update)**
   * *Fields*: Tanggal transaksi (default: hari ini), Jenis Transaksi (Pemasukan / Pengeluaran), Dropdown Kategori, Nominal (Amount), dan Keterangan/Catatan (opsional).
   * *Validasi Ketat*: Nominal wajib diisi, tidak boleh kosong, dan harus berupa angka positif lebih dari 0 (`amount > 0`). Sistem harus memvalidasi dan menolak angka minus di sisi backend maupun frontend.
   * *Dropdown Dinamis*: Jika pengguna memilih jenis "Pemasukan", dropdown kategori hanya memuat opsi kategori pemasukan. Jika memilih "Pengeluaran", dropdown otomatis berubah memuat opsi kategori pengeluaran.

2. **Riwayat Tabel Transaksi (Read)**
   * Menampilkan seluruh riwayat daftar transaksi pengguna dalam bentuk tabel monokrom yang rapi.
   * Lengkap dengan filter interaktif berdasarkan pilihan **Bulan** dan **Tahun** agar pengguna bisa melacak riwayat masa lalu.

3. **Hapus Transaksi (Delete)**
   * Pengguna dapat menghapus item transaksi yang salah input melalui tombol aksi di tabel riwayat.
   * Setelah data berhasil dihapus, nominal ringkasan Saldo di Dashboard harus otomatis terkalkulasi ulang dengan benar.

### 🎯 Expected Output (Deliverables)
1. Antarmuka Form Transaksi dan Halaman Tabel Riwayat Transaksi.
2. Logika CRUD pada Backend Controller lengkap dengan aturan validasi input form.