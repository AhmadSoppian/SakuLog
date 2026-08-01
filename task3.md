## # Task 3: Dashboard UI & Data Summary

### 📌 Deskripsi Tugas
Membangun antarmuka dan logika backend untuk halaman utama setelah user berhasil masuk, yaitu **Dashboard**. Halaman ini berfungsi memberikan ringkasan kondisi keuangan pengguna pada bulan berjalan secara *real-time*.

### 🎨 UI/UX Guidelines
* Tetap konsisten menggunakan tema **Monokromatik (Hitam dan Putih)**, minimalis, dan bersih (*clean*).
* Gunakan ruang kosong (*white space*) secara optimal dan tampilkan ringkasan angka dalam bentuk kartu komponen (*cards*) ber-kontras tajam.
* Desain layout wajib responsif (*mobile-friendly*).

### 📋 Spesifikasi Fitur & Data Dashboard
Halaman Dashboard harus menghitung dan menampilkan data berikut secara dinamis sesuai dengan data milik pengguna yang sedang login:
1. **Saldo Saat Ini**: Akumulasi total keseluruhan menggunakan rumus: `Total Semua Pemasukan - Total Semua Pengeluaran`.
2. **Total Pemasukan Bulan Ini**: Jumlah nominal uang masuk khusus pada bulan dan tahun berjalan saat ini.
3. **Total Pengeluaran Bulan Ini**: Jumlah nominal uang keluar khusus pada bulan dan tahun berjalan saat ini.
4. **Histori Transaksi Terbaru**: Menampilkan tabel atau daftar pendek berisi **5-10 transaksi terakhir** yang diinput oleh pengguna.
5. **Aksi Cepat**: Menyediakan tombol pintasan untuk "Tambah Transaksi" dan tombol "Logout".

### 🎯 Expected Output (Deliverables)
1. Komponen UI Halaman Dashboard (Desktop & Mobile).
2. Query backend atau controller yang mengalkulasi Saldo dan ringkasan Bulanan secara akurat berdasarkan user id yang sedang aktif.

---