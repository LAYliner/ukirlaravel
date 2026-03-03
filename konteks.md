# KONTEKS PROJECT: UKIR (SKRIPSI)

## 1. STACK TEKNOLOGI
- **Framework:** Laravel 12.53.0
- **Database:** MySQL 8.4.8
- **Backend Language:** PHP 8.5.3
- **Frontend:** Belum ditetapkan untuk saat ini, fokus ke Backend dulu. 
- **Server:** Apache

## 2. ARSITEKTUR & STRUKTUR
- **Pattern:** MVC
- **Struktur Folder Kunci:**
  - `app/Models`: Default
  - `app/Http/Controllers`: Default
  - `app/Services`: Default
  - `database/migrations`: -
- **Keamanan:** Belum ditentukan, tapi saya ingin membuat situs web yang memiliki admin, author (pengisi 
konten), dan user (komentar di konten)

## 3. SKEMA DATABASE (UTAMA)
- **Table: users**
  - Kolom: id, name, email, password, role, created_at, updated_at
- **Table: [Nama Table Lain]**
  - Kolom: [Daftar kolom penting & relasi]
- **Relasi:** [Misal: User hasMany Post]

## 4. STATUS IMPLEMENTASI
- **Selesai:** [Fitur yang sudah berfungsi, misal: Registrasi User]
- **Dalam Pengerjaan:** [Fitur saat ini, misal: Login Flow]
- **Pending:** [Fitur rencana]

## 5. RIWAYAT MASALAH & KONFIGURASI
- **Error Terakhir:** [Misal: Laravel MySQL Koneksi Error - SQLSTATE[HY000] [1045]]
- **Solusi Diterapkan:** [Misal: Update .env DB_PASSWORD, clear config cache]
- **Konfigurasi Kritis:**
  - `DB_CONNECTION=mysql`
  - `APP_ENV=local`
  - [Vars lainnya]

## 6. STANDAR KODE & BATASAN
- **Style:** PSR-12
- **Validasi:** Form Request Classes
- **Keamanan:** Sanitasi input wajib, prepared statements (Eloquent ORM)
- **Larangan:** Tidak ada hard-coded credentials, tidak ada `dd()` di production logic.

## 7. INSTRUKSI SESI INI
- Fokus pada: [Misal: Perbaikan logic registrasi, optimasi query]
- Output yang diharapkan: [Misal: Kode refactor, penjelasan arsitektur]
