# Product Requirements Document (PRD)

> **KANG WILLY - Personal Branding Site**
> Aplikasi Laravel + Filament admin untuk developer web / Vibe Coding.

## 1. Ikhtisar Produk
- **Nama**: KANG WILLY Personal Branding
- **Tujuan**: Portofolio digital + blog + CMS statis untuk personal branding
- **Stack**: Laravel 13, Filament v4, Tailwind 4, SQLite, Vite
- **User**: Developer (KANG WILLY), admin panel akses, publik baca blog/portofolio
- **Live**: https://info.kgwilly.my.id

## 2. Fitur Utama

### 2.1 Public Area (SSR Blade)
- Landing page (`/`): hero, about, portfolio, services, contact form
- Blog (`/blog`): list + detail post, sitemap.xml
- Contact form: POST ke `/contact`, validasi rate-limit, mail log
- SEO: OG meta, robots.txt deny `/admin`, canonical URL

### 2.2 Admin Panel (`/admin`)
- Auth login Filament (email/password: ketutwiliyantoro@gmail.com / password)
- Resources:
  - BlogPosts: title, slug, is_published, published_at, content (RichText), featured_image (Upload)
  - Portfolios: title, category, description, url, screenshot (Upload)
  - Services: title, description, icon
  - Settings: key/value dinamis (site_name, tagline, social links, dll)
  - Users: (Filament bawaan)
- Widgets Dashboard:
  - StatsOverview (total posts, views, comments, projects)
  - BlogCategoryChart (distribusi kategori blog)
  - PendingDraftsList (draft belum publish)
  - RecentActivityLog (aksi terbaru di panel)
- Fitur tambahahan (planned):
  - File manager integrasi
  - Backup/restore database button
  - Theme customizer (warna primary via panel)
  - Analytics dashboard (page views dari DB / Google Analytics)

### 2.3 Security
- `.env` diproduction mode (APP_DEBUG=false)
- SecurityHeaders middleware (HSTS, X-Frame-Options, X-Content-Type-Options)
- FileUpload dengan acceptedFileTypes `image/\*` only
- `is_admin` kolom + `FilamentUser` auth gate
- XSS proteksi via HtmlSanitizer di accessor BlogPost.safe_content

## 3. Skema Database

### tables utama (SQLite)
```mermaid
erDiagram
    USERS { int id; string name; string email; string password; boolean is_admin }
    BLOG_POSTS { int id; string title; string slug; boolean is_published; datetime published_at; string featured_image; longtext content }
    PORTFOLIOS { int id; string title; string category; text description; string url; string screenshot }
    SERVICES { int id; string title; text description; string icon }
    SETTINGS { string key; string value }
    SESSIONS { string id; int user_id; ... }
    CACHE { string key; ... }

    USERS ||--o{ BLOG_POSTS : "authors via future pivot"
    BLOG_POSTS ||--o{ CATEGORIES : category
    USERS ||--o{ SESSIONS : session
```

## 4. Tech Debt & Known Issues
- draft post bisa diakses via URL langsung → FIX: scope `published()` di BlogController
- null published_at crash → FIX: `optional($post->published_at)->format()`
- stored XSS `{!! $post->content !!}` → FIX: accessor `safe_content` pakai HtmlSanitizer
- Filament panel terbuka semua user → FIX: `is_admin` + `canAccessPanel()`
- CDN tailwind di view → FIX: pakai `@vite`, CDN hanya dev
- ChartWidget `$heading` static di Filament v4 → FIX: jadikan non-static

## 5. User Stories
| No | Sebagai | Saya ingin | Agar |
|----|---------|------------|------|
| US-01 | Admin | login ke `/admin/login` | kelola konten |
| US-02 | Admin | buat/edit blog post | publik bisa baca |
| US-03 | Publik | lihat list blog | temukan artikel |
| US-04 | Publik | submit contact form | hubungi developer |
| US-05 | Admin | upload gambar portfolio | tampilkan screenshot |
| US-06 | Admin | ubah setting situs | tanpa edit kode |
| US-07 | Search Engine | baca sitemap.xml | indeks halaman |

## 6. Metrics & Success Criteria
- Halaman utama: load < 1.5s, Lighthouse ≥90
- Blog post: SEO title/og:image lengkap
- Contact form: submit sukses → 302 redirect + notif toast
- Admin: 0 security error di audit, semua resource CRUD jalan
- Test suite: ≥19 passed (PublicPages, ContactForm, AdminPanelAccess, Setting, SeoRoutes)

## 7. Timeline / Milestones
| Milestone | Deliverables |
|----------|-------------|
| M1 | Dashboard admin lengkap (resources + widgets) |
| M2 | Analytics + backup feature |
| M3 | Multi-language support |
| M4 | File manager integrasi |

## 8. Gambaran Teknis Deployment
- Server: Armbian, Nginx, PHP 8.4-fpm
- Deploy path: /mnt/dacstorage/branding
- Cloudflare tunnel: info.kgwilly.my.id → localhost:1243
- DB: SQLite /mnt/dacstorage/branding/database/database.sqlite
- Session: database driver, domain .kgwilly.my.id

---
Dokumen akan diupdate seiring feature tambahannya.
