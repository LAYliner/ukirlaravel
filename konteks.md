# KONTEKS PROJECT: UKIR (SKRIPSI Studi Kasus: Sanggar Ukir Tana Paser)

## 1. STACK TEKNOLOGI
- **Local Server:** FlyEnv Version 4.13.6
- **Framework:** Laravel 12.53.0
- **Database:** MySQL 8.4.8
- **Backend Language:** PHP 8.5.3
- **Frontend:** Tailwind CSS
- **Server:** Apache

## 2. ARSITEKTUR & STRUKTUR
- **Pattern:** MVC
- **Struktur Folder:** repo_structure.yaml
- **Struktur Folder Kunci:**
  - `app/Models`: 
    - path: /app/Models
      type: directory
      contents:
      - path: /app/Models/User.php
        type: file
      - path: /app/Models/Comment.php
        type: file
      - path: /app/Models/Category.php
        type: file
      - path: /app/Models/Blog.php
        type: file
      - path: /app/Models/Project.php
        type: file
      - path: /app/Models/Media.php
        type: file

  - `app/Http/Controllers`: 
      - path: /app/Http/Controllers
        type: directory
        contents:
        - path: /app/Http/Controllers/Admin
          type: directory
          contents:
          - path: /app/Http/Controllers/Admin/ProjectController.php
            type: file
          - path: /app/Http/Controllers/Admin/AdminDashboardController.php
            type: file
          - path: /app/Http/Controllers/Admin/CommentController.php
            type: file
          - path: /app/Http/Controllers/Admin/CategoryController.php
            type: file
          - path: /app/Http/Controllers/Admin/UserController.php
            type: file
          - path: /app/Http/Controllers/Admin/BlogController.php
            type: file
        - path: /app/Http/Controllers/Auth
          type: directory
          contents:
          - path: /app/Http/Controllers/Auth/LoginController.php
            type: file
          - path: /app/Http/Controllers/Auth/RegisterController.php
            type: file
        - path: /app/Http/Controllers/Controller.php
          type: file
        - path: /app/Http/Controllers/Public
          type: directory
          contents:
          - path: /app/Http/Controllers/Public/ProjectController.php
            type: file
          - path: /app/Http/Controllers/Public/CommentController.php
            type: file
          - path: /app/Http/Controllers/Public/HomeController.php
            type: file
          - path: /app/Http/Controllers/Public/BlogController.php
            type: file

  - `storage` :
    - path: /storage
      type: directory
      contents:
      - path: /storage/framework
        type: directory
        contents:
        - path: /storage/framework/views
          type: directory
          contents:
        - path: /storage/framework/testing
          type: directory
          contents:
        - path: /storage/framework/sessions
          type: directory
          contents:
        - path: /storage/framework/cache
          type: directory
          contents:
          - path: /storage/framework/cache/data
            type: directory
            contents:
      - path: /storage/logs
        type: directory
        contents:
      - path: /storage/app
        type: directory
        contents:
        - path: /storage/app/private
          type: directory
          contents:
        - path: /storage/app/public
          type: directory
          contents:

  - `resources/views`:
    - path: /resources/views
      type: directory
      contents:
      - path: /resources/views/vendor
        type: directory
        contents:
        - path: /resources/views/vendor/pagination
          type: directory
          contents:
          - path: /resources/views/vendor/pagination/simple.blade.php
            type: file
      - path: /resources/views/auth
        type: directory
        contents:
        - path: /resources/views/auth/register.blade.php
          type: file
        - path: /resources/views/auth/login.blade.php
          type: file
      - path: /resources/views/layouts
        type: directory
        contents:
        - path: /resources/views/layouts/admin.blade.php
          type: file
        - path: /resources/views/layouts/public.blade.php
          type: file
        - path: /resources/views/layouts/auth.blade.php
          type: file
        - path: /resources/views/layouts/main.blade.php
          type: file
      - path: /resources/views/errors
        type: directory
        contents:
        - path: /resources/views/errors/419.blade.php
          type: file
      - path: /resources/views/admin
        type: directory
        contents:
        - path: /resources/views/admin/users
          type: directory
          contents:
          - path: /resources/views/admin/users/index.blade.php
            type: file
        - path: /resources/views/admin/categories
          type: directory
          contents:
          - path: /resources/views/admin/categories/create.blade.php
            type: file
          - path: /resources/views/admin/categories/edit.blade.php
            type: file
          - path: /resources/views/admin/categories/index.blade.php
            type: file
        - path: /resources/views/admin/blog
          type: directory
          contents:
          - path: /resources/views/admin/blog/create.blade.php
            type: file
          - path: /resources/views/admin/blog/edit.blade.php
            type: file
          - path: /resources/views/admin/blog/index.blade.php
            type: file
        - path: /resources/views/admin/comments
          type: directory
          contents:
          - path: /resources/views/admin/comments/index.blade.php
            type: file
        - path: /resources/views/admin/projects
          type: directory
          contents:
          - path: /resources/views/admin/projects/create.blade.php
            type: file
          - path: /resources/views/admin/projects/edit.blade.php
            type: file
          - path: /resources/views/admin/projects/index.blade.php
            type: file
        - path: /resources/views/admin/dashboard.blade.php
          type: file
      - path: /resources/views/welcome.blade.php
        type: file
      - path: /resources/views/public
        type: directory
        contents:
        - path: /resources/views/public/blog
          type: directory
          contents:
          - path: /resources/views/public/blog/show.blade.php
            type: file
          - path: /resources/views/public/blog/index.blade.php
            type: file
        - path: /resources/views/public/projects
          type: directory
          contents:
          - path: /resources/views/public/projects/show.blade.php
            type: file
          - path: /resources/views/public/projects/index.blade.php
            type: file
  - `resources/css`:
    - path: /resources/css
      type: directory
      contents:
      - path: /resources/css/ckeditor.css
        type: file
      - path: /resources/css/app.css
        type: file
    - path: /resources/js
      type: directory
      contents:
      - path: /resources/js/ckeditor.js
        type: file
      - path: /resources/js/bootstrap.js
        type: file
      - path: /resources/js/app.js
        type: file
