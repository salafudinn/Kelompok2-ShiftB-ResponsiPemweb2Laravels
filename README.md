# LaboRa

> Sistem Informasi Peminjaman, Kalkulasi Denda Keterlambatan, dan Pelaporan Kerusakan IoT Kit Laboratorium

---

## Informasi Kelompok

- **Nomor Kelompok:** Kelompok 2
- **Shift Praktikum:** Shift B

---

## Anggota Kelompok

| No | Nama Lengkap | NIM | Shift Awal | Shift Akhir | Jobdesk / Kontribusi | Link Video |
|---|---|---|---|---|---|---|
| 1 | Fachriel Yoga Wicaksono | H1H024042 | Shift A | Shift B | Integrasi Frontend & Autentikasi Sanctum | [Link Video](#) |
| 2 | Wendy Virtus | H1H024048 | Shift B | Shift B | Desain Database, Migrasi, & Seeder | [Link Video](https://youtu.be/3FkmslBWlp4) |
| 3 | Muhammad Raihan Izzuddin Ismadi | H1H024056 | Shift D | Shift B | RESTful API IoT Kits & Damage Reports | [Link Video](#) |
| 4 | Muhammad Imam Salafudin | H1H024067 | Shift D | Shift B | CRUD Komponen Peminjaman Iot Kits | [Link Video](https://youtu.be/KLPRGgiluw8) |

---

## Deskripsi Aplikasi

**LaboRa** adalah aplikasi web berbasis RESTful API (Laravel 13) yang dirancang untuk mendigitalisasi proses peminjaman peralatan IoT di laboratorium kampus secara terstruktur dan akuntabel.

---

## Permasalahan yang Diangkat

Pengelolaan inventaris alat laboratorium (terutama perangkat IoT) masih dilakukan secara manual menggunakan logbook kertas, yang menyebabkan berbagai masalah nyata:

- Mahasiswa tidak tahu **lokasi fisik alat** disimpan di rak/lemari mana
- Tidak ada pencatatan digital tentang **siapa meminjam apa dan kapan**
- Pengembalian terlambat **tidak memiliki konsekuensi otomatis** (tidak ada denda)
- Kerusakan komponen tidak terdokumentasi sehingga alat rusak dibiarkan tanpa penanganan

---

## Solusi yang Ditawarkan

Sistem informasi digital dengan alur proses bisnis terdefinisi penuh:

1. **Mahasiswa** melihat katalog alat beserta lokasi rak fisiknya, lalu mengajukan peminjaman
2. **Asisten Lab** menyetujui atau menolak pengajuan — stok otomatis berkurang saat disetujui
3. Asisten Lab mencatat serah terima barang fisik ke mahasiswa (status → `ON_LOAN`)
4. Jika terlambat dikembalikan, sistem **otomatis menghitung denda** Rp5.000/hari
5. Mahasiswa atau Asisten Lab dapat melaporkan kerusakan — status alat berubah ke `MAINTENANCE`

---

## Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| **Backend Framework** | Laravel 13 |
| **Bahasa Pemrograman** | PHP 8.5 |
| **Database** | MySQL / MariaDB |
| **ORM** | Eloquent ORM |
| **Autentikasi API** | Laravel Sanctum (Bearer Token) |
| **Frontend** | Blade + HTML/CSS/JavaScript (Fetch API) + Tailwind CSS |

---

## Cara Menjalankan Aplikasi

```bash
# 1. Clone repository
git clone https://github.com/salafudinn/Kelompok2-ShiftB-ResponsiPemweb2Laravel.git
cd Kelompok2-ShiftB-ResponsiPemweb2Laravel

# 2. Install dependensi PHP
composer install

# 3. Salin file konfigurasi environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di file .env
#    DB_DATABASE=labiot_manager
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Jalankan migrasi dan seeder (membuat tabel + data awal)
php artisan migrate:fresh --seed

# 7. Buat symlink untuk storage gambar
php artisan storage:link

# 8. Jalankan server lokal
php artisan serve
# Akses: http://127.0.0.1:8000
```

---

## Akun Pengujian

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Asisten Lab** | `admin@lab.com` | `LabIoT2026!` | CRUD Alat, Approve/Reject Pinjaman, Serah Terima, Proses Pengembalian + Hitung Denda, Kelola Laporan Kerusakan |
| **Mahasiswa** | `mhs1@lab.com` | `LabIoT2026!` | Lihat Katalog, Ajukan Pinjaman, Cek Riwayat & Denda, Lapor Kerusakan |
| **Mahasiswa** | `mhs2@lab.com` | `LabIoT2026!` | Sama seperti Mahasiswa 1 |

---

## Link Deployment

>  **[https://b2.athafa.cloud/](https://b2.athafa.cloud/)**

---

## API Documentation

Semua endpoint (kecuali `register` dan `login`) memerlukan header:
```
Authorization: Bearer <access_token>
Content-Type: application/json
Accept: application/json
```

### Autentikasi

| Method | Endpoint | Keterangan | Auth |
|---|---|---|---|
| `POST` | `/api/auth/register` | Daftar akun mahasiswa baru | No |
| `POST` | `/api/auth/login` | Login — mendapatkan Bearer Token | No |
| `POST` | `/api/auth/logout` | Logout — menghancurkan token aktif | Yes |

### Katalog Alat (IoT Kits)

| Method | Endpoint | Keterangan | Auth |
|---|---|---|---|
| `GET` | `/api/iot-kits` | Daftar alat dengan paginasi. Support `?search=` dan `?category=` | Yes |
| `GET` | `/api/iot-kits/{id}` | Detail satu alat | Yes |
| `POST` | `/api/iot-kits` | Tambah alat baru ke inventaris | Yes (Admin) |
| `PUT` | `/api/iot-kits/{id}` | Perbarui data alat | Yes (Admin) |
| `DELETE` | `/api/iot-kits/{id}` | Hapus alat dari inventaris | Yes (Admin) |

### Peminjaman (Borrowings)

| Method | Endpoint | Keterangan | Auth |
|---|---|---|---|
| `GET` | `/api/borrowings` | Riwayat pinjam. Mahasiswa: riwayat sendiri. Admin: semua data | Yes |
| `POST` | `/api/borrowings` | Buat pengajuan peminjaman baru (status: `PENDING`) | Yes (Mhs) |
| `PUT` | `/api/borrowings/{id}/approve` | Setujui pengajuan → stok berkurang otomatis (status: `APPROVED`) | Yes (Admin) |
| `PUT` | `/api/borrowings/{id}/reject` | Tolak pengajuan (status: `REJECTED`) | Yes (Admin) |
| `PUT` | `/api/borrowings/{id}/pickup` | Catat serah terima fisik ke mahasiswa (status: `ON_LOAN`) | Yes (Admin) |
| `PUT` | `/api/borrowings/{id}/return` | Proses pengembalian + hitung denda otomatis (status: `RETURNED`) | Yes (Admin) |

> **Formula Denda:** `Denda = max(0, selisih_hari × Rp5.000/hari)`

### Laporan Kerusakan (Damage Reports)

| Method | Endpoint | Keterangan | Auth |
|---|---|---|---|
| `GET` | `/api/damage-reports` | Daftar laporan kerusakan dengan paginasi | Yes |
| `POST` | `/api/damage-reports` | Buat laporan kerusakan baru | Yes |
| `PUT` | `/api/damage-reports/{id}/resolve` | Update status penanganan: `IN_REPAIR`, `RESOLVED`, atau `DISCARDED` | Yes (Admin) |

### HTTP Status Code yang Digunakan

| Code | Arti |
|---|---|
| `200` | OK — Request berhasil diproses |
| `201` | Created — Data berhasil dibuat |
| `401` | Unauthorized — Token tidak ada atau tidak valid |
| `403` | Forbidden — Role tidak memiliki hak akses ke resource ini |
| `422` | Unprocessable — Validasi gagal atau pelanggaran logika bisnis |
| `404` | Not Found — Data yang diminta tidak ditemukan |
