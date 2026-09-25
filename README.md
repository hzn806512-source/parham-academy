# 🎓 Parham Academy - Learning Management System (LMS)

> A high-performance, modular PHP MVC e-learning web platform engineered with secure video streaming tokens, granular course access management, and activity logging.

---

## 🌐 Languages / زبان‌ها
- [English](#-english)
- [فارسی](#-فارسی)

---

## 🇺🇸 English

### 📌 Overview
**Parham Academy** is an online education platform developed with custom PHP MVC architecture. It provides instructors and students with a secure environment for course distribution, lesson playback, and administrative controls.

### 🛡️ Architecture & Security Highlights
- **Custom MVC Engine:** Built from scratch with dedicated `Router`, `Controllers`, and `Session` managers.
- **Secure Video Streaming (`Videotoken`):** Dynamic time-based token generation to prevent unauthorized lesson downloads.
- **Brute-Force Protection:** Rate-limiting authentication with `login_attempts` tracking.
- **Comprehensive Audit Logging:** Real-time user action tracking via `activity_log`.
- **Role-Based Access Control (RBAC):** Granular course enrollment views via `v_active_access` and `course_access`.

### ✨ Key Features
- **Course & Lesson Management:** Create, update, and organize multi-chapter courses and video lessons.
- **Admin Control Panel:** Dedicated dashboards for managing users, courses, lesson files, and permissions.
- **User Dashboard:** Clean learner portal showing enrolled courses, active progress, and guides.

### 🚀 Getting Started Locally

1. **Clone the repository:**
   ```bash
   git clone https://github.com/hzn806512-source/parham-academy.git
   ```

2. **Database Setup:**
   - Create a MySQL database in phpMyAdmin.
   - Import the database schema from `/database/`.

3. **Configuration:**
   - Update database credentials in your configuration file.

4. **Launch:**
   - Run via XAMPP / Apache and navigate to the project root in your browser.

---

## 🇮🇷 فارسی

<div dir="rtl">

### 📌 درباره پروژه
**آکادمی پرهام (Parham Academy)** یک سامانه جامع مدیریت آموزش آنلاین (LMS) است که با استفاده از **معماری اختصاصی MVC در زبان PHP** و پایگاه‌داده MySQL طراحی و توسعه داده شده است.

### 🛡️ مزیت‌های فنی و امنیتی پروژه
- **معماری ماژولار MVC:** تفکیک کامل لایه‌های کنترلر، مدل و نما با روتینگ اختصاصی (`Router.php`).
- **استریم امن ویدیو (Videotoken):** استفاده از توکن‌های اعتبارسنجی پویا جهت جلوگیری از دانلود غیرمجاز ویدیوهای آموزشی.
- **امنیت و لاگینگ پیشرفته:** ثبت تلاش‌های ناموفق ورود جهت جلوگیری از حملات Brute-force و ثبت کلیه وقایع در `activity_log`.
- **مدیریت سطح دسترسی:** سیستم اعطای دسترسی زمان‌دار و مشروط به دوره‌ها با جدول‌های اختصاصی دسترسی.

### ✨ قابلیت‌های اصلی سامانه
- **پنل مدیریت یکپارچه:** مدیریت آسان دوره‌ها، درس‌ها، دسترسی دانشجویان و تنظیمات کلی سایت.
- **پنل کاربری دانشجویان:** دسترسی سریع به دوره‌های ثبت‌نام‌شده، مشاهده محتوا و راهنماها.
- **کدنویسی تمیز و بهینه‌سازی‌شده:** استفاده از سشن‌های امن و ساختار دیتابیس نرمال‌سازی شده.

### 🚀 راهنمای راه‌اندازی لوکال

۱. **کلون کردن مخزن:**
```bash
git clone https://github.com/hzn806512-source/parham-academy.git
```

۲. **راه‌اندازی پایگاه داده:**
- یک دیتابیس MySQL در phpMyAdmin بسازید.
- فایل دیتابیس موجود در پوشه `database/` را در آن ایمپورت کنید.

۳. **تنظیمات اتصال:**
- مشخصات دیتابیس را در فایل کانفیگ پروژه بررسی و تنظیم کنید.

۴. **اجرا:**
- پروژه را در مسیر زمپ (XAMPP) باز کرده و در مرورگر اجرا کنید.

</div>