- `app/Services`: Belum ada saat ini
- `database/migrations`: Digunakan untuk melacak skema database (dibuat menggunakan `laravel-migrations-generator` sebagai baseline)
- **Database Management**: Laravel Migrations & phpMyAdmin
- **Polymorphic Relations**: Tidak memiliki FK constraint di database level
- **Traditional FK**: `uploaded_by`, `user_id`, `category_id`, `parent_id` memiliki constraint
- **Relation View phpMyAdmin**: Hanya menampilkan traditional FK, bukan polymorphic

## 3. SKEMA DATABASE (UTAMA)
- **Table: users**
  - `id` (varchar(36), PK, UUID)
  - `name` (varchar(255), not null)
  - `email` (varchar(255), not null, unique, indexed)
  - `password` (varchar(255), not null)
  - `role` (enum: 'admin', 'author', 'user', default: 'user')
  - `phone` (varchar(20), nullable)
  - `is_active` (boolean, default: true)
  - `remember_token` (varchar(100), nullable)
  - `created_at` (datetime)
  - `updated_at` (datetime)
  - `deleted_at` (datetime, nullable)
- **Table: blogs**
  - `id` (varchar(36), PK, UUID)
  - `user_id` (varchar(36), FK -> users.id, indexed, not null)
  - `category_id` (varchar(36), FK -> categories.id, indexed, nullable)
  - `title` (varchar(255), not null)
  - `slug` (varchar(255), unique, indexed)
  - `content` (longtext, not null)
  - `thumbnail_path` (varchar(255), nullable)
  - `status` (enum: 'draft', 'published', default: 'draft')
  - `is_visible` (tinyint(1): `0`, `1`, default: `1`)
  - `published_at` (datetime, nullable)
  - `views` (int, default: 0)
  - `created_at` (datetime)
  - `updated_at` (datetime)
  - `deleted_at` (datetime, nullable)
- **Table: categories**
  - `id` (varchar(36), PK, UUID)
  - `name` (varchar(100), not null)
  - `slug` (varchar(150), unique, indexed)
  - `description` (text, nullable)
  - `created_at` (datetime)
- **Table: media**
  - `id` (varchar(36), PK, UUID)
  - `mediable_id` (varchar(36), indexed, not null)
  - `mediable_type` (varchar(255), indexed, not null)
  - `file_name` (varchar(255), not null)
  - `file_path` (varchar(255), not null)
  - `file_type` (varchar(50), not null)
  - `file_size` (int, unsigned, nullable)
  - `sort_order` (int, default: 0, indexed)
  - `uploaded_by` (varchar(36), FK -> users.id, nullable)
  - `created_at` (datetime)
