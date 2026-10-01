<div align="center">

# 🎓 Parham Academy - Learning Management System (LMS)

**Enterprise-ready, modular PHP MVC e-learning web platform with secure tokenized video streaming, RBAC course access control, and audit logging.**

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](#)
[![MySQL Database](https://img.shields.io/badge/MySQL-5.7%2B%20%7C%20MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](#)
[![Architecture](https://img.shields.io/badge/Architecture-Custom%20MVC-0284c7?style=for-the-badge)](#)
[![License: MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](#)
[![Security Status](https://img.shields.io/badge/Security-HMAC%20Tokenized%20Stream-emerald?style=for-the-badge)](#)

</div>

---

## 🌐 Languages / زبان‌ها
- [🇺🇸 English Version](#-english-documentation)
- [🇮🇷 نسخه فارسی](#-نسخه-فارسی)

---

## 🇺🇸 English Documentation

### 🌐 Live Demo & Quick Access
- **Storefront / Catalog:** `http://localhost/parham-academy/public`
- **Student Dashboard:** `http://localhost/parham-academy/public/panel/dashboard`
- **Admin Control Panel:** `http://localhost/parham-academy/public/admin/dashboard`

> **Demo Credentials:**
> - **Administrator:** Username: `admin` | Password: `password123`
> - **Student Demo:** Username: `student` | Password: `password123`

### 📸 System Showcase & Screenshots

| 🏠 Course Catalog & Homepage | 🎥 Token-Protected Video Stream |
|:---:|:---:|
| ![Homepage](docs/screenshots/01_homepage.svg) | ![Video Player](docs/screenshots/02_video_player.svg) |
| **Learner Dashboard** | **Administration Center** |
| ![Student Dashboard](docs/screenshots/03_student_dashboard.svg) | ![Admin Panel](docs/screenshots/04_admin_management.svg) |

### ⚡ Core Technical Features
- **Custom PHP MVC Framework:** Complete separation of concerns with an extensible HTTP router, controller layer, and encapsulated PDO database wrapper.
- **Token-Protected Video Streaming (`Videotoken`):** Lessons are guarded against direct URL grabbing via time-expiring cryptographic HMAC tokens.
- **Role-Based Access Control (RBAC):** Distinct permission layers separating Guests, Enrolled Students, and Administrators via normalized SQL tables.
- **Brute-Force & Rate-Limit Protection:** Real-time throttling via `login_attempts` tracking to neutralize automated attacks.
- **Audit Logging:** Administrative actions, logins, and permission changes are persisted in `activity_log`.

### 🛠️ Tech Stack
| Domain | Technology / Specification |
|:---|:---|
| **Backend Language** | PHP 8.0+ (Strict typing, Object-Oriented Programming, MVC) |
| **Database** | MySQL / MariaDB (Prepared Statements, Foreign Keys, SQL Views) |
| **Security Layer** | Dynamic HMAC Tokenizer, Password Hashing (`PASSWORD_BCRYPT`), Session Guard |
| **Frontend** | Semantic HTML5, Modern CSS3 (Flexbox/Grid), Vanilla JavaScript (ES6+) |
| **Web Server** | Apache (URL Rewriting via `.htaccess` Front-Controller) |

### 🚀 Installation & Local Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/hzn806512-source/parham-academy.git
   cd parham-academy
   ```
2. Import database schema into MySQL:
   ```bash
   mysql -u root -p parham_academy < database/parham_academy.sql
   ```
3. Configure database settings in `app/config.php`.
4. Run via XAMPP and open `http://localhost/parham-academy/public`.

---

## 🇮🇷 نسخه فارسی

<div dir="rtl">

### 📌 درباره پروژه
**آکادمی پرهام (Parham Academy)** یک وب‌اپلیکیشن جامع و سازمانی برای مدیریت آموزش آنلاین (LMS) است که با استفاده از **معماری اختصاصی MVC در زبان PHP** و پایگاه‌داده MySQL طراحی و توسعه داده شده است.

### 🌐 مشخصات دمو و حساب‌های تستی
- **صفحه اصلی و کاتالوگ دوره‌ها:** `http://localhost/parham-academy/public`
- **داشبورد دانشجو:** `http://localhost/parham-academy/public/panel/dashboard`
- **پنل مدیریت ادمین:** `http://localhost/parham-academy/public/admin/dashboard`

> **اطلاعات حساب‌های تستی:**
> - **مدیر کل (Admin):** نام کاربری: `admin` | کلمه عبور: `password123`
> - **دانشجو (Student):** نام کاربری: `student` | کلمه عبور: `password123`

### 📸 پیش‌نمایش بخش‌های سامانه

| 🏠 کاتالوگ دوره‌ها و صفحه اصلی | 🎥 استریم امن ویدیو (توکن پویا) |
|:---:|:---:|
| ![صفحه اصلی](docs/screenshots/01_homepage.svg) | ![پلیر ویدیو](docs/screenshots/02_video_player.svg) |
| **داشبورد یادگیری دانشجو** | **پنل مدیریت دوره‌ها و کاربران** |
| ![داشبورد دانشجو](docs/screenshots/03_student_dashboard.svg) | ![پنل مدیریت](docs/screenshots/04_admin_management.svg) |

### 🛡️ مزیت‌های فنی و امنیتی
- **معماری ماژولار MVC بدون وابستگی:** طراحی هسته اختصاصی با روتینگ منعطف (`Router.php`)، کنترلرهای مجزا و کپسوله‌سازی کامل لایه دیتابیس با PDO.
- **استریم امن ویدیو با توکن پویا (`Videotoken`):** محافظت از ویدیوها در برابر دانلود مستقیم یا لیچ شدن با توکن‌های رمزنگاری شده HMAC زمان‌دار.
- **سیستم کنترل سطح دسترسی (RBAC):** تفکیک دقیق دسترسی مهمان، دانشجو و مدیر بر اساس جدول‌های پایگاه داده (`course_access`, `v_active_access`).
- **سیستم ضد Brute-force:** محدودسازی خودکار تلاش‌های ناموفق ورود با جدول `login_attempts`.
- **ثبت لاگ وقایع (`activity_log`):** رهگیری دقیق فعالیت‌های حساس جهت بررسی‌های امنیتی.

### 🚀 راهنمای راه‌اندازی لوکال
۱. کلون کردن مخزن پروژه:
```bash
git clone https://github.com/hzn806512-source/parham-academy.git
```
۲. ایجاد پایگاه داده به نام `parham_academy` در phpMyAdmin و ایمپورت فایل `database/parham_academy.sql`.
۳. تنظیم مشخصات اتصال در `app/config.php`.
۴. باز کردن آدرس `http://localhost/parham-academy/public` در مرورگر.

</div>