# 🕵️‍♂️ Laporan Pengujian Khusus (Testing Checklist) - HRD System

Berikut adalah rancangan tes skenario menyeluruh (*End-to-End Testing*) untuk memvalidasi dan me-*review* secara mandiri bahwa tidak ada *bug* logika pada fitur di sistem HRD ini. Silakan gunakan daftar ini sebagai panduan pengetesan.

---

## 1. 🔐 Autentikasi & Kontrol Peran (*Auth & Authorization*)

- [ ] **Login Multi-User**: Berhasil login dengan `admin@hrd.test`, `hrd@hrd.test`, dan `staff@hrd.test`.
- [ ] **Proteksi Route Akses Admin**: Pastikan akun HRD/Staff jika memaksa masuk ke URL `/users` atau `/roles` akan diblokir (diarahkan kembali ke Dashboard dengan indikator _Access Denied_/403).
- [ ] **Perlindungan Data**: Pastikan akun Staff tidak bisa melihat histori spesifik Gaji milik karyawan lain atau melihat Menu Pegawai.
- [ ] **Logout**: Proses _logout_ berhasil dijalankan tanpa meninggalkan *session cookie* aktif (tombol _Back_ browser tidak bisa mengakses *dashboard* kembali).
- [ ] **Halaman Profile**: Mampu mengganti informasi diri dan berhasil menjalankan penggantian _Password_ melalui halaman *Card* Premium di `profile/edit`.

## 2. 📊 Dashboard Analytics

- [ ] **Tampilan Cerdas Admin**: Admin dan HRD melihat *insight overview* semua total Karyawan Aktif, Tabel _Recent Attendances_, absensi hari ini, hingga antrean *pending leaves* keseluruhan di Dashboard.
- [ ] **Tampilan Self-Service Staff**: Jika *login* sebagai Staff, tidak melihat menu Karyawan, tetapi melihat "Absensi Hari Ini", limitasi fitur pengajuan cuti pribadi, limit riwayat pribadi. 
- [ ] **Render HTML Realtime**: Tombol *Check In* lenyap berganti ke *Check Out* (warna oranye) setelah karyawan mensubmit absen paginya. Detail waktu tercantum rapi di kartu tanpa _glitch text_.

## 3. 👥 Master Karyawan (*Employee Management*)

- [ ] **Create Pegawai**: Admin bisa menambahkan `User` baru, meng-set Password dan otomatis mengelola jenis Role (Staff/HRD).
- [ ] **Update Profil Karyawan**: Admin/HRD bisa memperbarui e-mail dan jenis _role_ karyawan lama. Data akan terganti dinamis.
- [ ] **Pemberhentian Karyawan**: Jika Admin menghapus/mendelete `User`, `Attendance`, `Leave`, `Salaries` ikut terhapus (*Constraint Cascade*) agar tidak menyisakan *orphan data* di database.

## 4. ⏰ Modul Kehadiran (*Attendance System*)

- [ ] **Staff Check-In**: Berhasil menginput jam masuk (`created_at` atau *system timestamp* berfungsi). Jika sudah _Check-in_, tidak boleh ada opsi klik *Check-in* ulangan pada hari itu.
- [ ] **Deteksi Otomatis Terlambat**: Karyawan jika absen/cek-in melewati batas sistem jam masuk perusahaan (contoh: 08:00 AM) langsung ditandai berlabel `Late`, di bawah itu `Present`.
- [ ] **Staff Check-Out**: Berhasil merekam jam pulang dan merender lama kerja (_work_duration_) yang akurat. Tidak bisa check-out jika belum melakukan check-in hari ini.
- [ ] **Dashboard Laporan HRD**: List absensi (`/attendances`) mampu di-filter HRD dan menampilkan riwayat status (Alfa/Terlambat/Hadir) secara akurat dari seluruh departemen. 

## 5. 🛫 Cuti & Hari Libur (*Leaves & Holidays*)

- [ ] **Form Pengaturan Saldo (`Leave Balance`)**: HRD dipastikan dapat memberi/memperbarui jatah tahunan libur cuti Karyawan.
- [ ] **Pengajuan Cuti Valid**: Karyawan bisa mengajukan hari cuti "Rencana". Pastikan form tidak *error* jika *Start Date* lebih mundur (kurang dari) dari *End Date*.
- [ ] **Aksi Persetujuan HRD (Approval Flow)**:
    - Tes HRD klik **Approve** pada Cuti Staff A. Saldo Jatah (_Used Days_) Staff A dipastikan harus ter-update dan bertambah.
    - Tes HRD klik **Reject**. Saldo Jatah Staff A tidak boleh berubah (tetap).
- [ ] **Sinkronisasi Hari Libur (`Holidays`)**: Pastikan penambahan Hari Libur Nasional terekam kuat dan diabaikan dari hitungan hari memotong cuti/absen (opsional tergantung logic _working_days_ selanjutnya).

## 6. 💵 Kalkulasi Penggajian (*Payroll Calculation*) & PDF

- [ ] **Mendaftarkan Gaji Pegawai**: Admin di URL `/salary` berhasil mengisi Nominal Gaji Pokok untuk Pegawai baru. Inputan di-format angka Rupiah atau Integer.
- [ ] **Komponen Gaji Tetap & Dinamis**: Mampu menyimpan variasi Potongan BPJS `deduction` dan Tunjangan Uang Makan `allowance`. Parameter `is_recurring` terekam rapi.
- [ ] **Auto-generate Take Home Pay (Slip)**: Di menu *Generate Slip*, ketika HRD memilih Bulan dan Tahun, seluruh Tunjangan _ditambahkan_ dan Potongan _dikurangkan_ langsung dari Nominal Gaji Dasar secara eksak sempurna!
- [ ] **Cetak CSS Print (PDF)**: Tombol *Print* atau *Save as PDF*, bisa menyembunyikan Sidebar & Header dari Navigasi, secara eksklusif hanya menyisakan kerangka Lembar Tanda Terima Slip Gaji yang bersih untuk dicetak/didownload.

## 7. 🎨 UI/UX & Responsive Dark Mode

- [ ] **Kontras & Estetika**: Layout sistem di layar *Desktop* dapat menampilkan navigasi utuh dengan estetika warnanya (Tailwind *slate-900 / indigo*).
- [ ] **Navigasi Versi Ponsel (Mobile/Tablet Responsive)**: Jika layar HP (*viewport* menyusut), _Sidebar_ dapat tertutup atau elemen Grid menyempit (*stack vertically* 1 kolom per baris) agar kartu *Dashboard Analytics* tetap terbaca utuh.

---
_Gunakan lembar Checklist Modul Pengujian QA (Quality Assurance) ini sebagai sandaran untuk validasi setiap aksi di aplikasi berjalan baik di server lokal (`localhost:8000`) dan meminimalisir_ *Bug* _logika lanjutan._
