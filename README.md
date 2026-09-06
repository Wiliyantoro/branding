# KANG WILLY - Personal Branding Website

A modern, high-performance personal branding and portfolio website built with the TALL stack.

![Tech Stack](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v4-F59E0B?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)

---

## 🏗️ System Architecture

```mermaid
graph TD
    %% Entities
    Visitor([👤 Web Visitor])
    Admin([🔐 Administrator])
    
    %% Routing
    Router(Laravel Routing & Middleware)
    
    %% Controllers & Handlers
    PublicCtrl[Home & Blog Controllers]
    Filament[Filament v4 Admin Panel]
    
    %% Frontend
    Blade[Blade Views + Tailwind CSS v4]
    Livewire[Livewire + Alpine.js]
    
    %% Services
    Mail[Mailer Service]
    DB[(SQLite Database)]
    Storage[Local Public Storage]
    
    %% Connections
    Visitor -->|GET / POST| Router
    Admin -->|GET /admin| Router
    
    Router -->|Public Traffic| PublicCtrl
    Router -->|Admin Traffic| Filament
    
    PublicCtrl --> Blade
    Filament --> Livewire
    
    PublicCtrl -->|Rate-limited Contact Form| Mail
    
    Blade -.->|Reads| DB
    Livewire -.->|CRUD| DB
    
    Filament -->|Uploads| Storage
    Blade -.->|Displays| Storage
```

## 🗄️ Database Schema

```mermaid
erDiagram
    USERS {
        int id PK
        string name
        string email
        string password
        boolean is_admin "Access control for /admin"
    }
    
    BLOG_POSTS {
        int id PK
        string title
        string slug
        text content "XSS-Sanitized HTML"
        string featured_image
        boolean is_published
        datetime published_at
        int views_count "Session-deduplicated"
    }
    
    PORTFOLIOS {
        int id PK
        string title
        text description
        string image "Supports external URL & Uploads"
        string url
        string tech_stack
        boolean is_published
    }
    
    SERVICES {
        int id PK
        string title
        text description
        string icon "FontAwesome class"
        decimal price
        boolean is_published
    }
    
    TESTIMONIALS {
        int id PK
        string client_name
        string client_avatar
        text content
        int rating "1 to 5"
        boolean is_published
    }
    
    SETTINGS {
        int id PK
        string group "general, social, contact"
        string key UK
        text value
    }

    USERS ||--o{ BLOG_POSTS : manages
    USERS ||--o{ PORTFOLIOS : manages
    USERS ||--o{ SERVICES : manages
    USERS ||--o{ TESTIMONIALS : manages
    USERS ||--o{ SETTINGS : manages
```

---

## ✨ Key Features

- **Blazing Fast Frontend:** Server-side rendered Blade views styled with Vite + Tailwind CSS v4.
- **Dynamic CMS Panel:** Fully featured admin dashboard powered by Filament v4.
- **Security First:** 
  - Stored XSS protection via `Symfony/HtmlSanitizer` for rich text content.
  - Strict scope guards preventing draft leakage.
  - Rate-limited (`throttle:5,1`) contact form.
- **SEO & Accessibility Ready:** 
  - Dynamic `sitemap.xml` and `robots.txt`.
  - OpenGraph & Twitter meta tags auto-injected.
  - ARIA attributes and screen-reader compliant (a11y).
- **Global Dynamic Settings:** Site identity, emails, and social links are fully manageable from the admin panel (cached forever, auto-invalidated on update).

## 🚀 Installation & Setup

1. **Clone & Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database & Storage**
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

4. **Build Frontend Assets**
   ```bash
   npm run build
   ```

5. **Serve**
   ```bash
   php artisan serve
   ```

## 🧪 Testing

The application includes a comprehensive test suite (Unit & Feature tests) covering public pages, SEO routes, authorization, and contact form behavior.

```bash
php artisan test
```