- **Table: password_reset_tokens** (Standard Laravel)
  - `email` (varchar(255), PK)
  - `token` (varchar(255), not null)
  - `created_at` (datetime, nullable)
- **Table: projects**
  - `id` (varchar(36), PK, UUID)
  - `user_id` (varchar(36), FK -> users.id, indexed, not null)
  - `title` (varchar(255), not null)
  - `slug` (varchar(255), unique, indexed)
  - `description` (longtext, not null)
  - `thumbnail_path` (varchar(255), nullable)
  - `client_name` (varchar(255), nullable)
  - `project_date` (date, nullable) **DROPPED**
  - `status` (enum: 'draft', 'published', default: 'draft')
  - `is_visible` (tinyint(1): `0`, `1`, default: `1`)
  - `views` (int, default: 0)
  - `created_at` (datetime)
  - `updated_at` (datetime)
  - `deleted_at` (datetime, nullable)
- **Table: comments**
  - `id` (varchar(36), PK, UUID)
  - `user_id` (varchar(36), FK -> users.id, indexed, nullable) -- nullable untuk guest comment
  - `commentable_id` (varchar(36), indexed, not null) -- ID entitas (blog.id atau project.id)
  - `commentable_type` (varchar(255), indexed, not null) -- Class (App\Models\Blog atau App\Models\Project)
  - `parent_id` (varchar(36), FK -> comments.id, nullable, indexed) -- Untuk reply/nested comment
  - `content` (text, not null)
  - `created_at` (datetime)
  - `updated_at` (datetime)
  - `deleted_at` (datetime, nullable)

- **Relasi User:**
  - `User` hasMany `Blog` (via user_id)
  - `User` hasMany `Comment` (via user_id)
  - `User` hasMany `Project` (via user_id)
  - `User` hasMany `Media` (via uploaded_by)

- **Relasi Blog:**
  - `Blog` belongsTo `User` (via user_id)
  - `Blog` belongsTo `Category` (via category_id)
  - `Blog` hasMany `Comment` (Polymorphic)
  - `Blog` hasMany `Media` (Polymorphic)

- **Relasi Project:**
  - `Project` belongsTo `User` (via user_id)
  - `Project` hasMany `Comment` (Polymorphic)
  - `Project` hasMany `Media` (Polymorphic)

- **Relasi Comment:**
  - `Comment` belongsTo `User` (via user_id)
  - `Comment` morphTo `Commentable` (Blog/Project)

- **Relasi Media:**
  - `Media` morphTo `Mediable` (Blog/Project) → `$media->mediable()`
  - `Media` belongsTo `User` (via uploaded_by) → `$media->uploader()`

## 4. STATUS IMPLEMENTASI
- **Selesai:** 
- Auth (Register, Login, Logout)
- Admin Dashboard
- Blog CRUD (Admin) + Thumbnail Upload
- Blog Public View (Index, Show) — UI diperbarui ke Tailwind CSS (`layouts.main`)
- Category CRUD (Admin)
- Category Integration di Blog (Create/Edit)
- Search, Sort (A-Z/Z-A), Pagination (preserve params) di Categories (Index)
- Sidebar Navigation + Responsive Toggle
- Module Projects (CRUD Admin) — termasuk Search, Sort, Filter, Pagination
- Module Projects Public View (Index, Show) — UI Tailwind CSS
- Comments System (Blog & Project) — hanya authenticated user, nested replies
- `welcome.blade.php` di-refactor ke `layouts.main` (tidak ada lagi duplikasi nav/footer)
- Admin Comment Log & Moderation Module:
  - View daftar komentar dengan kolom: Tipe (Blog/Project), Judul Blog/Project, Pengguna (nama + email), Isi Komentar, Status (Active/Deleted), Timestamp (created_at, updated_at)
  - Pagination (15 item/halaman)
  - Sorting by created_at, updated_at, ID (asc/desc)
  - Search by content, user name, email
  - Filter by type (blog/project), specific blog_id, specific project_id, status (active/deleted/all)
  - Soft-delete comment dengan konfirmasi modal
  - Restore soft-deleted comment
  - Force delete (permanent) dengan konfirmasi modal
  - Route: `/admin/comments` (admin only)
  - Controller: `App\Http\Controllers\Admin\CommentController`
  - View: `resources/views/admin/comments/index.blade.php`
  - Sidebar link "Komentar" ditambahkan di `layouts/admin.blade.php` (hanya untuk admin)
