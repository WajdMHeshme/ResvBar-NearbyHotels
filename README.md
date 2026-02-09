<p align="center">
    <img src="assets/images/logo.png" alt="ResvBAR Logo" width="150">
</p>

# ResvBAR — Authentication + Nearby Hotels Map

هذا المشروع هو تطبيق ويب بسيط يتيح للمستخدمين:

- التسجيل وتسجيل الدخول
- عرض خريطة تفاعلية للمستخدم
- معرفة الفنادق القريبة من موقع المستخدم الحالي باستخدام **Leaflet + OpenStreetMap**

---

## 🛠 الأدوات والتقنيات المستخدمة

- **Backend:** Laravel 12.50.0 + PHP 8.2
- **Frontend:** HTML, CSS, JavaScript
- **CSS Framework:** Tailwind CSS
- **Map Library:** Leaflet
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **APIs:** OpenStreetMap / Overpass API (للفنادق)
- **Optional:** Alpine.js لبعض المكونات التفاعلية (مثلاً Dropdown)

---

## ⚡ متطلبات النظام

- PHP >= 8.1
- Composer
- MySQL
- Node.js + npm (لتشغيل Tailwind CSS)

---

## 📦 خطوات الإعداد

1. **نسخ المشروع وتثبيت الـ Composer dependencies:**

```bash
git clone <repo-url>
cd auth-map-task
composer install
