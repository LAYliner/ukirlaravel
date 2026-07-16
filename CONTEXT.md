# KONTEKS PROJECT: UKIR (SKRIPSI Studi Kasus: Sanggar Ukir Tana Paser)

## 1. STACK TEKNOLOGI
- **Framework:** Laravel 12 | **PHP:** ^8.2 | **Database:** MySQL | **Frontend:** Tailwind CSS v4 + Vite 7 + CKEditor 5
- **Pattern:** MVC | **Collation:** utf8mb4_0900_ai_ci | **PK:** UUID (varchar(36))

## 2. DATABASE SCHEMA

### users
`id` UUID PK, `name`, `email` unique, `email_verified_at`, `password` (hashed), `role` enum(admin/author/user), `phone`, `is_active` bool, `profile_picture`, `bio`, `remember_token`, `timestamps`, `soft_deletes`

### categories
`id` UUID PK, `name`, `slug` unique, `description`, `created_at` only (no updated_at)

### blogs
`id` UUID PK, `user_id` FK→users CASCADE, `category_id` FK→categories SET NULL, `title`, `slug` unique, `content` longText, `thumbnail_path`, `status` enum(draft/published), `is_visible` bool, `published_at`, `views` int, `timestamps`, `soft_deletes`

### projects
`id` UUID PK, `user_id` FK→users CASCADE, `title`, `slug` unique, `description` longText, `client_name`, `thumbnail_path`, `status` enum(draft/published), `is_visible` bool, `views` int, `timestamps`, `soft_deletes`

### comments
`id` UUID PK, `user_id` FK→users SET NULL (nullable), `commentable_id`, `commentable_type` (polymorphic: Blog/Project), `parent_id` FK→comments CASCADE (nullable, nested replies), `content` text, `timestamps`, `soft_deletes`

### tags
`id` UUID PK, `name`, `slug` unique, `timestamps`, `soft_deletes`

### project_tag
`id` UUID PK, `project_id` FK→projects CASCADE, `tag_id` FK→tags CASCADE, `timestamps`, unique(project_id, tag_id)

### password_reset_tokens
`email` PK, `token` (6 chars), `expires_at`, `verified` bool, `attempts` int, `created_at`

### email_verifications
`id` AI PK, `email` indexed, `otp_code`, `attempts`, `is_locked` bool, `locked_until`, `expires_at`, `timestamps`

### Dropped tables
media, cache, cache_locks, sessions, jobs, job_batches, failed_jobs

## 3. RELASI MODEL
| Model | Relations |
|-------|-----------|
| **User** | hasMany Blog, Comment, Project. Helper: `isAdmin()`, `isAuthor()`, `isUser()`, `isActive()`. Accessor: `profile_picture_url` |
| **Blog** | belongsTo User, Category. morphMany Comment. Scopes: search, filterByCategory, published |
| **Project** | belongsTo User. morphMany Comment. belongsToMany Tag (pivot: project_tag). Scopes: search, filterByStatus, filterByDateRange, filterByAuthor, filterByTags, published, active, forAuthenticatedUser |
| **Category** | hasMany Blog |
| **Comment** | belongsTo User. morphTo commentable (Blog/Project). belongsTo parent (self). hasMany replies (self) |
| **Tag** | belongsToMany Project. Auto-generate slug. Scopes: active |
| **ProjectTag** | extends Pivot, UUID PK |
| **PasswordResetToken** | email PK, timestamps false |
| **EmailVerification** | OTP-based email verification |

## 4. CONTROLLERS

### Admin (`app/Http/Controllers/Admin/`)
| Controller | Key Methods |
|-----------|-------------|
| **AdminDashboardController** | index (stats), uploadImage (CKEditor) |
| **BlogController** | index, create, store, edit, update, destroy, toggleVisibility, updateStatus |
| **ProjectController** | index, create, store, show, edit, update, destroy, toggleVisibility, updateStatus |
| **CategoryController** | index, create, store, edit, update, destroy |
| **CommentController** | index, destroy, restore, forceDelete |
| **TagController** | index, create, store, edit, update, destroy |
| **UserController** | index, toggleStatus, updateRole, destroy, restore, forceDelete |

### Auth (`app/Http/Controllers/Auth/`)
| Controller | Key Methods |
|-----------|-------------|
| **LoginController** | show, login, logout. Role-based redirect |
| **RegisterController** | show, register (with OTP) |
| **ForgotPasswordController** | showForm, sendToken, resendToken |
| **ResetPasswordController** | showVerifyForm, verify, showResetForm, reset |
| **VerificationController** | showForm, verify, resend, success. Brute force protection (5 attempts → 24hr lock) |

### Public (`app/Http/Controllers/Public/`)
| Controller | Key Methods |
|-----------|-------------|
| **HomeController** | index (latest 3 projects + 3 blogs) |
| **BlogController** | index (search/category, 9/page), show (comments, views, related) |
| **ProjectController** | index (search/tag, 9/page), show (comments, views, related by tags) |
| **CommentController** | store (polymorphic, auth required), destroy (owner/admin/author) |
| **ContentHighlightController** | redirect (highlight comment via query param) |

