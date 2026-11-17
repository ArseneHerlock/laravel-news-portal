<div align="center">

# 📰 Zaira — News & Magazine Web System

**A modern, multi-role News & Magazine publishing platform built with PHP / Laravel, TiDB Cloud (MySQL-compatible), and Bootstrap 5.**

![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TiDB Cloud](https://img.shields.io/badge/TiDB%20Cloud-NewsDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-10B981?style=for-the-badge)

</div>

---

## 📖 Overview

**Zaira** is a university team project implementing a full-featured online news & magazine management system. The platform separates **Public Readers**, **Authors**, and **Administrators** into dedicated portals, backed by a shared **TiDB Cloud (`NewsDB`)** database so all team members work against live synchronized data.

---

## ✨ Key Features

- **Public News Portal (`HTML/`):**
  - Editorial homepage with Featured, Latest, Weekly, Trending, and Popular sections.
  - Article archive filtering by category, tag, and keyword search (`blog/blogs.html`).
  - Full article reader view with author bio and comments (`blog/blog-details.html`).
  - Public author profile & article feed (`blog/author.html`).
  - Dark / Light mode toggle & RTL/LTR layout support.
- **User Portal (`HTML/user/`):**
  - User registration, login, and personal account dashboard.
- **Author Studio (`HTML/author/`):**
  - Dedicated author dashboard, post management (`Published`, `Pending`, `Draft`), and article creation form (Title, Slug, Excerpt, Content, SEO metadata, Categories, Tags, Featured Image).
- **Admin Console (`HTML/admin/`):**
  - System-wide dashboard and article moderation panel.

---

## 📁 Project Structure

```text
PHP-Project/
├── Cloude/
│   └── TiDB CLoud/
│       ├── isrgrootx1.pem      # SSL/TLS CA certificate for TiDB Cloud
│       └── password.txt        # Database access credentials (team use)
├── Database/
│   └── zaira_schema.sql        # MySQL / TiDB database schema
├── Diagram/                    # System architecture & ERD diagrams
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

| Layer               | Technology                                                 |
| ------------------- | ---------------------------------------------------------- |
| **Backend**         | PHP 8.3+, Laravel 13 Framework                             |
| **Database**        | TiDB Cloud (`NewsDB`, MySQL-compatible over TLS) or SQLite |
| **Frontend**        | Blade Views, HTML5, CSS3, Bootstrap 5.3, Vanilla JS        |
| **Version Control** | Git & GitHub (Conventional Commits)                        |

---

## 🚀 Getting Started (Local Setup)

### 1. Clone the repository

```bash
git clone https://github.com/ArseneHerlock/laravel-news-portal.git
cd laravel-news-portal
```

### 2. Install Laravel dependencies & Seed Demo Data

```bash
cd Src/NewsWebsite
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

### 3. Configure TiDB Cloud (`NewsDB`) Connection (Optional / Team Shared DB)

By default, `.env.example` works out-of-the-box with local SQLite (`php artisan migrate --seed`). To connect to the shared **TiDB Cloud** database over TLS, update `Src/NewsWebsite/.env`:

```env
DB_CONNECTION=mysql
DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=NewsDB
DB_USERNAME=3AnYdBThXPvmUxi.root
DB_PASSWORD=<ask-team-lead-for-tidb-password>
MYSQL_ATTR_SSL_CA="../../Cloude/TiDB CLoud/isrgrootx1.pem"
```

> **Security Note:** Never commit plaintext database passwords or `.env` files to Git.

### 4. Run the Laravel Server & Automated Tests

```bash
php artisan serve
# Run the automated feature & unit test suite:
php artisan test
```

Open `http://127.0.0.1:8000` in your browser.

### 🔑 Demo Accounts (Seeded by `php artisan migrate --seed`)

| Role | Portal URL | Email | Password |
| :--- | :--- | :--- | :--- |
| **Admin** | `http://127.0.0.1:8000/admin/login` | `admin@gmail.com` | `password` |
| **Author** | `http://127.0.0.1:8000/author/login` | `author@gmail.com` | `password` |
| **Reader** | `http://127.0.0.1:8000/login` | `user@gmail.com` | `password` |

### 5. Preview Static UI Templates

Open `HTML/index.html` directly in your browser (or via VS Code Live Server) to preview all 17 frontend and portal templates.

---

## 👥 Team Division (4-Member Backend Workflow)

| Member       | Git Branch           | Module                   | Scope                                                                        |
| ------------ | -------------------- | ------------------------ | ---------------------------------------------------------------------------- |
| **Member 1** | `feat/auth-user`     | Auth & User Portal       | Login, Register, Role Middleware (`admin`, `author`, `user`), User Dashboard |
| **Member 2** | `feat/public-news`   | Public News & Search     | Homepage blocks, Blog listing, Search, Category/Tag filter, Article detail   |
| **Member 3** | `feat/author-studio` | Author Studio & Comments | Author Dashboard, CRUD Articles, Image Upload, Article Comments              |
| **Member 4** | `feat/admin-cms`     | Admin & Site Pages       | Admin Dashboard, Post Moderation, Categories/Tags, Contact & Newsletter      |

---

## 📝 Commit Convention

We follow [Conventional Commits](https://www.conventionalcommits.org/):

- `feat(...)`: New feature
- `fix(...)`: Bug fix
- `docs(...)`: Documentation & diagrams
- `style(...)`: UI/CSS changes
- `chore(...)`: Config, dependencies, or maintenance
