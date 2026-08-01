## # Task 5: Monthly Report, Visualization & Financial Insights

### 📌 Deskripsi Tugas
Tahap akhir dari MVP SakuLog. Membuat halaman Laporan Bulanan yang menyajikan grafik visualisasi data pengeluaran dan memberikan teks kesimpulan (*insight*) otomatis mengenai kondisi keuangan pengguna.

### 📋 Spesifikasi Fitur Laporan & Visualisasi

1. **Filter Laporan**
   * Sediakan komponen navigasi dropdown untuk memilih **Bulan** dan **Tahun** pada bagian atas halaman laporan.

2. **Grafik Visualisasi Data** (Disarankan menggunakan library Chart seperti *Chart.js* atau *ApexCharts*)
   * **Pie Chart (Grafik Lingkaran)**: Menampilkan distribusi persentase total pengeluaran berdasarkan kategori pada bulan yang dipilih (Contoh: Makan 50%, Uang Kos 30%, Transportasi 20%).
   * **Bar Chart (Grafik Batang)**: Perbandingan total akumulasi uang masuk vs uang keluar dari bulan ke bulan untuk melihat tren arus kas.

3. **Teks Insight Keuangan Otomatis**
   Buat logika kondisional sederhana berbasis teks untuk mendeteksi kondisi keuangan user pada bulan tersebut, contohnya:
   * *"Pengeluaran terbesar Anda bulan ini ada pada kategori **Makan & Minum**."*
   * *Warning Alert*: *"Waspada! Pengeluaran Anda bulan ini lebih besar daripada pemasukan Anda."* (Muncul jika total pengeluaran bulan ini > total pemasukan bulan ini).

### 🎯 Expected Output (Deliverables)
1. Halaman UI Laporan yang bersih dengan integrasi library grafik chart.
2. Logika backend untuk melakukan *grouping* data transaksi berdasarkan kategori demi kebutuhan penyuapan data (*data feeding*) grafik Pie Chart.
3. Komponen teks insight dinamis yang muncul sesuai dengan kondisi riil data keuangan user.