- User Management:
  - View daftar user dengan kolom: Nama, Email, Role (Admin/Author/User), Status (Aktif/Tidak Aktif), Foto Profil, Timestamp (created_at, updated_at)
  - Pagination (15 user/halaman)
  - Sorting by nama, email, role, status, created_at, updated_at, ID (asc/desc)
  - Search by nama, email
  - Filter by role, status
  - Toggle status (aktif/tidak aktif) dengan konfirmasi modal
  - Soft-delete user dengan konfirmasi modal
  - Restore soft-deleted user
  - Force delete (permanent) user dengan konfirmasi modal
  - Upload foto profil (resize otomatis ke 150x150px)
  - Role management: ubah role user (Admin/Author/User)
  - Route: `/admin/users` (admin only)
  - Controller: `App\Http\Controllers\Admin\UserController`
  - View: `resources/views/admin/users/index.blade.php`
  - Sidebar link "User" ditambahkan di `layouts/admin.blade.php` (hanya untuk admin)
    - Module Blog:
    - status enum 'rejected' di tabel `blogs` dihapus. Hapus dan ubah logika terkait status `rejected` di controller, model, dan view terkait.
    - Filter by category_id di module blog dan status di sisi admin.
    - Sorting by judul, created_at, updated_at dan teks `Menampilkan` dipindah ke atas sejajar dengan sorting seperti yang ada di index.blade.php untuk halaman comments.
- Integrasi CKEditor 5 ke dalam form Create/Edit module Blog dan Project, termasuk penanganan layout responsif dan penampilan Rich Text di sisi publik.
- Fitur Highlight Comment:
  - Scroll otomatis dan highlight (efek visual transisi warna latar belakang) ke komentar tertentu saat halaman Blog/Project diakses dengan query parameter `highlightComment`.
  - Route redirect khusus `/content/{contentId}?highlightComment={commentId}` yang ditangani oleh `App\Http\Controllers\Public\ContentHighlightController` untuk mengarahkan pengguna secara dinamis ke halaman Blog atau Project yang sesuai berdasarkan relasi polymorphic `commentable` dari komentar tersebut, sekaligus mendeteksi jika komentar telah dihapus (soft-deleted).
  - Integrasi di frontend via DOMContentLoaded JavaScript di `resources/views/public/blog/show.blade.php`.
- **Laravel Migrations Generator Setup:**
  - Menghapus tabel `users` dan `password_reset_tokens` dari migrasi bawaan Laravel (`0001_01_01_000000_create_users_table.php`) untuk menghindari bentrok, menyisakan tabel `sessions`.
  - Membuat migrasi basis data dari skema MySQL yang ada menggunakan `kitloong/laravel-migrations-generator` untuk semua tabel (`blogs`, `categories`, `comments`, `media`, `password_reset_tokens`, `projects`, `users`).
  - Mendaftarkan migrasi hasil generate ke tabel `migrations` sebagai batch `0` (*baseline*).
  - Menerapkan migrasi tabel bawaan Laravel yang belum ada (`sessions`, `cache`, `jobs`) ke dalam database.
- **Blog Visibility (Sembunyikan/Tampilkan Blog):**
  - Menambahkan kolom `is_visible` (boolean, default true) ke tabel `blogs` melalui migrasi baru dan memperbarui model `Blog` (`$fillable` & `$casts`).
  - Menambahkan rute patch status (`admin.blog.update-status`) dan visibilitas (`admin.blog.toggle-visibility`).
  - Menerapkan fitur *toggle* visibilitas dan status dropdown bagi admin di daftar blog admin, serupa dengan manajemen proyek.
  - Memfilter data blog publik di `Public\BlogController` (daftar blog, pencarian, related posts) dan `HomeController` (latest blogs) agar hanya menampilkan blog yang memiliki status `is_visible = true`.
- **Dalam Pengerjaan:** 
  - (Kosong untuk saat ini)
- **Pending:** 
  - Media/Upload Service (Polymorphic)
  - Site Settings (Identitas Situs)


