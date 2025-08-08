# 🌐 SatuAkses

**SatuAkses** adalah aplikasi web inklusif berbasis Laravel yang secara khusus dikembangkan untuk **mendukung penyandang disabilitas dalam mengakses peluang kerja dan pelatihan keterampilan**.

Platform ini bertujuan untuk:
- Memberikan **akses setara terhadap lowongan kerja** bagi difabel.
- Menyediakan **kursus keterampilan** sesuai kategori dan tingkat kemampuan pengguna.
- Menjadi jembatan antara **perusahaan inklusif** dan **tenaga kerja difabel** yang potensial.
- Mendorong ekosistem kerja yang **berkeadilan, adaptif, dan memberdayakan**.

## 🎯 Fitur Utama

### 👨‍💼 Untuk Admin
- Manajemen Lowongan (CRUD)
- Manajemen Kursus dan Pelatihan
- Pengelolaan Data Perusahaan & User
- Notifikasi & Pengaturan Sistem
- Pengelolaan Jenis Disabilitas, Kategori & Tag

### 🏢 Untuk Perusahaan (Employer)
- Posting lowongan kerja inklusif
- Melihat pendaftar yang sesuai
- Dashboard khusus employer

### 🙋‍♂️ Untuk Pengguna Difabel
- Melamar pekerjaan yang tersedia
- Mengikuti kursus berdasarkan minat dan kemampuan
- Akses informasi dengan tampilan ramah difabel

## 🛠️ Teknologi yang Digunakan
- **Laravel 10** – Backend Framework
- **AdminLTE 3** – Admin Dashboard UI
- **Bootstrap 5** – Frontend Styling
- **SQLite / MySQL** – Database
- **Role-based Access** – Autentikasi dan otorisasi pengguna
- **Git & GitHub** – Version Control

## 🌱 Visi Sosial
Kami percaya bahwa setiap individu berhak atas kesempatan yang sama. Dengan **SatuAkses**, kami berusaha menghapus hambatan digital yang menghalangi penyandang disabilitas dalam mengakses pekerjaan dan pendidikan vokasi.

---

## 📦 Instalasi Cepat (Dev)
```bash
git clone https://github.com/ammarha123/satuakses.git
cd satuakses
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
