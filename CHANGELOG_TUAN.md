# BÁO CÁO TỔNG HỢP LỊCH SỬ PHÁT TRIỂN & THAY ĐỔI MÃ NGUỒN (CHANGELOG)

> **Dự án:** Hệ thống Cổng thông tin Tin tức & Tạp chí Đa vai trò **Zaira News & Magazine Portal**  
> **Môn học:** Lập trình mã nguồn mở (Open Source Programming)  
> **Nhánh tích hợp gốc của nhóm (Base Branch):** `main` (Commit gốc: `2bb3c88`)  
> **Nhánh phát triển được đối chiếu (Compare Branch):** `feature/tuan-project-improvements` (HEAD: `12f6a4a`)  
> **Thống kê Git Diff (`git diff --shortstat main..feature/tuan-project-improvements`):** `186 files changed, 63,790 insertions(+), 862 deletions(-)`

Tài liệu này tổng hợp đầy đủ, trung thực và chính xác các thay đổi thực tế trên nhánh `feature/tuan-project-improvements` so với nhánh `main` của nhóm dựa trên kết quả kiểm tra `git diff main..feature/tuan-project-improvements` và lịch sử commit.

---

## MỤC LỤC

1. [Tổng quan các thay đổi so với phiên bản gốc (`main`)](#1-tổng-quan-các-thay-đổi-so-với-phiên-bản-gốc-main)
2. [Các tính năng mới đã được triển khai (New Features Implemented)](#2-các-tính-năng-mới-đã-được-triển-khai-new-features-implemented)
3. [Các tính năng hiện có đã được sửa lỗi và hoàn thiện](#3-các-tính-năng-hiện-có-đã-được-sửa-lỗi-và-hoàn-thiện)
4. [Cải tiến giao diện và trải nghiệm người dùng (UI/UX & Visual Design)](#4-cải-tiến-giao-diện-và-trải-nghiệm-người-dùng-uiux--visual-design)
5. [Danh sách các Bug đã khắc phục và nguyên nhân gốc rễ (Root Causes)](#5-danh-sách-các-bug-đã-khắc-phục-và-nguyên-nhân-gốc-rễ-root-causes)
6. [Thay đổi về Cơ sở dữ liệu, Định tuyến, Kiến trúc và Cấu trúc Code](#6-thay-đổi-về-cơ-sở-dữ-liệu-định-tuyến-kiến-trúc-và-cấu-trúc-code)
7. [Thay đổi về Thư viện phụ thuộc (Dependencies) và Cấu hình](#7-thay-đổi-về-thư-viện-phụ-thuộc-dependencies-và-cấu-hình)
8. [Hoạt động kiểm thử đã thực hiện và kết quả thực tế](#8-hoạt-động-kiểm-thử-đã-thực-hiện-và-kết-quả-thực-tế)
9. [Các giới hạn hiện tại và hạng mục đề xuất chưa triển khai (Known Limitations)](#9-các-giới-hạn-hiện-tại-và-hạng-mục-đề-xuất-chưa-triển-khai-known-limitations)
10. [Lưu ý quan trọng dành cho các thành viên trong nhóm trước khi chạy](#10-lưu-ý-quan-trọng-dành-cho-các-thành-viên-trong-nhóm-trước-khi-chạy)

---

## 1. Tổng quan các thay đổi so với phiên bản gốc (`main`)

Ở phiên bản gốc trên nhánh `main` (commit `2bb3c88`), dự án đang bị chia cắt thành 3 phần rời rạc chưa kết nối với nhau:
1. `HTML/`: Chứa 17 trang giao diện HTML tĩnh được trích xuất từ bản demo gốc nhưng còn vướng 519 liên kết trỏ ra domain ngoài (`https://zaira.themedox.com`), lẫn giao diện quản lý bán hàng E-Commerce không phù hợp với đề tài báo điện tử và thiếu một số hàm JavaScript xử lý sự kiện.
2. `Database/zaira_schema.sql`: Chứa thiết kế SQL gồm 18 bảng dữ liệu quan hệ nhưng chưa được đưa vào hệ thống Migration/Eloquent ORM của Laravel.
3. `Src/NewsWebsite/`: Chỉ mới là bộ khung (skeleton) mặc định của Laravel 13 với 1 route `/` duy nhất trả về view `welcome`, chưa có các Controllers, Models, Middleware hay Views nghiệp vụ.

Trên nhánh `feature/tuan-project-improvements`, dự án đã được hợp nhất và hoàn thiện qua **6 commits** độc lập:

| Thứ tự | Commit Hash | Thông điệp Commit | Tóm tắt phạm vi thay đổi |
| :--- | :--- | :--- | :--- |
| **1** | `73fa95e` | `sec(config): untrack plaintext db password, update .gitignore, .env.example, LICENSE and README` | Gỡ file mật khẩu nhạy cảm khỏi Git index, cập nhật `.gitignore`, `.env.example`, `composer.lock`, thêm `LICENSE` (MIT) và cập nhật `README.md`. |
| **2** | `127a968` | `feat(db): implement 18-table zaira schema migrations, eloquent models and seeder` | Chuyển hóa toàn bộ 18 bảng từ `zaira_schema.sql` thành Laravel Migrations, xây dựng 17 Eloquent Models và `DatabaseSeeder.php`. |
| **3** | `c639766` | `feat(backend): add auth, role middleware, public news, author studio and admin controllers` | Xây dựng `RoleMiddleware`, 5 Controllers (`PublicNewsController`, `AuthController`, `UserPortalController`, `AuthorPortalController`, `AdminPortalController`), `AppServiceProvider` và định tuyến đầy đủ trong `routes/web.php`. |
| **4** | `18729c8` | `feat(ui): build responsive blade views, remove e-commerce clutter and fix js interactions` | Xây dựng 18 file Blade Views, tích hợp bộ CSS/Fonts/JS và 15 ảnh SVG nội bộ vào `Src/NewsWebsite/public/assets/`, đồng thời làm sạch toàn bộ 17 file trong `HTML/`. |
| **5** | `539e51e` | `test(qa): add automated feature tests for public news, rbac and editorial workflows` | Xây dựng bộ kiểm thử tự động `ZairaNewsPortalTest.php` và cập nhật `ExampleTest.php` (7 bài test, 82 assertions). |
| **6** | `12f6a4a` | `docs(guide): add detailed Vietnamese installation and lecturer demo guide (DEMO_GUIDE_TUAN.md)` | Bổ sung tài liệu hướng dẫn cài đặt và kịch bản thuyết trình bằng tiếng Việt `DEMO_GUIDE_TUAN.md`. |

---

## 2. Các tính năng mới đã được triển khai (New Features Implemented)

Dưới đây là các tính năng mới hoàn toàn được lập trình và tích hợp vào `Src/NewsWebsite/`:

### 2.1. Quy trình Biên tập – Kiểm duyệt 2 chiều kèm Ghi chú Phản hồi (Editorial Moderation Workflow)
- **Files liên quan:**
  - `Src/NewsWebsite/app/Http/Controllers/AuthorPortalController.php` (`store()`, `update()`)
  - `Src/NewsWebsite/app/Http/Controllers/AdminPortalController.php` (`moderatePost()`, `bulkAction()`)
  - `Src/NewsWebsite/database/migrations/2026_11_09_000001_create_zaira_news_tables.php` (cột `rejection_reason` trong bảng `posts`)
  - `Src/NewsWebsite/resources/views/portal/author/dashboard.blade.php`, `posts.blade.php`, `create-post.blade.php`
  - `Src/NewsWebsite/resources/views/portal/admin/dashboard.blade.php`, `posts.blade.php`
- **Chi tiết hoạt động:**
  - Khi Tác giả (`role: author`) tạo mới hoặc cập nhật bài viết, dù gửi dữ liệu `status = published`, hệ thống vẫn tự động ép trạng thái về `pending` (Chờ duyệt) hoặc `draft` (Lưu nháp). Chỉ Quản trị viên (`role: admin`) mới có quyền xuất bản trực tiếp.
  - Quản trị viên có thể duyệt xuất bản (`published`, tự động gán `published_at = now()`) hoặc từ chối bài viết (`rejected`) kèm theo **Lý do từ chối (`rejection_reason`)**.
  - Lý do từ chối được hiển thị nổi bật ngay tại trang quản lý bài viết và trang chỉnh sửa của Tác giả để Tác giả hoàn thiện và gửi duyệt lại.
  - Khách vãng lai hoặc độc giả thường không thể xem bài viết chưa xuất bản (trả về lỗi `404`), trong khi tác giả của bài viết hoặc Admin có thể xem trước với thanh cảnh báo **Editorial Preview Mode**.

### 2.2. Hệ thống Phân quyền RBAC & Chặn tài khoản bị khóa (`RoleMiddleware`)
- **Files liên quan:**
  - `Src/NewsWebsite/app/Http/Middleware/RoleMiddleware.php`
  - `Src/NewsWebsite/bootstrap/app.php` (đăng ký middleware alias `role`)
  - `Src/NewsWebsite/app/Models/User.php` (`isAdmin()`, `isAuthor()`, `isUser()`, `dashboardRoute()`)
- **Chi tiết hoạt động:**
  - Kiểm tra chặt chẽ quyền truy cập theo vai trò trên các nhóm route `/author/*` (`role:author,admin`) và `/admin/*` (`role:admin`).
  - Nếu người dùng có quyền thấp hơn cố truy cập đường dẫn của cấp cao hơn, hệ thống tự động điều hướng về đúng Dashboard của vai trò đó kèm thông báo lỗi.
  - Nếu tài khoản bị Admin chuyển sang trạng thái `banned`, middleware và `AuthController` sẽ tự động đăng xuất, hủy session và chặn đăng nhập lại.

### 2.3. Bộ tiện ích hỗ trợ Đọc giả trên trang Chi tiết Bài báo
- **Files liên quan:**
  - `Src/NewsWebsite/resources/views/public/blog-details.blade.php`
  - `Src/NewsWebsite/app/Models/Post.php` (`getEstimatedReadingMinutesAttribute()`)
  - `Src/NewsWebsite/public/assets/js/zaira-lite.js` (`window.toggleArticleSpeech()`, Reading Progress Bar, Auto TOC)
- **Chi tiết hoạt động:**
  - **Thời gian đọc ước tính (Estimated Reading Time):** Tự động đếm số từ của nội dung bài viết (sau khi lọc thẻ HTML) chia cho tốc độ đọc trung bình 200 từ/phút để hiển thị `X min read`.
  - **Mục lục tự động (Auto Table of Contents):** Tự động quét các thẻ `<h2>` và `<h3>` trong phần thân bài báo (`#article-body-content`), gán `id` neo và dựng bảng mục lục nhảy nhanh.
  - **Đọc bài báo bằng giọng nói (Text-to-Speech):** Sử dụng `window.speechSynthesis` (Web Speech API) cho phép độc giả bấm nút **Listen to Article** để nghe trình duyệt đọc nội dung bài viết và bấm lần nữa để dừng.
  - **Thanh tiến trình đọc (Reading Progress Bar):** Hiển thị thanh tiến độ màu cam (`#F4796C`) ở sát mép trên màn hình khi cuộn bài viết.

### 2.4. Nguồn cấp dữ liệu chuẩn RSS 2.0 XML (`/rss.xml`)
- **Files liên quan:**
  - `Src/NewsWebsite/app/Http/Controllers/PublicNewsController.php` (`rssFeed()`)
  - `Src/NewsWebsite/resources/views/public/rss.blade.php`
  - `Src/NewsWebsite/routes/web.php` (`Route::get('/rss.xml', ...)->name('rss.feed')`)
- **Chi tiết hoạt động:** Xuất 20 bài báo đã xuất bản mới nhất dưới định dạng chuẩn `application/rss+xml; charset=UTF-8`.

### 2.5. Bộ chuyển đổi nhanh Vai trò & Điền nhanh tài khoản Demo (1-Click Demo Switcher)
- **Files liên quan:**
  - `Src/NewsWebsite/app/Http/Controllers/AuthController.php` (`demoSwitch()`)
  - `Src/NewsWebsite/public/assets/js/zaira-lite.js` (`window.fillDemoCredentials()`)
  - `Src/NewsWebsite/resources/views/auth/user-login.blade.php`, `portal-login.blade.php`
  - `Src/NewsWebsite/resources/views/layouts/public.blade.php`, `portal.blade.php`
- **Chi tiết hoạt động:** Cho phép điền nhanh thông tin đăng nhập mẫu trên trang Login hoặc chuyển nhanh phiên đăng nhập giữa `Reader`, `Author`, `Admin` thông qua route `/demo-switch/{role}` (chỉ hoạt động khi `app.debug` bật ở môi trường phát triển/demo).

---

## 3. Các tính năng hiện có đã được sửa lỗi và hoàn thiện

Những trang trước đây chỉ tồn tại dưới dạng giao diện tĩnh trong thư mục `HTML/` nay đã được kết nối hoàn chỉnh với cơ sở dữ liệu và xử lý nghiệp vụ thực tế tại `Src/NewsWebsite/`:

1. **Trang chủ (`/`), Trang danh sách & Bộ lọc bài viết (`/blogs`), Trang tác giả (`/author-profile/{username}`):**
   - **Files:** `PublicNewsController.php`, `resources/views/public/index.blade.php`, `blogs.blade.php`, `author.blade.php`.
   - **Nâng cấp:** Truy vấn dữ liệu thực từ bảng `posts`, `categories`, `tags`, `users`, `author_profiles`; hỗ trợ lọc theo chuyên mục (`?category=`), lọc theo thẻ (`?tag=`), tìm kiếm từ khóa (`?search=`) trên tiêu đề/tóm tắt/nội dung và phân trang có giữ tham số lọc (`withQueryString()`).
2. **Bình luận đa cấp (`comments`) & Lưu bài viết yêu thích (`bookmarks`):**
   - **Files:** `PublicNewsController.php` (`storeComment()`, `toggleBookmark()`), `resources/views/public/blog-details.blade.php`.
   - **Nâng cấp:** Lưu bình luận thực vào bảng `comments` (hỗ trợ trả lời bình luận cha qua `parent_id`), thêm/xóa bookmark thực tế trong bảng `bookmarks` và tăng bộ đếm `views_count` mỗi khi mở bài viết.
3. **Đăng ký bản tin (`newsletters`), Gửi liên hệ (`contact_messages`) & Trang tĩnh (`custom_pages`):**
   - **Files:** `PublicNewsController.php` (`subscribeNewsletter()`, `submitContact()`, `about()`, `contact()`, `privacyPolicy()`), `resources/views/public/about.blade.php`, `contact.blade.php`, `privacy-policy.blade.php`.
   - **Nâng cấp:** Lưu email đăng ký nhận tin vào bảng `newsletters`, lưu tin nhắn liên hệ vào bảng `contact_messages` (hiển thị trong trang Admin để đánh dấu đã đọc), và nạp nội dung trang Chính sách bảo mật từ bảng `custom_pages`.
4. **Bảng điều khiển Độc giả (`Reader Hub — /user/dashboard`):**
   - **Files:** `UserPortalController.php`, `resources/views/portal/user/dashboard.blade.php`.
   - **Nâng cấp:** Cho phép độc giả xem và xóa bài viết đã lưu, xem lịch sử bình luận, cập nhật thông tin cá nhân & tải lên ảnh đại diện (`avatar`), đổi mật khẩu (kiểm tra `current_password`), gửi yêu cầu nâng cấp lên quyền Tác giả (`author_profiles`) và tạo phiếu hỗ trợ (`tickets` + `ticket_replies`).
5. **Bảng điều khiển Quản trị viên (`Admin Console — /admin/dashboard` & `/admin/posts`):**
   - **Files:** `AdminPortalController.php`, `resources/views/portal/admin/dashboard.blade.php`, `posts.blade.php`.
   - **Nâng cấp:** Thực hiện CRUD chuyên mục (`storeCategory`, `destroyCategory` — có kiểm tra ngăn xóa chuyên mục đang chứa bài viết), CRUD thẻ tag (`storeTag`, `destroyTag`), kiểm duyệt/xóa bình luận (`moderateComment`, `destroyComment`), cập nhật vai trò & khóa/mở khóa tài khoản người dùng (`updateUserRole`) và thao tác hàng loạt trên danh sách bài viết (`bulkAction`).

---

## 4. Cải tiến giao diện và trải nghiệm người dùng (UI/UX & Visual Design)

1. **Loại bỏ triệt để các thành phần rác E-Commerce (Menu bán hàng & Thẻ doanh thu):**
   - **Files:** `Src/NewsWebsite/resources/views/layouts/portal.blade.php`, `portal/user/dashboard.blade.php`, `portal/author/dashboard.blade.php`, `portal/admin/dashboard.blade.php`, cùng các file đối chiếu `HTML/user/dashboard.html`, `HTML/author/dashboard.html`, `HTML/author/posts.html`, `HTML/author/create-post.html`, `HTML/admin/dashboard.html`, `HTML/admin/posts.html`.
   - **Chi tiết:** Xóa toàn bộ menu `Ecommerce`, `Products`, `Orders`, `Transactions`, `Brands`, `Shipping`, `Delivery Charge`, `Coupon`, `Payment Method` và các thẻ `Total Sale`, `New Order`, `Total Order`, `Total Product`. Thay thế bằng các chỉ số thống kê biên tập báo chí thực tế (Số bài đã đăng, Bài chờ duyệt, Tổng lượt đọc, Tổng bình luận).
2. **Thiết kế bộ 15 ảnh Vector SVG nội bộ sắc nét, hoạt động 100% Offline:**
   - **Files:** `Src/NewsWebsite/public/assets/img/*.svg` và `HTML/assets/img/*.svg` (`logo-dark.svg`, `logo-light.svg`, `avatar-admin.svg`, `avatar-author.svg`, `avatar-author2.svg`, `avatar-user.svg`, `ad-banner.svg`, `default-post.svg`, `post-tech-1.svg`, `post-cloud-2.svg`, `post-security-3.svg`, `post-ai-4.svg`, `post-ux-5.svg`, `post-community-6.svg`, `post-opinion-7.svg`).
   - **Chi tiết:** Đảm bảo toàn bộ logo, ảnh đại diện và ảnh bìa bài báo luôn hiển thị sắc nét ngay cả khi máy thuyết trình không có kết nối Internet.
3. **Đồng bộ chế độ Giao diện Tối / Sáng (Dark Mode / Light Mode):**
   - **Files:** `Src/NewsWebsite/public/assets/js/zaira-lite.js`, `HTML/assets/js/zaira-lite.js`, `Src/NewsWebsite/resources/views/layouts/public.blade.php`.
   - **Chi tiết:** Công tắc chuyển đổi giao diện đồng bộ đồng thời class `.dark-theme` của Zaira và thuộc tính `data-bs-theme="dark"` của Bootstrap 5.3, lưu trạng thái vào `localStorage` để giữ nguyên khi chuyển trang.

---

## 5. Danh sách các Bug đã khắc phục và nguyên nhân gốc rễ (Root Causes)

| Mã lỗi | Mô tả hiện tượng lỗi ở bản gốc (`main`) | Nguyên nhân gốc rễ (Root Cause) | Giải pháp khắc phục & File đã sửa |
| :--- | :--- | :--- | :--- |
| **SEC-01** | File `Cloude/TiDB CLoud/password.txt` chứa mật khẩu DB thật xuất hiện trên Git repository. | Thành viên đẩy nhầm file ghi chú mật khẩu plaintext lên Git và chưa cấu hình `.gitignore`. | Gỡ khỏi Git tracking (`git rm --cached "Cloude/TiDB CLoud/password.txt"`) và thêm quy tắc chặn `**/password.txt`, `*.pem` vào `.gitignore`. |
| **BUG-01** | Mở trang Đăng nhập (`user/login.html`) và Đăng ký (`user/register.html`) luôn bị báo lỗi đỏ *"The email field is required"* dù chưa bấm Submit. | HTML tĩnh được lưu lại từ một phiên trình duyệt đang bị lỗi validation nên dính cứng class `is-invalid` và thẻ `<span class="invalid-feedback">`. | Xóa toàn bộ class `is-invalid` cứng trong `HTML/user/login.html`, `HTML/user/register.html` và sử dụng directive `@error('email')` động trong `user-login.blade.php`, `register.blade.php`. |
| **BUG-02** | Nhấp vào menu, thẻ bài viết, phân trang hoặc bấm nút tìm kiếm bị chuyển hướng sang trang web ngoài `https://zaira.themedox.com/...`. | Có tổng cộng **519 liên kết tuyệt đối** trong 17 file HTML chưa được chuyển về đường dẫn nội bộ. | Thay thế 100% liên kết ngoài bằng Laravel `route(...)` trong các Blade views (`resources/views/**`) và đường dẫn tương đối trong toàn bộ 17 file `HTML/**/*.html`. |
| **BUG-03** | Bấm vào biểu tượng con mắt ẩn/hiện mật khẩu ở trang Login/Register của User bị lỗi Console `Uncaught ReferenceError: togglePassword is not defined`. | Thuộc tính `onclick="togglePassword('password', this)"` gọi hàm toàn cục nhưng hàm này bị thiếu trong `HTML/assets/js/zaira-lite.js`. | Bổ sung hàm `window.togglePassword(inputId, btn)` vào `Src/NewsWebsite/public/assets/js/zaira-lite.js` và `HTML/assets/js/zaira-lite.js`. |
| **BUG-04** | Không thể đóng/mở thanh Sidebar trên màn hình nhỏ hoặc mở rộng menu con trong trang Author/Admin. | Các nút `#sidebar-toggler`, `#sidebar-toggler-2` và `.dropdown-btn` không có sự kiện lắng nghe trong `zaira-lite.js`. | Bổ sung trình điều khiển sự kiện click cho `#sidebar-toggler`, `#sidebar-toggler-2` và `.dropdown-btn` trong `zaira-lite.js`. |
| **BUG-05** | Tại trang Tạo bài viết (`create-post`), bật công tắc *Is Video Blog?* không hiện ô nhập URL Video; chọn ảnh bìa không hiển thị ảnh xem trước. | Thiếu sự kiện `change` cho `#is_video_blog` (để ẩn/hiện `#video_url_section`) và `#cover-image` (để cập nhật `#thumb-preview`). | Bổ sung logic bật/tắt `#video_url_section` và `FileReader` live preview cho `#cover-image` trong `zaira-lite.js` và `create-post.blade.php`. |
| **BUG-06** | Hiển thị chuỗi văn bản lỗi `backend::translate.create_new_blog` trên giao diện Tạo bài viết. | Bản gốc chưa dịch chuỗi khóa đa ngôn ngữ của module backend. | Thay thế toàn bộ chuỗi `backend::translate.*` bằng tiêu đề chuẩn *"Create & Edit Article — Author Studio"* trong `create-post.blade.php` và `HTML/author/create-post.html`. |

---

## 6. Thay đổi về Cơ sở dữ liệu, Định tuyến, Kiến trúc và Cấu trúc Code

### 6.1. Thay đổi về Cơ sở dữ liệu (`Database/` & `Src/NewsWebsite/database/`)
- **Cập nhật Schema SQL gốc (`Database/zaira_schema.sql`):**
  - Bổ sung cột `rejection_reason TEXT NULL` vào bảng `posts` để lưu ghi chú biên tập khi Admin từ chối bài viết.
  - Bổ sung chỉ mục tổ hợp `INDEX idx_posts_feed (status, language_id, published_at)` trên bảng `posts` để tối ưu hiệu năng truy vấn danh sách bài báo ngoài trang chủ.
- **Laravel Migrations (`Src/NewsWebsite/database/migrations/`):**
  - `0001_01_01_000000_create_users_table.php`: Tạo bảng `roles` và cập nhật bảng `users` với đầy đủ các trường `role_id`, `username`, `phone`, `avatar`, `bio`, `status`.
  - `2026_11_09_000001_create_zaira_news_tables.php`: Tạo 16 bảng nghiệp vụ còn lại (`author_profiles`, `languages`, `categories`, `tags`, `posts`, `post_tag`, `media`, `comments`, `bookmarks`, `newsletters`, `contact_messages`, `tickets`, `ticket_replies`, `custom_pages`, `advertisements`, `settings`).
- **Hệ thống 17 Eloquent Models (`Src/NewsWebsite/app/Models/`):**
  - `Role.php`, `User.php`, `AuthorProfile.php`, `Language.php`, `Category.php`, `Tag.php`, `Post.php`, `Comment.php`, `Bookmark.php`, `Newsletter.php`, `ContactMessage.php`, `Ticket.php`, `TicketReply.php`, `Media.php`, `CustomPage.php`, `Advertisement.php`, `Setting.php`.
- **Database Seeder & Factory:**
  - `Src/NewsWebsite/database/seeders/DatabaseSeeder.php` và `Src/NewsWebsite/database/factories/UserFactory.php`.

### 6.2. Thay đổi về Kiến trúc Backend & Định tuyến (`Src/NewsWebsite/app/` & `routes/web.php`)
- **5 Controllers chuyên biệt theo từng phân hệ (`Src/NewsWebsite/app/Http/Controllers/`):**
  - `PublicNewsController.php`: Xử lý trang chủ, danh sách bài báo, chi tiết bài báo, trang tác giả, trang giới thiệu/liên hệ/chính sách bảo mật, bình luận, bookmark, đăng ký bản tin, chuyển ngôn ngữ và RSS feed.
  - `AuthController.php`: Xử lý đăng nhập cho Reader/Author/Admin, đăng ký tài khoản tự động sinh `username`, đăng xuất và chuyển nhanh tài khoản demo.
  - `UserPortalController.php`: Xử lý Dashboard độc giả, cập nhật hồ sơ, đổi mật khẩu, gửi yêu cầu nâng quyền Author và tạo Ticket hỗ trợ.
  - `AuthorPortalController.php`: Xử lý Dashboard thống kê của Tác giả, danh sách bài viết cá nhân, thêm/sửa/xóa bài viết và tải lên ảnh bìa.
  - `AdminPortalController.php`: Xử lý Dashboard Tổng biên tập, duyệt/từ chối bài viết, thao tác hàng loạt (Bulk Actions), quản lý Chuyên mục, Thẻ, Bình luận, Người dùng và Tin nhắn liên hệ.
- **Chia sẻ dữ liệu toàn cục cho Views (`Src/NewsWebsite/app/Providers/AppServiceProvider.php`):**
  - Sử dụng `View::composer('*', ...)` (kèm kiểm tra `Schema::hasTable` an toàn khi chạy CLI) để chia sẻ danh sách chuyên mục trên thanh menu (`sharedCategories`), ngôn ngữ (`sharedLanguages`), cấu hình hệ thống (`sharedSettings`) và bài viết thịnh hành (`sharedTrendingPosts`).

---

## 7. Thay đổi về Thư viện phụ thuộc (Dependencies) và Cấu hình

1. **Đồng bộ `Src/NewsWebsite/composer.lock` tương thích với PHP 8.3 & 8.4:**
   - Bản gốc trên nhánh `main` khóa cứng một số gói `symfony/*` ở phiên bản `v8.1.x` (yêu cầu bắt buộc `PHP >= 8.4.1`), gây lỗi không thể chạy `composer install` trên các máy cài đặt PHP `8.3.x` phổ biến (như Laragon/XAMPP).
   - Đã cập nhật `Src/NewsWebsite/composer.lock` hạ các gói `symfony/*` xuống nhánh `v7.4.x` ổn định, giúp dự án chạy mượt mà trên cả **PHP 8.3** và **PHP 8.4** mà không cần thay đổi bất kỳ dòng code nào của Laravel 13.
2. **Cập nhật `Src/NewsWebsite/.env.example`:**
   - Thiết lập mặc định `DB_CONNECTION=sqlite` để chạy ngay lập tức ở môi trường local, đồng thời giữ sẵn khối cấu hình mẫu kết nối `TiDB Cloud (NewsDB)` có chú thích rõ ràng để nhóm chuyển đổi khi cần.
3. **Bổ sung giấy phép mã nguồn mở `LICENSE`:**
   - Thêm file `LICENSE` chuẩn **MIT License** ở thư mục gốc.

---

## 8. Hoạt động kiểm thử đã thực hiện và kết quả thực tế

Toàn bộ chức năng trên nhánh `feature/tuan-project-improvements` đã được kiểm thử thực tế bằng bộ kiểm thử tự động PHPUnit (`Src/NewsWebsite/tests/Feature/ZairaNewsPortalTest.php` và `ExampleTest.php`):

- **Lệnh kiểm thử:**
  ```powershell
  cd Src/NewsWebsite
  php artisan test
  ```
- **Kết quả thực tế:** **7/7 bài kiểm thử PASSED (82 assertions, 0 failures, thời gian chạy ~1.34 giây)**:
  1. `Tests\Unit\ExampleTest::test_that_true_is_true` — **PASSED**
  2. `Tests\Feature\ExampleTest::test_the_application_returns_a_successful_response` — **PASSED**
  3. `Tests\Feature\ZairaNewsPortalTest::test_public_homepage_blog_archive_search_and_detail_pages_work` — **PASSED**: Xác minh trang chủ `/`, lọc chuyên mục `/blogs?category=technology`, tìm kiếm từ khóa `/blogs?search=...`, xem chi tiết bài báo tăng `views_count`, trang hồ sơ tác giả `/author-profile/sarah-jenkins`, trang `/about`, `/contact`, `/privacy-policy`, nguồn cấp `/rss.xml` và xác nhận không còn bất kỳ chuỗi `zaira.themedox.com` nào.
  4. `Tests\Feature\ZairaNewsPortalTest::test_authentication_registration_and_role_based_access_control` — **PASSED**: Xác minh đăng ký tài khoản mới tự sinh `username` (`tran-quoc-tuan`), kiểm tra `RoleMiddleware` chặn Reader truy cập `/author/dashboard` và `/admin/dashboard`, chặn Author truy cập `/admin/dashboard`, và xác nhận giao diện Portal đã loại bỏ `Total Sale` cùng `backend::translate`.
  5. `Tests\Feature\ZairaNewsPortalTest::test_author_post_submission_and_admin_editorial_moderation_workflow` — **PASSED**: Xác minh khi Author gửi bài viết mới với `status = published`, hệ thống tự động đưa về `pending`; khách vãng lai truy cập bài `pending` nhận mã `404`; Admin từ chối bài kèm `rejection_reason`; sau đó Admin duyệt `published` thì bài viết có `published_at` và hiển thị công khai (`200 OK`).
  6. `Tests\Feature\ZairaNewsPortalTest::test_reader_comments_bookmarks_contact_and_newsletter_workflows` — **PASSED**: Xác minh độc giả gửi bình luận, lưu bài viết (Bookmark), gửi form liên hệ và đăng ký nhận bản tin (Newsletter).
  7. `Tests\Feature\ZairaNewsPortalTest::test_admin_category_tag_comment_and_user_management_workflows` — **PASSED**: Xác minh Admin tạo chuyên mục mới, tạo thẻ Tag mới, khóa tài khoản người dùng (`status = banned`), xác nhận người dùng bị khóa không thể đăng nhập, sau đó kích hoạt lại và nâng quyền lên Author (`role_id = 2`).

---

## 9. Các giới hạn hiện tại và hạng mục đề xuất chưa triển khai (Known Limitations)

Để đảm bảo tính trung thực kỹ thuật, dưới đây là sự phân biệt rõ ràng giữa những gì **đã triển khai** và những ý tưởng mở rộng **chưa triển khai** (hoặc giữ ở mức tối giản phù hợp phạm vi môn học):

1. **Trình soạn thảo nội dung bài viết (Rich-Text Editor):**
   - **Hiện trạng:** Trang tạo/chỉnh sửa bài viết (`create-post.blade.php`) hiện sử dụng vùng nhập `<textarea>` hỗ trợ mã HTML chuẩn (`<h2>`, `<h3>`, `<p>`, `<blockquote>`, `<ul>`) thay vì nhúng trình soạn thảo WYSIWYG bên thứ ba qua CDN (như TinyMCE hay CKEditor).
   - **Lý do:** TinyMCE yêu cầu API Key hoặc kết nối mạng ngoài; việc dùng `<textarea>` HTML thuần giúp ứng dụng hoạt động 100% offline không bị khóa bởi cảnh báo domain/API key khi demo trên lớp.
2. **Đa ngôn ngữ nội dung (Multi-language Content Filtering):**
   - **Hiện trạng:** Hệ thống đã có bảng `languages` (`en`, `ar`, `vi`) và route chuyển đổi ngôn ngữ lưu vào session (`/language/switch/{code}`), đồng thời hỗ trợ chọn ngôn ngữ khi tạo bài viết mới. Tuy nhiên, bộ dữ liệu mẫu (`DatabaseSeeder`) hiện đang nạp 9 bài báo bằng tiếng Anh (`en`) để đồng bộ với nội dung giao diện mẫu Zaira; giao diện tĩnh chưa tách hết toàn bộ nhãn sang file `lang/vi.json`.
3. **Gửi Email thực tế qua SMTP (Password Reset & Newsletter Broadcast):**
   - **Hiện trạng:** Chức năng đăng ký nhận bản tin (`newsletters`) và liên hệ (`contact_messages`) hiện lưu trữ đầy đủ vào cơ sở dữ liệu; cấu hình `MAIL_MAILER` trong `.env.example` đang để ở chế độ `log` (ghi vào `storage/logs/laravel.log`) chứ chưa kết nối máy chủ SMTP ngoài.
4. **Hệ thống Ticket Hỗ trợ (`tickets` & `ticket_replies`):**
   - **Hiện trạng:** Độc giả đã có thể tạo phiếu hỗ trợ mới và xem danh sách phiếu hỗ trợ của mình tại `/user/dashboard`; bảng `tickets` và `ticket_replies` đã có đầy đủ Migration và Eloquent Model, nhưng chưa tách thành một màn hình chat trả lời ticket riêng biệt trong Admin Console.

---

## 10. Lưu ý quan trọng dành cho các thành viên trong nhóm trước khi chạy

Trước khi chạy phiên bản nâng cấp trên nhánh `feature/tuan-project-improvements`, các thành viên trong nhóm cần lưu ý **5 điểm quan trọng** sau:

1. **Đứng đúng thư mục `Src/NewsWebsite` khi chạy lệnh:**
   - Mọi lệnh `composer`, `php artisan migrate:fresh --seed`, `php artisan serve` và `php artisan test` đều phải được thực thi bên trong thư mục `Src/NewsWebsite/` (không phải ở thư mục gốc của repository).
2. **Không commit file `.env` hoặc mật khẩu TiDB Cloud lên Git:**
   - File `Cloude/TiDB CLoud/password.txt` đã được gỡ khỏi Git tracking để bảo mật. Khi cần lấy mật khẩu kết nối TiDB Cloud, hãy hỏi trực tiếp trong nhóm nội bộ và chỉ điền vào file `Src/NewsWebsite/.env` trên máy cá nhân (file `.env` đã được `.gitignore` bảo vệ).
3. **Ưu tiên dùng SQLite mặc định khi chạy thử hoặc thuyết trình:**
   - Chỉ cần sao chép `.env.example` sang `.env`, tạo file rỗng `Src/NewsWebsite/database/database.sqlite` và chạy `php artisan migrate:fresh --seed` là có sẵn đầy đủ 4 tài khoản và 9 bài báo mẫu mà không cần cài đặt hay cấu hình MySQL/TiDB Cloud.
4. **Chạy `php artisan storage:link` một lần sau khi clone:**
   - Để các file ảnh bìa bài báo hoặc ảnh đại diện mới tải lên hiển thị đúng ngoài giao diện.
5. **Không gộp (merge) trực tiếp vào `main` khi chưa thống nhất:**
   - Nhánh `feature/tuan-project-improvements` được giữ độc lập hoàn toàn để các thành viên dễ dàng kiểm tra, chạy thử và đối chiếu trước khi nhóm quyết định tạo Pull Request hợp nhất vào `main`.
