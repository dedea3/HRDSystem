# Sistem Manajemen HRD - Dokumentasi & Tutorial Panduan Penggunaan

Selamat! Proyek **Sistem Manajemen HRD** (Human Resource Development) berbasis web telah selesai 100% dan siap digunakan. Dokumen ini berisi detail komprehensif dari fitur yang saya selesaikan, hingga cara pengoperasian lokal.

---

## 🚀 Fitur yang Telah Diselesaikan
Proyek ini dikembangkan dengan **Laravel 12 (Backend), Tailwind CSS & Alpine.js (Frontend)** yang menggunakan antarmuka *Premium Dark Mode*.

1. **Multi-Role Authentication & Otorisasi Lengkap**
   - Merombak bawaan Laravel Breeze menjadi layout *custom dark mode UI*.
   - Middleware ketat yang membatasi hak akses berdasarkan peran: **Admin System, HRD Manager, dan Staff Karyawan.**

2. **Manajemen Karyawan (Pegawai)**
   - Akses CRUD penuh data karyawan bagi sentral Admin.
   - Pendaftaran *Role* khusus sesuai hierarki.

3. **Sistem Manajemen Presensi & Absensi**
   - Pengajuan _Check-in_ dan _Check-out_ mandiri oleh Staff di Dashboard.
   - Kalkulasi logika otomatis waktu keterlambatan dengan lencana dinamis (*Hadir* vs *Terlambat*).
   - Penginputan riwayat absen secara manual oleh tim HRD.

4. **Kalkulasi Penggajian Terotomatisasi (Payroll) & Slip Gaji PDF**
   - Mendefinisikan **Gaji Pokok** (*Basic Salary*) tiap-tiap pegawai.
   - Penambahan Komponen Gaji (*Allowance*/Tunjangan & *Deduction*/Potongan) dari skema rutin (BPJS/Transport) atau tambahan sekali potong.
   - Mencetak kalkulasi otomatis _Take Home Pay_.
   - Tombol ekspor otomatis ke **PDF Slip Gaji**, menggunakan perombakan struktur `@media print` tersembunyi.

5. **Sistem Terpadu Cuti & Persetujuan Berjenjang**
   - Staff dipermudah mengajukan _Leaves_ (Cuti Tahunan, Sakit, Pribadi).
   - Verifikasi berjenjang oleh tim HRD/Admin (Approve atau Reject).
   - Saldo jatah cuti karyawan terpotong akurat layaknya logika sistem perbankan.

6. **Desain UI/UX Premium & Estetika Real-time**
   - *Landing Page* (`welcome.blade.php`) telah saya desain elegan sebagai representasi bisnis modern, lengkap dengan Call-To-Action pintar.
   - Komponen "Edit Profile" disulap ke dalam *wrapper card* yang membaur dengan warna dominan dalam Dashboard.

---

## 🛠️ Cara Menjalankan & Reset Data Simulasi (Tutorial)

Aplikasi dirancang untuk langsung siap dicoba, dikarenakan sudah menginjeksi Data Dummy (Contoh) secara otomatis dari kecerdasan sistem _Seeder_ kami.

### 1. Eksekusi Reset Database & Fake Data Seeder Cerdas
Langkah ini sangat penting jika Anda ingin mencoba aplikasi untuk **demo**. Semua data karyawan, presensi acak 10 hari terakhir, hingga histri komplit gaji akan otomatis diisi.
Jalankan di dalam Terminal Lokal Anda:
```bash
php artisan migrate:fresh --seed
```

### 2. Nyalakan Lingkungan Web (Vite & Laravel)
Jika server belum menyala, buka minimal 2 tab Terminal secara paralel:
- **Terminal 1 (Jalankan Server PHP Backend):**
  ```bash
  php artisan serve
  ```
- **Terminal 2 (Jalankan *Watcher* Tailwind CSS Frontend):**
  ```bash
  npm run dev
  ```
_*(Tidak perlu menjalankan server CSS apabila Anda men-deploy ke *Production*, cukup jalankan perintah `npm run build` sekali saja)*._

### 3. Akses Website Live
Jalankan Chrome/Firefox atau browser favorit Anda dan kunjungi tautan lokal server:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## 🔐 Akun Akses Karyawan (Role-Based Demo)

Ketika *Seed/Reset* database yang Anda pelajari sukses, tiga (3) akun di *level* hirarki berbeda secara *default* sudah tercipta dengan *Password* seragam untuk mempermudah tes Anda.

> **Kata Sandi (Password) untuk semua akun di bawah adalah:** `password`

### 1. Admin System (Akses Penuh) 👑
Bertanggung jawab atas pengendalian parameter kunci Master Database.
- **Login Email:** `admin@hrd.test`
- **Terkunci Pada:** Akses konfigurasi Master Gaji Basis, Manajemen Karyawan Lengkap, Hak Approval Lintas Operasional, Pembuatan Slip Gaji, dll.

### 2. HRD Manager (Akses Kontrol Pegawai) 👔
Bertanggung jawab pada supervisi harian rutinitas operasional & hari libur pekerja.
- **Login Email:** `hrd@hrd.test`
- **Terkunci Pada:** Perekaman Absensi Manual, Konfirmasi/Tolak Cuti Staff, Manajemen Kuota Cuti Tahunan, Sinkronisasi Komponen Hari Libur Resmi.

### 3. Staff Karyawan (Akses Personal Self-Service) 👨‍💻
Bertanggungjawab untuk portal pekerja murni (diri sendiri).
- **Login Email:** `staff@hrd.test`
- **Terkunci Pada:** Absen Elektronik *Check In/Out Realtime*, Rekap Unduh Laporan PDF Histori Gaji Mandiri, Laporan Update Info Personal Akun Pribadi, dan Fitur Status Lacak Cuti.

---

🌟 **Selesai! Sistem Manajemen Berbasis Cloud Anda kini sudah prima 100%.**
Apabila ke depannya Anda berkenan membedah fitur ekspansif lainnya (Sinkronisasi Mesin Fingerprint, Rekap Pivot Table via Microsoft Excel, atau Broadcast Pemberitahuan via Saluran Telegram/WhatsApp), jangan ragu menyampaikan permintaan itu.
