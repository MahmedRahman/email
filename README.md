# مساعد البريد الشخصي — المرحلة 1

لوحة تحكم Laravel لإدارة فلاتر البريد وربطها بسير عمل n8n. هذه المرحلة تركز على الواجهة (عربي + RTL) مع مصادقة جلسة بسيطة وبيانات تجريبية.

## المتطلبات

- PHP 8.2+
- Composer
- Node.js 18+

## التشغيل

```bash
composer install
cp .env.example .env   # إن لم يكن .env موجوداً
php artisan key:generate
php artisan migrate
npm install
npm run dev            # في طرفية منفصلة
php artisan serve      # http://localhost:8000
```

## تسجيل الدخول التجريبي

| الحقل | القيمة |
|-------|--------|
| البريد | `admin@example.com` |
| كلمة المرور | `password` |

يمكن تغييرها عبر `.env`:

```env
DEMO_USER_EMAIL=admin@example.com
DEMO_USER_PASSWORD=password
DEMO_USER_NAME=محمد
```

## المسارات

| Method | URI | الوصف |
|--------|-----|--------|
| GET | `/` | إعادة توجيه حسب حالة الجلسة |
| GET | `/login` | صفحة تسجيل الدخول |
| POST | `/login` | معالجة الدخول |
| GET | `/dashboard` | لوحة التحكم (محمية) |
| POST | `/logout` | تسجيل الخروج |

## هيكل الملفات الرئيسية

```
app/Http/Controllers/Auth/LoginController.php
app/Http/Controllers/DashboardController.php
app/Http/Middleware/EnsureAuthenticated.php
app/Http/Middleware/RedirectIfAuthenticated.php
app/Services/Dashboard/DashboardDataService.php
config/demo.php
resources/views/auth/login.blade.php
resources/views/dashboard/index.blade.php
resources/views/layouts/{guest,app}.blade.php
resources/views/partials/{sidebar,navbar,sidebar-icon}.blade.php
resources/views/components/{stat-card,status-badge}.blade.php
routes/web.php
```

## المراحل القادمة

- صفحات فلاتر البريد وسير العمل والسجلات
- ربط n8n API
- مصادقة كاملة مع قاعدة بيانات
