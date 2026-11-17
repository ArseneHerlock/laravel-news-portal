<div align="center">

# 📰 Zaira — News & Magazine Web System

**A modern, multi-role News & Magazine publishing platform built with PHP / Laravel, MySQL, and Bootstrap 5 (with Dark Mode & RTL support).**

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-Backend-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

</div>

---

## 📖 Overview

**Zaira** is a university team project implementing a full-featured online news & magazine management system. The platform separates **Public Readers**, **Authors**, and **Administrators** into dedicated portals, allowing seamless article publishing, editorial moderation, category/tag discovery, and reader engagement.

---

## ✨ Key Features

- **Public News Portal (`HTML/`):**
  - Editorial homepage with Featured, Latest, Weekly, Trending, and Popular sections.
  - Article archive filtering by category, tag, and keyword search (`blog/blogs.html`).
  - Full article reader view with author bio and comment section (`blog/blog-details.html`).
  - Public author profile & article feed (`blog/author.html`).
  - Dark / Light mode switch & RTL/LTR layout support.
- **User Portal (`HTML/user/`):**
  - User registration, login, and personal account dashboard.
- **Author Studio (`HTML/author/`):**
  - Dedicated author dashboard, post management (`Published`, `Pending`, `Draft`), and full article creation form (Title, Slug, Excerpt, Rich Content, SEO metadata, Categories, Tags, Featured Image).
- **Admin Console (`HTML/admin/`):**
  - System-wide dashboard and article moderation panel.

---

## 📁 Project Structure

```text
PHP-Project/
├── Database/
│   └── zaira_schema.sql        # MySQL database schema
├── Diagram/                    # System architecture & ERD diagrams
├── Cloude/                     # Project planning & documentation notes
├── HTML/                       # 17 static HTML/CSS/Bootstrap 5 templates
│   ├── index.html              # Public homepage
│   ├── blog/                   # blogs.html, blog-details.html, author.html
│   ├── pages/                  # about.html, contact.html, privacy-policy.html
│   ├── user/                   # login.html, register.html, dashboard.html
│   ├── author/                 # login.html, dashboard.html, posts.html, create-post.html
│   ├── admin/                  # login.html, dashboard.html, posts.html
│   └── assets/                 # Zaira stylesheets, webfonts, and zaira-lite.js
└── Src/
    └── NewsWebsite/            # Laravel backend application
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.2+, Laravel Framework |
| **Database** | MySQL (`Database/zaira_schema.sql`) |
| **Frontend** | HTML5, CSS3, Bootstrap 5, Font Awesome, Minimal Vanilla JS |
| **Version Control** | Git & GitHub |

---

## 🚀 Getting Started (Local Setup)

### 1. Clone the repository
```bash
git clone https://github.com/<your-username>/zaira-news-laravel.git
cd zaira-news-laravel
```

### 2. Setup the Database
1. Start **MySQL** (via XAMPP, Laragon, or Docker).
2. Create a database (e.g., `zaira_news`).
3. Import `Database/zaira_schema.sql` into your database.

### 3. Setup the Laravel Backend
```bash
cd Src/NewsWebsite
composer install
cp .env.example .env
php artisan key:generate
```
Update your database credentials in `Src/NewsWebsite/.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zaira_news
DB_USERNAME=root
DB_PASSWORD=
```
Then run the local server:
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` in your browser.

### 4. Preview Static UI Templates
Open `HTML/index.html` directly in any modern browser (or via VS Code Live Server) to preview all 17 frontend and portal templates.

---

## 👥 Team Division (4-Member Backend Workflow)

| Member | Module | Scope |
|---|---|---|
| **Member 1** | Auth & User Portal | Login, Register, Role Middleware (`admin`, `author`, `user`), User Dashboard |
| **Member 2** | Public News & Search | Homepage blocks, Blog listing, Search, Category/Tag filter, Article detail |
| **Member 3** | Author Studio & Comments | Author Dashboard, CRUD Articles, Image Upload, Article Comments |
| **Member 4** | Admin & Site Pages | Admin Dashboard, Post Moderation, Categories/Tags, Contact & Newsletter |

---

## 📝 Commit Convention

We follow [Conventional Commits](https://www.conventionalcommits.org/):
- `feat(...)`: New feature
- `fix(...)`: Bug fix
- `docs(...)`: Documentation & diagrams
- `style(...)`: UI/CSS changes
- `chore(...)`: Config, dependencies, or maintenance
