# KONTEKS PROJECT: UKIR (SKRIPSI)

## 1. STACK TEKNOLOGI
- **Framework:** Laravel 12.53.0
- **Database:** MySQL 8.4.8
- **Backend Language:** PHP 8.5.3
- **Frontend:** HTML+CSS
- **Server:** Apache

## 2. ARSITEKTUR & STRUKTUR
- **Pattern:** MVC
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
          - path: /app/Http/Controllers/Admin/AdminDashboardController.php
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
  - `app/Services`: Belum ada saat ini
  - `database/migrations`: Tidak digunakan untuk schema existing untuk `saat ini`
- **Database Management**: phpMyAdmin (Manual)
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
  - `status` (enum: 'draft', 'published', 'rejected', default: 'draft')
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
  - `client_name` (varchar(255), nullable)
  - `project_date` (date, nullable)
  - `status` (enum: 'draft', 'published', default: 'draft')
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
- **Selesai:** Penyesuaian kode dengan skema database yang baru sudah selesai, registrasi, login, admin membuat blog dalam status `'draft', ''rejected', dan 'published'
- **Dalam Pengerjaan:** -
- **Pending:** Belum ada.

## 5. RIWAYAT MASALAH & KONFIGURASI
- **Error Terakhir:** 
  - Error terkait Blog yang masih menggunakan skema database lama
  - Thumbnail blog tidak muncul
- **Solusi Diterapkan:** -
  - Melakkukan penyesuaian di file kode yang berkaitan dengan Blog seperti `app/Models/Blog.php, app/Http/Controllers/Public/BlogController.php, app/Http/Controllers/Admin/BlogController.php, resources/views/admin/blog/create.blade.php, resources/views/admin/blog/edit.blade.php, resources/views/admin/blog/index.blade.php, 
  - Menjalankan perintah `php artisan storage:link` untuk membuat symbolic link
- **Konfigurasi Kritis:**
  - `DB_CONNECTION=mysql`
  - `APP_ENV=local`
  - [Vars lainnya]

## 6. STANDAR KODE & BATASAN
- **Style:** PSR-12
- **Validasi:** Form Request Classes
- **Keamanan:** Sanitasi input wajib, prepared statements (Eloquent ORM)
- **Larangan:** Tidak ada hard-coded credentials, tidak ada `dd()` di production logic.
- **Foreign Keys**: Handle cascade di Model Observer (karena Soft Delete)
- **Collation**: utf8mb4_0900_ai_ci (konsisten semua tabel)

## 7. INSTRUKSI SESI INI
- Fokus pada: Membuat tombol 'Kategori' dan Halaman untuk CRUD Kategori di halaman dashboard admin. Pastikan menggunakan tabel `categories`
- Output yang diharapkan: -
