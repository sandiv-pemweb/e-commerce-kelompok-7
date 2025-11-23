<p align="center">
  <a href="https://github.com/mamelilea/e-commerce-uap-pemweb.git">
    <h1 align="center">E-Commerce UAP</h1>
  </a>
</p>

Repository ini merupakan proyek Laravel 12 yang sudah dilengkapi dengan Laravel Breeze sebagai starter kit untuk fitur autentikasi, serta struktur database yang telah disediakan. Tugas Anda adalah mengembangkan fitur sesuai instruksi dengan menggunakan repository ini sebagai dasar. Setelah implementasi selesai, silakan ajukan Pull Request berisi hasil pekerjaan tim Anda. Pull Request tersebut nantinya akan diperiksa dan dinilai oleh asisten praktikum.

## Penjelasan tugas
Anda diminta untuk membuat antarmuka CRUD sederhana untuk aplikasi E-Commerce dengan beberapa halaman berikut:

Halaman Pengguna (Customer Side)
1. **Homepage:** Berisi daftar produk, termasuk:
    - Daftar seluruh produk
    - Daftar produk berdasarkan kategori
2. **Halaman Detail Produk:** Menampilkan satu produk beserta detailnya, seperti deskripsi, gambar, kategori, dan ulasan.
3. **Halaman Checkout:** Pengguna mengisi alamat, memilih jenis pengiriman, dan menyelesaikan pembelian.
4. **Halaman Riwayat Transaksi (Opsional) :** Menampilkan riwayat pembelian dan detail transaksinya.

Halaman Toko (Seller Dashboard):
1. **Halaman Registrasi Toko:** Penjual membuat profil toko
2. **Halaman Manajemen Pesanan:** Melihat dan memperbarui pesanan masuk, informasi pengiriman, serta nomor resi.
3. **Halaman Saldo Toko:** Melihat saldo dan riwayat perubahan saldo.
4. **Halaman Penarikan Saldo:** Mengajukan penarikan dan melihat riwayat penarikan, termasuk:
    - Mengelola (mengubah) nama bank, nama pemilik rekening, dan nomor rekening
5. **Halaman Manajemen Toko:** Untuk penjual mengelola tokonya, termasuk:
    - Mengelola (ubah/hapus) profil toko
    - Mengelola (buat/ubah/hapus) produk
    - Mengelola (buat/ubah/hapus) kategori produk
    - Mengelola (buat/ubah/hapus) gambar produk

Halaman Admin (Owner of e-commerce):
1. **Halaman Verifikasi Toko:** Memverifikasi atau menolak pengajuan pembuatan toko.
2. **Halaman Manajemen Pengguna & Toko:** Melihat dan mengelola seluruh pengguna dan toko yang terdaftar.

#### Poin Nilai Tambah (Opsional)
Kelompok dapat memperoleh nilai tambahan apabila mengimplementasikan beberapa hal berikut:
1. UI rapi dan responsif
Tampilan antarmuka dibuat konsisten, bersih, dan mendukung berbagai resolusi layar.
2. Menambahkan fitur opsional non-wajib, seperti:
   - Wishlist
   - Search produk
   - Filter harga
   - Dashboard grafik (visualisasi data penjualan/produk)
3. Penerapan clean code & struktur proyek yang baik
   - Menggunakan service layer
   - Repository pattern
   - Resource (API Resource / View Resource)
   - Struktur file rapi dan mudah di-maintain

## Struktur Database
![db structure](https://github.com/WisnuIbnu/E-Commerce-pemweb-uap/blob/main/public/db_structure.png?raw=true)

## Prasyarat

Untuk menjalankan proyek ini, Anda memerlukan:

-   PHP >= 8.2
-   Composer
-   NPM
-   Database server (MySQL, MariaDB, PostgreSQL, or SQLite)

## Instalasi

Ikuti langkah-langkah berikut untuk melakukan instalasi dan menjalankan proyek dalam lingkungan pengembangan di komputer lokal Anda:

1. Clone repository versi terbaru dari sumber yang diberikan:

```bash
git clone https://github.com/mamelilea/e-commerce-uap-pemweb.git
```

2. Instal dependensi PHP menggunakan Composer:
```bash
composer install
```
3. Salin file .env.example menjadi .env lalu sesuaikan konfigurasi database:
```bash
cp .env.example .env
```
4. Generate application key:
```bash
php artisan key:generate
```
5. Jalankan migrasi database:
```bash
php artisan migrate
```
Jika ingin menambahkan data dummy, gunakan:
```bash
php artisan migrate --seed
```
6. Jalankan development server Laravel:
```bash
php artisan serve
```
7. buka terminal yang lain (terminal ada 2), Pada terminal lain, install semua modul Node.js dan lakukan build:
```bash
npm install
npm run build
```
8. Kompilasi asset dalam mode pengembangan:
```bash
npm run dev
```
9. Buka browser dan akses aplikasi:
```bash
http://localhost:8000
```

## Pengumpulan Tugas:

1. Fork repository dengan nama e-commerce-kelompok-x
(ganti x dengan nomor kelompok Anda).
2. Selesaikan seluruh tugas yang telah ditentukan sesuai instruksi.
3. Buat Pull Request ke branch main pada repository kami dengan membawa semua perubahan yang telah Anda kerjakan.

## Format Judul Pull Request
Gunakan format berikut untuk judul PR:
```bash
[kelompok-x] Implementasi Fitur E-Commerce
```
Contoh:
```bash
[kelompok-1] Implementasi Fitur E-Commerce
```

## Format Deskripsi Pull Request
Gunakan template berikut:
```bash
## Ringkasan Pengerjaan
Jelaskan secara singkat apa saja yang telah dikerjakan pada tugas ini.

## Fitur yang Diimplementasikan
- [ ] Halaman Homepage
- [ ] Halaman Produk
- [ ] Checkout
- [ ] Riwayat Transaksi (opsional)
- [ ] Registrasi Toko
- [ ] Manajemen Pesanan
- [ ] Saldo Toko
- [ ] Penarikan Saldo
- [ ] Manajemen Toko (produk, kategori, gambar)
- [ ] Verifikasi Toko (Admin)
- [ ] Manajemen User & Toko (Admin)

*(Checklist dihapus atau ditandai sesuai progress kelompok kalian.)*

## Catatan Tambahan
Tambahkan hal penting seperti:
- Fitur yang belum selesai
- Kendala yang ditemui
- Hal yang perlu direview khusus
```

<h3 align="center">tetap semangat dan sukses selalu php lovers💕</h3>