## 5. RIWAYAT MASALAH & KONFIGURASI
- **Riwayat Masalah & Solusi:**
  - Error Blog menggunakan skema database lama → penyesuaian di `Blog.php`, `BlogController.php` (Admin & Public), dan semua view admin/blog
  - Thumbnail blog tidak muncul → menjalankan `php artisan storage:link`
  - Duplikasi `<nav>` dan `<footer>` antara `welcome.blade.php` dan `layouts/main.blade.php` → `welcome.blade.php` di-refactor menjadi `@extends('layouts.main')`, konten dideduksi menjadi `@section('content')` saja
  - `layouts/public.blade.php` → **sudah tidak dipakai oleh file apapun** (orphan), kandidat untuk dihapus
  - Vite gagal memuat aset CKEditor → Mendaftarkan `ckeditor.css` dan `ckeditor.js` secara eksplisit ke dalam *array input* pada `vite.config.js`.
  - Editor CKEditor error inisialisasi / tertimpa dummy text bawaan Builder → Menghapus konfigurasi `initialData` dan `attachTo` pada `resources/js/ckeditor.js`, lalu menggunakan DOM element sebagai argumen pertama `ClassicEditor.create()`.
  - Layout CKEditor tumpah/melebar (*overflow*) di form admin → Menghapus *fixed width* (`min-width`/`max-width`: `795px`) di `resources/css/ckeditor.css` dan menggantinya dengan `width: 100%` agar mengikuti lebar kontainer Tailwind.
  - Hasil teks tampil sebagai kode HTML mentah di halaman *show* (publik) → Mengganti Blade syntax `{{ }}` atau `nl2br(e(...))` dengan sintaks render unescaped `{!! !!}` pada file view publik Blog dan Projects.
  - Form Create/Edit tidak bisa disubmit (tombol simpan tidak merespon) → Terjadi karena browser mencoba melakukan validasi HTML5 pada elemen `<textarea required>` yang sudah di-*hidden* oleh CKEditor. Solusinya adalah menghapus atribut `required` pada elemen textarea tersebut dan mengandalkan validasi *backend* Laravel.
  - Elemen Heading dan Lists (HTML) hasil CKEditor tidak memilik gaya visual (tampil rata) di halaman *Show* → Disebabkan oleh *CSS Reset* dari Tailwind CSS. Solusinya adalah menginstal `@tailwindcss/typography` via NPM dan mendaftarkannya pada `app.css` (`@plugin "@tailwindcss/typography";`) agar class `prose` dapat aktif dan me-render ulang gaya tipografi elemen dasar HTML.
  - Media Embed (YouTube, dll) tidak muncul di halaman *Show* → Secara *default* CKEditor 5 menyimpan Media dalam tag abstrak `<oembed>`. Solusinya adalah menambahkan `mediaEmbed: { previewsInData: true }` di konfigurasi JS CKEditor agar media disimpan secara langsung dalam bentuk kode *iframe* HTML yang utuh.
- **Konfigurasi Kritis:**
  - `DB_CONNECTION=mysql`
  - `APP_ENV=local`
  - [Vars lainnya]

## 6. STANDAR KODE & BATASAN
- **Style:** PSR-12
- **Validasi:** Form Request Classes
- **Keamanan:** Sanitasi input wajib, prepared statements (Eloquent ORM)
- **Larangan:** 
 - Tidak ada hard-coded credentials, tidak ada `dd()` di production logic.
 - Tidak ada `emoji` di UI
- **Foreign Keys**: Handle cascade di Model Observer (karena Soft Delete)
- **Collation**: utf8mb4_0900_ai_ci (konsisten semua tabel)
- **Database**: Manual Schema Management & Database-First Approach
- **UI dan UX**: Wajib menerapkan Responsive Design. Untuk ukuran layar smartphone, tablet, dan desktop

## 7. INSTRUKSI SESI INI
- *index blade.php di bagian admin projects belum memiliki alert success dan error seperti blog.*
- *Sesuaikan sorting index.blade.php di module projects (judul dan tanggal) seperti di module blog*
- *hapus filter `dari tanggal` dan `sampai tanggal` di module projects*
- *Cek setiap index disetiap module, alert diletakan di bawah judul halaman*
- *Penempatan 'Menampilkan X - Y dari Z data' dipindah ke bawah sejajar dengan pagination*. 