### Other
| Controller | Methods |
|-----------|---------|
| **ProfileController** | show, update, changePassword, comments |

## 5. FORM REQUESTS (`app/Http/Requests/`)
- `StoreProjectRequest`, `UpdateProjectRequest` — title, slug, description, client_name, thumbnail, status, tags
- `StoreTagRequest`, `UpdateTagRequest` — name, slug (unique excluding soft-deleted)
- `UpdateProfileRequest` — name, phone, bio, profile_picture
- `ChangePasswordRequest` — current_password, new_password (confirmed, min:8)

## 6. MIDDLEWARE (`app/Http/Middleware/`)
- `CheckRole` (alias: `role`) — variadic roles, checks user role, abort 401/403
- `PreventAuthPageCache` (alias: `no.auth.cache`) — no-cache headers on auth pages

## 7. MAIL (`app/Mail/`)
- `OtpMail` — OTP verifikasi registrasi (sync)
- `ResetPasswordMail` — Token reset password (queued)

## 8. RATE LIMITERS (AppServiceProvider)
- `change-password`: 5/min/IP
- `password_request`: 3/hr/email+IP
- `token_verify`: 5/15min/email+IP

## 9. VIEWS (`resources/views/`)
```
admin/          dashboard, blog/{index,create,edit}, categories/{index,create,edit}, comments/index, projects/{index,create,edit}, tags/{index,create,edit}, users/index
auth/           login, register, forgot-password, reset-password, verify-otp, verify-token, verification-success, emails/{otp,reset-password}
layouts/        admin, auth, main
profile/        show, comments
public/         about/index, blog/{index,show}, contact/index, projects/{index,show}
errors/         419
```

## 10. FRONTEND
- **CSS:** `app.css` (Tailwind v4 + @tailwindcss/typography, custom theme: primary=#885007, secondary=#e1c49d, accent=#dda45a), `ckeditor.css`
- **JS:** `app.js`, `bootstrap.js` (Axios), `ckeditor.js` (ClassicEditor, SimpleUploadAdapter→/admin/ckeditor/upload)
- **Vite entry:** app.css, app.js, ckeditor.css, ckeditor.js

## 11. ROUTES SUMMARY
| Group | Routes |
|-------|--------|
| **Public** | `/`, `/blog`, `/blog/{slug}`, `/projects`, `/projects/{slug}`, `/content/{id}` (highlight), `/contact`, `/about` |
| **Guest+no.cache** | `/login`, `/register`, `/forgot-password`, `/password/verify`, `/password/reset`, `/verify-otp`, `/resend-otp`, `/verification-success` |
| **Auth** | `/logout`, `/dashboard` (→admin), `/comments` (store/destroy), `/profile` (show/update/change-password/comments) |
| **Admin (admin,author)** | `/admin/` (dashboard), `/admin/blog/*`, `/admin/categories/*`, `/admin/projects/*`, `/admin/tags/*`, `/admin/comments/*`, `/admin/ckeditor/upload` |
| **Admin (admin only)** | `/admin/users/*` |

## 12. ROLE ACCESS
| Feature | Admin | Author | User | Guest |
|---------|-------|--------|------|-------|
| Public pages | ✓ | ✓ | ✓ | ✓ |
| Comment | ✓ | ✓ | ✓ | ✗ |
| Profile | ✓ | ✓ | ✓ | ✗ |
| Admin Dashboard | ✓ | ✓ | ✗ | ✗ |
| CRUD Blog/Project | All | Own | ✗ | ✗ |
| CRUD Category/Tag | ✓ | ✓ | ✗ | ✗ |
| Manage Comments | ✓ | ✓ | ✗ | ✗ |
| Manage Users | ✓ | ✗ | ✗ | ✗ |

## 13. STANDAR & BATASAN
- **Style:** PSR-12 | **Validasi:** Form Request Classes | **Security:** Eloquent ORM (prepared statements)
- **Larangan:** No hard-coded credentials, no `dd()` in production, no emoji in UI
- **FK cascade:** handled in Model (Soft Delete aware)
- **UI:** Responsive design wajib (mobile, tablet, desktop)
- **Database:** Manual schema management, database-first approach

## 14. KONFIGURASI KRITIS
- `DB_CONNECTION=mysql`, `APP_ENV=local`
- `APP_URL` must match `storage:link` for thumbnails
- Vite entry includes ckeditor.css and ckeditor.js explicitly

## 15. RIWAYAT MASALAH & SOLUSI
- Thumbnail tidak muncul → `php artisan storage:link`
- Vite gagal load CKEditor → daftarkan eksplisit di vite.config.js
- CKEditor initialData error → gunakan DOM element, hapus `initialData`
- CSS Reset Tailwind hilangkan style heading/lists → install `@tailwindcss/typography`, gunakan class `prose`
- Media embed tidak muncul → `mediaEmbed: { previewsInData: true }` di CKEditor config
- Form tidak submit → hapus `required` pada textarea (CKEditor hides it), andalkan validasi backend
- Collation mismatch di project_tag → set `$table->collation = 'utf8mb4_0900_ai_ci'`
