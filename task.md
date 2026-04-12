# Task List: Zahrababyandkids POS

## Fase 1: Manajemen Produk (SELESAI ✅)
- [x] Setup Konfigurasi Database `.env` (MySQL)
- [x] Membuat Model dan Migrations lengkap (9 Tabel)
- [x] Relasi Antar Model (Eloquent)
- [x] Membuat Base Layout UI (TailwindCSS Soft Pink & White, Bottombar Mobile-First)
- [x] Modul Master Data Kategori (Index, Create, Edit, Delete, Product Counter)
- [x] Modul Master Data Owner (Index, Create, Edit, Delete, Product Counter)
- [x] Modul Master Data Varian (Tambah Atribut dan Opsi Varian, relasi otomatis)
- [x] Modul Produk: Index (List Produk, Pagination, Filter Scroll, Search Box)
- [x] Modul Produk: Detail (Akses matrix harga, margin, dan limitasi modal)
- [x] Modul Produk: Create (Input manual/auto Generate Kode, Load Varian otomatis vis Checkbox, Auto Matrix Row)
- [x] Modul Produk: Edit (Dukungan penuh edit item varian dan Bulk Update Harga Seragam)

## Fase 2: Manajemen Transaksi / POS (SELESAI ✅)
- [x] Manajemen Data Customer (Pembuatan list nama, WA, & otomatisasi dropdown saat kasir)
- [x] Halaman Mesin Kasir / POS (Livewire Cart System Dinamis)
    - [x] Tombol List Produk Kasir (Pilih Barang dan Varian)
    - [x] Penyesuaian Harga per item (Jual Retail / Grosir Sell) 
    - [x] Diskon per item atau Diskon Global
- [x] Logika Kalkulasi Transaksi (Hitung Bruto, Laba Bersih, Biaya Packing, Ongkir)
- [x] Implementasi Snapshot System (Menyimpan riwayat Harga Modal & Item demi mencegah perubahan mundur)
- [x] Sistem Cetak Struk (Print Window ready untuk 58mm POS thermal)
- [/] Fitur Rollback/Retur (Pengembalian stok saat transaksi batal - Ditangani bertahap)

## Fase 3: Dashboard & Laporan (IN-PROGRESS ⏳)
- [/] Dashboard Indikator (Total Penjualan Hari/Bulan ini & Barang Terjual)
- [/] Tombol Quick Access ke halaman tersembunyi (List Transaksi, Varian)
- [ ] Laba Bersih & Statistik Pendapatan
- [ ] Laporan Rekap Hutang Titipan Konsinyasi per-Owner
