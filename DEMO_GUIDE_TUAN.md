# HƯỚNG DẪN CÀI ĐẶT, SỬ DỤNG & KỊCH BẢN THUYẾT TRÌNH ĐỒ ÁN (DEMO GUIDE)

> **Dự án:** Hệ thống Cổng thông tin Tin tức & Tạp chí Đa vai trò **Zaira News & Magazine Portal**  
> **Môn học:** Lập trình mã nguồn mở (Open Source Programming)  
> **Nhánh phát triển (Git Branch):** `feature/tuan-project-improvements`  
> **Kho lưu trữ GitHub chính thức:** `https://github.com/ArseneHerlock/laravel-news-portal.git`

Tài liệu này được biên soạn chi tiết bằng tiếng Việt dành cho các thành viên trong nhóm và phục vụ trực tiếp buổi **báo cáo – thuyết trình đồ án trước giảng viên**. Mọi câu lệnh, đường dẫn URL, tài khoản thử nghiệm và dữ liệu mẫu dưới đây đều được đối chiếu chính xác 100% với source code thực tế trên nhánh `feature/tuan-project-improvements`.

---

## MỤC LỤC

1. [Tổng quan dự án và công nghệ sử dụng](#1-tổng-quan-dự-án-và-công-nghệ-sử-dụng)
2. [Phần mềm cần thiết và thiết lập môi trường](#2-phần-mềm-cần-thiết-và-thiết-lập-môi-trường)
3. [Hướng dẫn cài đặt từng bước (Step-by-Step Installation)](#3-hướng-dẫn-cài-đặt-từng-bước-step-by-step-installation)
4. [Cấu hình Cơ sở dữ liệu, Migration và Seeding](#4-cấu-hình-cơ-sở-dữ-liệu-migration-và-seeding)
5. [Lệnh khởi động và chạy ứng dụng](#5-lệnh-khởi-động-và-chạy-ứng-dụng)
6. [Hướng dẫn sử dụng chi tiết từng chức năng chính](#6-hướng-dẫn-sử-dụng-chi-tiết-từng-chức-năng-chính)
7. [Kịch bản Demo từng bước khi báo cáo trước Giảng viên](#7-kịch-bản-demo-từng-bước-khi-báo-cáo-trước-giảng-viên)
8. [Danh sách tài khoản thử nghiệm và dữ liệu mẫu có sẵn](#8-danh-sách-tài-khoản-thử-nghiệm-và-dữ-liệu-mẫu-có-sẵn)
9. [Các lỗi thường gặp và cách khắc phục (Troubleshooting)](#9-các-lỗi-thường-gặp-và-cách-khắc-phục-troubleshooting)
10. [Lệnh Git dành cho các thành viên trong nhóm lấy nhánh về máy](#10-lệnh-git-dành-cho-các-thành-viên-trong-nhóm-lấy-nhánh-về-máy)
11. [Tổng hợp các thay đổi và nâng cấp quan trọng so với phiên bản gốc](#11-tổng-hợp-các-thay-đổi-và-nâng-cấp-quan-trọng-so-với-phiên-bản-gốc)

---

## 1. Tổng quan dự án và công nghệ sử dụng

### 1.1. Mục tiêu của hệ thống
**Zaira News & Magazine Portal** là một hệ thống quản trị và xuất bản báo điện tử đa vai trò (Multi-Role Editorial Content Management System). Hệ thống mô phỏng quy trình hoạt động thực tế của một tòa soạn báo trực tuyến hiện đại với 3 phân hệ người dùng rõ ràng:

- **Độc giả (Reader / Normal User — `role_id = 3`):** Đọc tin tức công khai, tìm kiếm, lọc bài viết theo chuyên mục/thẻ tag, nghe đọc bài báo (Text-to-Speech), bình luận đa cấp, lưu bài báo yêu thích (Bookmarks), đăng ký nhận bản tin (Newsletter), gửi liên hệ, gửi yêu cầu nâng cấp lên quyền Tác giả và tạo phiếu hỗ trợ (Support Tickets).
- **Tác giả / Phóng viên (Author / Editor — `role_id = 2`):** Làm việc tại **Author Studio** để soạn thảo bài báo mới (hỗ trợ đinh dạng bài viết thường và Video Blog, tải ảnh bìa, gắn thẻ Tags, tối ưu SEO Meta), lưu bản nháp (`draft`) hoặc gửi bài vào hàng đợi chờ duyệt (`pending`), theo dõi lượt đọc, bình luận và xem ghi chú phản hồi biên tập (`rejection_reason`) từ Tổng biên tập.
- **Quản trị viên / Tổng biên tập (Administrator — `role_id = 1`):** Làm việc tại **Admin Console** để kiểm duyệt bài viết trong hàng đợi (Duyệt xuất bản `published` hoặc Từ chối kèm lý do `rejected`), thực hiện thao tác hàng loạt (Bulk Actions), quản lý Chuyên mục (`categories`), Thẻ (`tags`), Kiểm duyệt bình luận (`comments`), Quản lý người dùng & phân quyền (`users`, `roles`) và xử lý Hộp thư liên hệ (`contact_messages`).

### 1.2. Công nghệ sử dụng (Tech Stack)
Toàn bộ dự án được xây dựng bằng các công nghệ mã nguồn mở (Open Source):

| Tầng (Layer) | Công nghệ / Thư viện | Phiên bản / Chi tiết trong dự án |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel Framework** (PHP) | `^13.17` (tương thích PHP `^8.3` và `8.4`) |
| **Kiến trúc phần mềm** | **MVC + Eloquent ORM + Middleware RBAC** | 5 Controllers, 17 Eloquent Models, `RoleMiddleware`, 18 Blade Views |
| **Cơ sở dữ liệu (Database)** | **SQLite** (Local mặc định) / **MySQL – TiDB Cloud** | 18 bảng nghiệp vụ quan hệ chuẩn 3NF + 3 bảng hệ thống Laravel (`cache`, `jobs`, `sessions`) |
| **Frontend & Giao diện** | **Blade Templates + Bootstrap 5.3 + Vanilla JS** | Giao diện Zaira Editorial, hỗ trợ **Dark Mode / Light Mode**, Responsive trên Desktop/Tablet/Mobile |
| **Hình ảnh & Biểu tượng** | **Local Vector SVG + FontAwesome 5 + Flaticon** | Bộ 15 ảnh SVG nội bộ (`public/assets/img/`) hoạt động 100% offline không phụ thuộc mạng ngoài |
| **Kiểm thử tự động (QA)** | **PHPUnit** | `^12.5.12` (7 bài kiểm thử tự động với 82 assertions kiểm tra toàn bộ luồng nghiệp vụ) |
| **Giấy phép nguồn mở** | **MIT License** | Khai báo tại file `LICENSE` ở thư mục gốc dự án |

### 1.3. Cấu trúc thư mục dự án
```text
laravel-news-portal/
├── Cloude/TiDB CLoud/          # Chứng chỉ SSL (isrgrootx1.pem) phục vụ kết nối TiDB Cloud
├── Database/
│   └── zaira_schema.sql        # File SQL thiết kế gốc gồm 18 bảng của hệ thống Zaira News
├── HTML/                       # Bản giao diện HTML tĩnh đối chiếu (đã làm sạch 100% link ngoài)
├── Src/NewsWebsite/            # MÃ NGUỒN CHÍNH CỦA ỨNG DỤNG LARAVEL FULL-STACK
│   ├── app/
│   │   ├── Http/Controllers/   # 5 Controllers: PublicNews, Auth, UserPortal, AuthorPortal, AdminPortal
│   │   ├── Http/Middleware/    # RoleMiddleware.php (Kiểm soát phân quyền RBAC & chặn tài khoản bị khóa)
│   │   └── Models/             # 17 Eloquent Models ánh xạ đầy đủ 18 bảng trong CSDL
│   ├── database/
│   │   ├── migrations/         # Các file Migration tạo toàn bộ bảng và khóa ngoại
│   │   └── seeders/            # DatabaseSeeder.php nạp sẵn dữ liệu mẫu phong phú
│   ├── public/assets/          # Toàn bộ CSS, Fonts, JS (zaira-lite.js) và 15 ảnh SVG nội bộ
│   ├── resources/views/        # 18 file giao diện Blade (layouts, public, auth, portal)
│   ├── routes/web.php          # Định tuyến toàn bộ hệ thống Public, Auth, Reader, Author, Admin
│   └── tests/Feature/          # Bộ kiểm thử tự động ZairaNewsPortalTest.php
├── DEMO_GUIDE_TUAN.md          # Tài liệu hướng dẫn cài đặt & kịch bản thuyết trình (file này)
├── LICENSE                     # Giấy phép mã nguồn mở MIT
└── README.md                   # Tài liệu giới thiệu chung của nhóm
```

---

## 2. Phần mềm cần thiết và thiết lập môi trường

Trước khi cài đặt dự án, máy tính cần có sẵn các phần mềm mã nguồn mở sau (khuyến nghị dùng **Laragon** hoặc **XAMPP** trên Windows):

1. **PHP:** Phiên bản **8.3 trở lên** (đã kiểm thử thực tế trên `PHP 8.3.36`).
   - Các PHP extensions cần bật trong `php.ini`: `pdo_sqlite`, `sqlite3`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `ctype`, `json`, `xml`, `zip`.
2. **Composer:** Trình quản lý thư viện PHP (phiên bản `2.x`).
3. **Git:** Để lấy mã nguồn và chuyển nhánh.
4. **Trình duyệt web hiện đại:** Google Chrome, Microsoft Edge hoặc Mozilla Firefox (hỗ trợ Web Speech API để demo tính năng đọc báo bằng giọng nói).
5. **Cơ sở dữ liệu:**
   - **Chế độ 1 (Khuyến nghị khi chạy Local & Demo trên lớp):** Sử dụng **SQLite** tích hợp sẵn trong PHP — không cần bật MySQL Server hay kết nối Internet vẫn chạy mượt mà 100%.
   - **Chế độ 2 (Kết nối đám mây nhóm):** Sử dụng **TiDB Cloud (MySQL)** thông qua chứng chỉ bảo mật `Cloude/TiDB CLoud/isrgrootx1.pem`.

Kiểm tra nhanh môi trường trên cửa sổ Terminal / PowerShell:
```powershell
php -v
composer -V
git --version
```

---

## 3. Hướng dẫn cài đặt từng bước (Step-by-Step Installation)

> **Lưu ý quan trọng:** Toàn bộ mã nguồn ứng dụng Laravel nằm trong thư mục `Src/NewsWebsite`. Mọi lệnh `composer` và `php artisan` đều phải được chạy bên trong thư mục `Src/NewsWebsite`.

### Bước 1: Mở Terminal và di chuyển vào thư mục ứng dụng Laravel
Từ thư mục gốc của dự án (`laravel-news-portal`), chạy lệnh:
```powershell
cd Src/NewsWebsite
```

### Bước 2: Cài đặt các thư viện phụ thuộc bằng Composer
Chạy lệnh sau để cài đặt các gói thư viện trong `vendor/`:
```powershell
composer install
```
*(Mẹo: Nếu máy dùng Laragon chưa bật sẵn `extension=zip` trong `php.ini`, bạn có thể chạy lệnh sau để bật `zip` tạm thời ngay trong dòng lệnh mà không cần sửa cấu hình máy:)*
```powershell
php -d extension=zip "C:\ProgramData\ComposerSetup\bin\composer.phar" install --no-interaction --prefer-dist
```

### Bước 3: Tạo file cấu hình môi trường `.env`
Sao chép file mẫu `.env.example` thành `.env`:
- **Trên Windows PowerShell:**
  ```powershell
  Copy-Item .env.example .env
  ```
- **Trên Git Bash / macOS / Linux:**
  ```bash
  cp .env.example .env
  ```

### Bước 4: Khởi tạo khóa bảo mật ứng dụng (`APP_KEY`)
```powershell
php artisan key:generate
```

### Bước 5: Tạo liên kết thư mục lưu trữ ảnh tải lên (`storage:link`)
Để các ảnh đại diện (`avatar`) và ảnh bìa bài viết (`featured_image`) do người dùng tải lên hiển thị được ngoài giao diện:
```powershell
php artisan storage:link
```

---

## 4. Cấu hình Cơ sở dữ liệu, Migration và Seeding

Hệ thống hỗ trợ linh hoạt **2 chế độ Cơ sở dữ liệu**. File `.env.example` đã được cấu hình mặc định ở **Chế độ 1 (SQLite)** để bất kỳ thành viên nào trong nhóm tải về cũng chạy được ngay lập tức.

### 4.1. Chế độ 1: Sử dụng SQLite Local (Mặc định – Nhanh & Ổn định nhất khi thuyết trình)

1. Trong file `Src/NewsWebsite/.env`, đảm bảo dòng cấu hình là:
   ```env
   DB_CONNECTION=sqlite
   ```
2. Tạo file cơ sở dữ liệu SQLite rỗng (nếu chưa có):
   - **Trên Windows PowerShell:**
     ```powershell
     if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File -Path database/database.sqlite }
     ```
   - **Trên Git Bash / Linux / macOS:**
     ```bash
     touch database/database.sqlite
     ```
3. Chạy lệnh tạo toàn bộ 18 bảng và nạp dữ liệu mẫu (Migration + Seeding):
   ```powershell
   php artisan migrate:fresh --seed
   ```

### 4.2. Chế độ 2: Kết nối cơ sở dữ liệu đám mây TiDB Cloud (MySQL)

Nếu muốn chuyển sang cơ sở dữ liệu đám mây chung của nhóm (`NewsDB` trên TiDB Cloud):

1. Mở file `Src/NewsWebsite/.env`, đóng comment dòng `DB_CONNECTION=sqlite` và bỏ comment cụm cấu hình TiDB Cloud:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
   DB_PORT=4000
   DB_DATABASE=NewsDB
   DB_USERNAME=3AnYdBThXPvmUxi.root
   DB_PASSWORD=<nhập_mật_khẩu_tidb_cloud_của_nhóm>
   MYSQL_ATTR_SSL_CA="../../Cloude/TiDB CLoud/isrgrootx1.pem"
   ```
2. Chạy lệnh khởi tạo bảng và nạp dữ liệu mẫu:
   ```powershell
   php artisan migrate --seed
   ```

### 4.3. Kết quả khi chạy `php artisan migrate:fresh --seed` thành công
Khi chạy lệnh, Terminal sẽ hiển thị 4 file migration được thực thi hoàn tất:
- `0001_01_01_000000_create_users_table` (Tạo bảng `roles`, `users`, `password_reset_tokens`, `sessions`)
- `0001_01_01_000001_create_cache_table` (Tạo bảng `cache`, `cache_locks`)
- `0001_01_01_000002_create_jobs_table` (Tạo bảng `jobs`, `job_batches`, `failed_jobs`)
- `2026_11_09_000001_create_zaira_news_tables` (Tạo 16 bảng nghiệp vụ báo chí: `author_profiles`, `languages`, `categories`, `tags`, `posts`, `post_tag`, `media`, `comments`, `bookmarks`, `newsletters`, `contact_messages`, `tickets`, `ticket_replies`, `custom_pages`, `advertisements`, `settings`)

---

## 5. Lệnh khởi động và chạy ứng dụng

### 5.1. Khởi động Web Server cục bộ
Đảm bảo bạn đang đứng tại thư mục `Src/NewsWebsite`, chạy lệnh:
```powershell
php artisan serve
```
Sau khi chạy, mở trình duyệt web và truy cập địa chỉ:
- **Trang chủ ứng dụng:** `http://127.0.0.1:8000`

*(Nếu cổng `8000` đang bận bởi ứng dụng khác, bạn có thể chạy trên cổng `8080` bằng lệnh `php artisan serve --port=8080` và truy cập `http://127.0.0.1:8080`).*

### 5.2. Chạy bộ kiểm thử tự động (Automated Tests)
Để kiểm tra toàn bộ chức năng của hệ thống bằng PHPUnit trước khi thuyết trình:
```powershell
cd Src/NewsWebsite
php artisan test
```
> **Lưu ý:** Sau khi chạy `php artisan test`, nếu muốn làm mới lại dữ liệu mẫu trong database SQLite, chỉ cần chạy lại `php artisan migrate:fresh --seed`.

---

## 6. Hướng dẫn sử dụng chi tiết từng chức năng chính

### 6.1. Phân hệ Độc giả & Trang tin tức công khai (Public News Portal)

| Chức năng | Đường dẫn (URL) | Hướng dẫn thao tác |
| :--- | :--- | :--- |
| **Trang chủ (Homepage)** | `http://127.0.0.1:8000/` | Xem thanh tin nóng (**Breaking News Ticker**), bài viết tiêu điểm (**Featured Hero Story**), danh sách bài mới nhất (**Latest Stories**), chuyên mục nổi bật, bảng xếp hạng bài đọc nhiều nhất và thanh công cụ chuyển đổi **Dark Mode / Light Mode**. |
| **Danh sách & Lọc bài viết** | `http://127.0.0.1:8000/blogs` | Lọc bài báo theo chuyên mục (`?category=technology`), theo thẻ tag (`?tag=open-source`), hoặc tìm kiếm theo từ khóa (`?search=Laravel`) trên thanh tìm kiếm. |
| **Chi tiết bài báo & Tiện ích đọc** | `http://127.0.0.1:8000/blog/{slug}` | Mỗi lần mở bài viết, hệ thống tự động cộng `+1` vào lượt xem (`views_count`). Trang hỗ trợ: **Thanh tiến trình đọc** trên đỉnh trang, **Thời gian đọc ước tính** (`min read`), **Mục lục tự động (Auto TOC)** trích xuất từ các thẻ `<h2>/<h3>`, và nút **Listen to Article** (đọc bài báo bằng giọng nói). |
| **Bình luận & Trả lời (Comments)** | Tại cuối trang `/blog/{slug}` | Khi đã đăng nhập, độc giả có thể viết bình luận mới hoặc bấm nút **Reply** để trả lời một bình luận có sẵn (hỗ trợ bình luận đa cấp `parent_id`). |
| **Lưu bài viết (Bookmark)** | Nút **Bookmark / Saved** trên trang `/blog/{slug}` | Nhấp vào nút **Bookmark** ngay dưới tiêu đề bài báo để lưu vào danh sách đọc sau (nhấp lần nữa để bỏ lưu). |
| **Hồ sơ công khai của Tác giả** | `http://127.0.0.1:8000/author-profile/sarah-jenkins` | Xem thông tin tiểu sử, chức danh, liên kết mạng xã hội, tổng số bài đã xuất bản, tổng lượt đọc và danh sách các bài báo của tác giả đó. |
| **Trang Giới thiệu, Liên hệ, Chính sách** | `/about`, `/contact`, `/privacy-policy` | Xem đội ngũ biên tập (`/about`), gửi tin nhắn liên hệ trực tiếp vào CSDL (`/contact`), xem chính sách bảo mật động từ bảng `custom_pages` (`/privacy-policy`). |
| **Nguồn cấp RSS 2.0 XML** | `http://127.0.0.1:8000/rss.xml` | Xuất chuẩn XML RSS 2.0 gồm 20 bài viết mới nhất. |

### 6.2. Phân hệ Xác thực & Chuyển đổi nhanh vai trò (Authentication & Demo Switcher)

- **Đăng nhập Độc giả:** `http://127.0.0.1:8000/login` (hoặc `/user/login`)
- **Đăng ký tài khoản mới:** `http://127.0.0.1:8000/register` (hoặc `/user/register`) — Tự động sinh `username` duy nhất từ họ tên và đăng nhập thẳng vào Reader Hub.
- **Đăng nhập Tác giả (Author Studio):** `http://127.0.0.1:8000/author/login`
- **Đăng nhập Quản trị viên (Admin Console):** `http://127.0.0.1:8000/admin/login`
- **Tính năng hỗ trợ thuyết trình (1-Click Demo Credentials & Switcher):**
  - Tại các trang đăng nhập, có sẵn các nút **Fill Reader Demo**, **Fill Author Demo**, **Fill Admin Demo** giúp điền tự động email và mật khẩu chỉ với 1 cú nhấp chuột.
  - Ngoài ra, ở góc trên bên phải của thanh Header còn có bộ nút chuyển nhanh phiên đăng nhập (`/demo-switch/user`, `/demo-switch/author`, `/demo-switch/admin`) giúp tiết kiệm thời gian khi báo cáo trước giảng viên.

### 6.3. Phân hệ Bảng điều khiển Độc giả (Reader Hub — `/user/dashboard`)

Sau khi đăng nhập bằng tài khoản Độc giả (`user@gmail.com`):
1. **Saved Bookmarks:** Xem danh sách các bài báo đã lưu và bấm nút bỏ lưu nhanh.
2. **My Recent Comments:** Theo dõi các bình luận của bản thân và trạng thái kiểm duyệt (`approved`, `pending`).
3. **Update Profile & Change Password:** Cập nhật họ tên, số điện thoại, tiểu sử (`bio`), tải lên ảnh đại diện (`avatar`) mới hoặc đổi mật khẩu.
4. **Become a Contributing Author:** Gửi đơn xin nâng cấp tài khoản thành Tác giả (kèm chức danh đề xuất `designation`) để Admin phê duyệt.
5. **Editorial Support Tickets:** Tạo phiếu hỗ trợ kỹ thuật (`low`, `medium`, `high`) gửi tới ban quản trị.

### 6.4. Phân hệ Phòng làm việc Tác giả (Author Studio — `/author/dashboard`)

Sau khi đăng nhập bằng tài khoản Tác giả (`author@gmail.com`):
1. **Author Dashboard (`/author/dashboard`):** Xem 4 chỉ số thống kê biên tập gồm: *Published Articles* (Bài đã xuất bản), *Awaiting Moderation* (Bài đang chờ duyệt), *Total Article Reads* (Tổng lượt đọc tích lũy) và *Reader Comments* (Bình luận của độc giả trên bài viết của mình).
2. **Quản lý bài viết cá nhân (`/author/posts`):** Lọc bài viết của mình theo trạng thái (`All`, `Published`, `Pending`, `Draft`, `Rejected`) hoặc tìm kiếm theo tiêu đề. Nếu bài viết bị từ chối, lý do từ chối của Admin (**Editor Note**) sẽ hiển thị màu đỏ ngay dưới tiêu đề bài.
3. **Soạn thảo bài viết mới (`/author/posts/create`):**
   - Nhập Tiêu đề, Đường dẫn tĩnh (Slug — để trống hệ thống tự sinh không trùng lặp), Chuyên mục, Ngôn ngữ, Tóm tắt và Nội dung HTML.
   - Bật công tắc **Is Video Blog?** (`#is_video_blog`) nếu muốn nhúng đường dẫn Video YouTube/Vimeo.
   - Chọn ảnh bìa (`#cover-image`) với tính năng **xem trước ảnh trực tiếp (Live Image Preview)** trước khi tải lên.
   - Gắn nhiều thẻ Tags và điền thông tin SEO (`meta_title`, `meta_description`, `meta_keywords`).
   - **Quy tắc nghiệp vụ quan trọng:** Khi Tác giả gửi bài (dù chọn *Submit for Review*), hệ thống luôn đưa bài vào trạng thái **`pending` (Chờ duyệt)** chứ không cho phép Tác giả tự ý xuất bản thẳng lên trang chủ.

### 6.5. Phân hệ Quản trị Toàn hệ thống (Admin Console — `/admin/dashboard`)

Sau khi đăng nhập bằng tài khoản Quản trị viên (`admin@gmail.com`):
1. **Editorial Overview (`/admin/dashboard`):**
   - Xem thống kê toàn cổng thông tin (Tổng bài viết, Số bài chờ duyệt, Tổng lượt xem toàn trang, Tổng số thành viên).
   - **Editorial Moderation Queue:** Bảng duyệt nhanh các bài viết đang ở trạng thái `pending` hoặc `draft`. Admin có thể bấm **Publish** (duyệt đăng ngay) hoặc **Reject** (từ chối kèm lý do gửi lại cho tác giả).
   - **Categories & Tags Management (`#categories-section`):** Thêm/xóa chuyên mục (có kiểm tra an toàn: không cho xóa chuyên mục đang chứa bài viết) và thêm/xóa thẻ Tag.
   - **Comment Moderation (`#comments-section`):** Duyệt (`Approve`), ẩn/từ chối (`Reject`) hoặc xóa các bình luận của độc giả.
   - **Users & Role Governance (`#users-section`):** Thay đổi vai trò của bất kỳ người dùng nào giữa `Reader`, `Author`, `Admin` hoặc khóa tài khoản (`Banned`). Hệ thống có cơ chế bảo vệ không cho phép Admin tự hạ quyền hoặc tự khóa tài khoản của chính mình.
   - **Contact Inquiries (`#messages-section`):** Xem nội dung tin nhắn do độc giả gửi từ trang `/contact` và đánh dấu đã đọc (`Mark Read`).
2. **Quản lý & Kiểm duyệt toàn bộ bài viết (`/admin/posts`):**
   - Hỗ trợ tìm kiếm, lọc theo trạng thái/chuyên mục, thao tác hàng loạt (**Bulk Actions**: Publish, Set Pending, Reject, Delete nhiều bài cùng lúc), và bật/tắt các cờ hiển thị trang chủ (`Featured`, `Breaking`, `Slider`).

---

## 7. Kịch bản Demo từng bước khi báo cáo trước Giảng viên

Kịch bản dưới đây được thiết kế gói gọn trong **7–10 phút**, đi qua một vòng đời xuất bản báo chí khép kín (**Độc giả $\rightarrow$ Tác giả viết bài $\rightarrow$ Tổng biên tập kiểm duyệt $\rightarrow$ Xuất bản công khai**) giúp gây ấn tượng mạnh về tính hoàn thiện của đồ án.

### Phần 1: Trải nghiệm Độc giả & Giao diện Trang tin tức (2 phút)
1. Mở trang chủ `http://127.0.0.1:8000/`.
2. Giới thiệu bố cục báo điện tử hiện đại: thanh tin nóng **Breaking News**, khu vực bài tiêu điểm **Featured**, bộ lọc chuyên mục trên thanh điều hướng và bấm thử nút **Dark Mode** (biểu tượng mặt trăng trên thanh top-bar) để trình diễn giao diện Tối/Sáng mượt mà.
3. Nhập từ khóa `TiDB` hoặc `Accessible` vào ô tìm kiếm để lọc bài báo tại `/blogs`.
4. Nhấp vào bài viết **"A Step-By-Step Guide To Building Accessible Carousels & Modern Web Interfaces"**:
   - Chỉ cho giảng viên thấy số lượt xem (`views_count`) tăng thêm `1` khi tải trang.
   - Cuộn trang để thấy **Thanh tiến trình đọc (Reading Progress Bar)** chạy trên đỉnh màn hình và **Mục lục tự động (Table of Contents)** bên trong bài viết.
   - Bấm nút **Listen to Article** để trình duyệt đọc nội dung bài báo bằng giọng nói, sau đó bấm lần nữa để dừng.

### Phần 2: Đăng nhập Độc giả — Lưu bài & Bình luận (1.5 phút)
1. Bấm nút **Sign In** trên góc phải (`http://127.0.0.1:8000/login`), nhấp nút **Fill Reader Demo** (`user@gmail.com`) rồi bấm **Sign In to Zaira**.
2. Hệ thống chuyển vào **Reader Hub** (`/user/dashboard`), hiển thị sẵn 2 bài báo đã lưu trong mục **Saved Bookmarks**.
3. Quay lại một bài báo bất kỳ, bấm nút **Bookmark** và gửi một bình luận mới *"Đồ án môn Lập trình mã nguồn mở hoạt động rất mượt mà!"* — bình luận xuất hiện ngay lập tức ở phần thảo luận.

### Phần 3: Tác giả soạn thảo bài báo mới tại Author Studio (2.5 phút)
1. Sử dụng thanh **Demo Switcher** ở góc trên bên phải, bấm vào nút **Author** (hoặc truy cập `/author/login` và bấm **Fill Author Demo** — `author@gmail.com`).
2. Tại **Author Studio Dashboard** (`/author/dashboard`), giới thiệu 4 thẻ KPI thống kê chuyên biệt cho phóng viên (đã loại bỏ hoàn toàn các mục E-Commerce không phù hợp).
3. Bấm vào **Create New Article** (`/author/posts/create`):
   - Nhập tiêu đề: `Ứng dụng Laravel 13 và Phần mềm Nguồn mở trong Báo điện tử Hiện đại`
   - Chọn chuyên mục: `Technology`
   - Nhập tóm tắt và nội dung (có thẻ `<h2>Tổng quan kiến trúc</h2><p>Nội dung chi tiết...</p>`).
   - Bấm thử công tắc **Is Video Blog?** để cho giảng viên thấy ô nhập URL Video ẩn/hiện linh hoạt, và chọn thử 1 file ảnh từ máy tính để trình diễn tính năng **Live Cover Preview**.
   - Chọn trạng thái **Submit for Review (Pending)** rồi bấm **Save & Submit Article**.
4. Hệ thống chuyển về `/author/posts` với thông báo xanh: *"Article submitted to the Editorial Queue (Pending Admin Approval)"*. Giải thích với giảng viên: **Tác giả không thể tự xuất bản bài viết khi chưa có sự phê duyệt của Tổng biên tập**.

### Phần 4: Tổng biên tập (Admin) Kiểm duyệt, Phản hồi & Xuất bản (2.5 phút)
1. Trên thanh **Demo Switcher**, bấm nút **Admin** (tài khoản `admin@gmail.com`) để vào **Admin Console** (`/admin/dashboard`).
2. Ngay tại bảng **Editorial Moderation Queue**, bài viết vừa tạo của tác giả Sarah Jenkins xuất hiện ở đầu hàng đợi với nhãn vàng `Pending`:
   - **Thao tác 1 (Minh họa Từ chối kèm Ghi chú biên tập):** Tại trang `/admin/posts`, bấm nút **Reject** (biểu tượng dấu nhân đỏ) trên một bài chờ duyệt, nhập ghi chú *"Cần bổ sung thêm số liệu trích dẫn ở mục 2"* rồi xác nhận. Khi quay lại giao diện Author, tác giả sẽ thấy dòng chữ đỏ **Editor Note** hiển thị đúng ghi chú này.
   - **Thao tác 2 (Phê duyệt Xuất bản):** Bấm nút xanh **Publish** trên bài viết *"Ứng dụng Laravel 13 và Phần mềm Nguồn mở..."*.
3. Bấm **View Live Portal** để ra ngoài trang chủ `http://127.0.0.1:8000/`: bài báo vừa duyệt đã chính thức xuất hiện công khai trên trang chủ cho mọi độc giả!

### Phần 5: Minh chứng Kiểm thử tự động (QA Verification — 1 phút)
1. Mở cửa sổ Terminal trong thư mục `Src/NewsWebsite` và chạy lệnh:
   ```powershell
   php artisan test
   ```
2. Trình chiếu kết quả **7 passed (82 assertions)** hoàn thành trong ~1.3 giây, chứng minh toàn bộ phân quyền RBAC, luồng duyệt bài và bảo mật đều được kiểm chứng tự động.

---

## 8. Danh sách tài khoản thử nghiệm và dữ liệu mẫu có sẵn

Khi chạy lệnh `php artisan migrate:fresh --seed`, file `DatabaseSeeder.php` khởi tạo sẵn **4 tài khoản thực tế** (tất cả đều dùng chung mật khẩu là `password`):

### 8.1. Bảng tài khoản thử nghiệm (Test Accounts)

| Vai trò (Role) | Họ tên hiển thị | Email đăng nhập | Username | Mật khẩu | Đường dẫn Dashboard sau khi đăng nhập |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Administrator** (`role_id = 1`) | Alex Morgan (Editor-in-Chief) | `admin@gmail.com` | `admin` | `password` | `/admin/dashboard` |
| **Author / Editor** (`role_id = 2`) | Sarah Jenkins | `author@gmail.com` | `sarah-jenkins` | `password` | `/author/dashboard` |
| **Author / Editor** (`role_id = 2`) | David Tran | `david.tran@zaira.dev` | `david-tran` | `password` | `/author/dashboard` |
| **Normal User / Reader** (`role_id = 3`) | Minh Tuan (Reader) | `user@gmail.com` | `minh-tuan` | `password` | `/user/dashboard` |

### 8.2. Dữ liệu mẫu có sẵn trong Cơ sở dữ liệu (Sample Data)
- **3 Ngôn ngữ (`languages`):** English (`en` — mặc định), Arabic (`ar` — RTL), Vietnamese (`vi`).
- **6 Chuyên mục (`categories`):** `Technology`, `Featured`, `Trending`, `Insights`, `Community`, `Opinion`.
- **10 Thẻ (`tags`):** `Open Source`, `Laravel`, `Cloud Database`, `Cybersecurity`, `Web Accessibility`, `Artificial Intelligence`, `UI/UX Design`, `DevOps`, `FinTech`, `Science`.
- **9 Bài báo mẫu (`posts`) thuộc đủ các trạng thái để tiện kiểm thử:**
  - **7 bài đã xuất bản (`published`):** Hiển thị ngoài trang chủ và trang danh sách `/blogs`.
  - **1 bài đang chờ duyệt (`pending`):** *"Upcoming Web Standards in 2027: Native CSS Grid Subgrid & View Transitions"* — nằm sẵn trong hàng đợi duyệt bài của Admin.
  - **1 bài lưu nháp (`draft`):** *"Draft: Benchmarking Full-Text Search Strategies in Relational Databases"* — nằm trong danh sách bài nháp của tác giả Sarah Jenkins.
- **Dữ liệu tương tác có sẵn:**
  - **2 Bình luận mẫu (`comments`):** Bình luận gốc của `Minh Tuan (Reader)` và bình luận trả lời (`parent_id`) của tác giả `Sarah Jenkins` trên bài viết đầu tiên.
  - **2 Bài báo đã lưu (`bookmarks`):** Của tài khoản `user@gmail.com`.
  - **1 Tin nhắn liên hệ chưa đọc (`contact_messages`):** Từ `Dr. Nguyen Van A (partner@university.edu.vn)` hiển thị trong mục *Contact Inquiries* của Admin.
  - **1 Trang tĩnh tùy chỉnh (`custom_pages`):** Trang *Privacy Policy & Terms of Use* (`slug: privacy-policy`).
  - **1 Banner quảng cáo (`advertisements`)** và **6 cấu hình hệ thống (`settings`)**.

---

## 9. Các lỗi thường gặp và cách khắc phục (Troubleshooting)

### Lỗi 1: `No application encryption key has been specified` (Lỗi 500 khi vừa mở trang)
- **Nguyên nhân:** Chưa tạo file `.env` hoặc chưa sinh khóa `APP_KEY`.
- **Cách khắc phục:**
  ```powershell
  cd Src/NewsWebsite
  Copy-Item .env.example .env
  php artisan key:generate
  php artisan config:clear
  ```

### Lỗi 2: `Database file at path [database/database.sqlite] does not exist` hoặc `SQLSTATE[HY000]: General error: 1 no such table: posts`
- **Nguyên nhân:** Chưa tạo file `database.sqlite` hoặc chưa chạy lệnh migration.
- **Cách khắc phục:**
  ```powershell
  cd Src/NewsWebsite
  if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File -Path database/database.sqlite }
  php artisan migrate:fresh --seed
  ```

### Lỗi 3: Composer báo lỗi `The zip extension and unzip/7z commands are both missing` hoặc lỗi phiên bản PHP trên Windows
- **Nguyên nhân:** File `php.ini` của Laragon/XAMPP chưa bật `extension=zip`.
- **Cách khắc phục:** Chạy Composer kèm cờ `-d extension=zip` ngay trên dòng lệnh:
  ```powershell
  cd Src/NewsWebsite
  php -d extension=zip "C:\ProgramData\ComposerSetup\bin\composer.phar" install --no-interaction --prefer-dist
  ```
  *(Hoặc mở file `php.ini`, bỏ dấu `;` trước dòng `;extension=zip` và lưu lại).*

### Lỗi 4: Kết nối TiDB Cloud báo lỗi SSL (`SQLSTATE[HY000] [2002]` hoặc `SSL certificate problem`)
- **Nguyên nhân:** Đường dẫn tới file chứng chỉ `isrgrootx1.pem` trong `.env` bị sai hoặc mật khẩu TiDB Cloud chưa chính xác.
- **Cách khắc phục:**
  1. Kiểm tra dòng `MYSQL_ATTR_SSL_CA="../../Cloude/TiDB CLoud/isrgrootx1.pem"` trong `Src/NewsWebsite/.env`.
  2. Nếu đang thuyết trình ở nơi mạng Wi-Fi trường học chặn cổng `4000`, hãy đổi `DB_CONNECTION=sqlite` trong `Src/NewsWebsite/.env` rồi chạy `php artisan config:clear` và `php artisan migrate:fresh --seed` để chạy offline 100% không phụ thuộc mạng.

### Lỗi 5: Ảnh tải lên khi tạo bài viết mới không hiển thị
- **Nguyên nhân:** Chưa tạo symbolic link từ `storage/app/public` sang `public/storage`.
- **Cách khắc phục:**
  ```powershell
  cd Src/NewsWebsite
  php artisan storage:link
  ```

---

## 10. Lệnh Git dành cho các thành viên trong nhóm lấy nhánh về máy

Các bạn trong nhóm có thể lấy toàn bộ mã nguồn đã nâng cấp trên nhánh `feature/tuan-project-improvements` về máy mà **không làm ảnh hưởng tới nhánh `main` hay nhánh cá nhân của thành viên khác** theo đúng các lệnh sau:

### Trường hợp 1: Máy đã clone sẵn repository của nhóm
```bash
# 1. Kiểm tra trạng thái máy hiện tại (lưu hoặc stash các thay đổi dở dang nếu có)
git status

# 2. Tải thông tin các nhánh mới nhất từ GitHub về máy
git fetch origin

# 3. Chuyển sang nhánh feature/tuan-project-improvements
git checkout feature/tuan-project-improvements

# 4. Cập nhật code mới nhất trên nhánh
git pull origin feature/tuan-project-improvements

# 5. Xác nhận đang đứng đúng trên nhánh feature/tuan-project-improvements
git branch --show-current
```

### Trường hợp 2: Máy mới hoàn toàn (Clone trực tiếp nhánh về máy)
```bash
git clone -b feature/tuan-project-improvements https://github.com/ArseneHerlock/laravel-news-portal.git
cd laravel-news-portal/Src/NewsWebsite
```

---

## 11. Tổng hợp các thay đổi và nâng cấp quan trọng so với phiên bản gốc

Bảng dưới đây tóm tắt những điểm khác biệt cốt lõi giữa **phiên bản gốc trên nhánh `main`** và **phiên bản đã hoàn thiện trên nhánh `feature/tuan-project-improvements`** để nhóm dễ dàng trình bày trước hội đồng/giảng viên:

| Hạng mục | Phiên bản gốc (`main`) | Phiên bản nâng cấp (`feature/tuan-project-improvements`) |
| :--- | :--- | :--- |
| **1. Kiến trúc Laravel Backend (`Src/NewsWebsite/`)** | Chỉ là bộ khung Laravel mới cài đặt với 1 route `/` mặc định, chưa có Models, Controllers hay Views nghiệp vụ. | Hoàn thiện **Full-Stack MVC**: 17 Eloquent Models, 5 Controllers, `RoleMiddleware`, 18 Blade Views và hệ thống định tuyến đầy đủ trong `routes/web.php`. |
| **2. Cơ sở dữ liệu & Dữ liệu mẫu** | Chỉ có file `Database/zaira_schema.sql` rời bên ngoài, chưa tích hợp vào Laravel Migrations hay Seeder. | Tích hợp đầy đủ **18 bảng** vào Laravel Migrations (bổ sung cột `rejection_reason` và composite index `idx_posts_feed`), kèm `DatabaseSeeder.php` nạp sẵn dữ liệu 3 vai trò, 6 chuyên mục, 10 tags, 9 bài viết. |
| **3. Bảo mật thông tin (Security)** | File `Cloude/TiDB CLoud/password.txt` chứa mật khẩu DB thật bị theo dõi trên Git; thiếu CSRF và RBAC. | Đã gỡ `password.txt` khỏi Git tracking, bổ sung quy tắc chặn vào `.gitignore`, bảo vệ toàn bộ form bằng `@csrf`, mã hóa mật khẩu `bcrypt` và phân quyền chặt chẽ bằng `RoleMiddleware`. |
| **4. Giao diện Author & Admin (Loại bỏ rác E-Commerce)** | Sidebar và Dashboard của Author/Admin bị lẫn hàng chục menu bán hàng (`Products`, `Orders`, `Coupons`, `Shipping`, `Total Sale`) và lỗi chuỗi dịch thô `backend::translate.create_new_blog`. | **Xóa sạch 100% thành phần E-Commerce** và chuỗi dịch lỗi; thiết kế lại thành **Author Studio** và **Admin Console** chuyên biệt cho tòa soạn báo điện tử. |
| **5. Liên kết ngoài & Tài nguyên ảnh** | Tồn tại **519 liên kết** và form action trỏ ra domain ngoài `https://zaira.themedox.com`, khiến bấm vào menu hay tìm kiếm bị văng khỏi web của nhóm. | Thay thế toàn bộ 519 liên kết ngoài bằng **Laravel Named Routes** nội bộ; bổ sung bộ **15 ảnh vector SVG** sắc nét hoạt động hoàn toàn offline. |
| **6. Sửa lỗi JavaScript & Form** | Trang Login/Register bị hiển thị sẵn lỗi đỏ *"The email field is required"* ngay khi mới mở; lỗi `ReferenceError: togglePassword is not defined`; kẹt nút đóng/mở Sidebar và công tắc Video Blog. | Xóa bỏ class `.is-invalid` giả tạo; bổ sung đầy đủ `togglePassword()`, điều khiển Sidebar, công tắc Video Blog và xem trước ảnh bìa trực tiếp trong `zaira-lite.js`. |
| **7. Tính năng mới nâng cao giá trị đồ án** | Chưa có luồng duyệt bài thực tế, chưa có tiện ích hỗ trợ đọc báo hay RSS. | Bổ sung **Quy trình Biên tập – Kiểm duyệt kèm ghi chú từ chối**, **Nghe đọc bài báo (Text-to-Speech)**, **Mục lục tự động (Auto TOC)**, **Thanh tiến trình đọc**, **Thời gian đọc ước tính** và **RSS 2.0 XML Feed (`/rss.xml`)**. |
| **8. Kiểm thử tự động & Chuẩn mã nguồn mở** | Chưa có bài kiểm thử nghiệp vụ, thiếu file `LICENSE`. | Bổ sung file giấy phép nguồn mở `LICENSE` (MIT) và bộ kiểm thử `ZairaNewsPortalTest.php` đạt **7/7 tests PASSED (82 assertions)**. |
