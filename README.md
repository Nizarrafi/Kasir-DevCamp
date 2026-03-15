# Kasir DevCamp - POS System

[![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js)](https://vuejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-0.x-9553E9?style=for-the-badge&logo=inertia)](https://inertiajs.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)

A professional Point of Sale (POS) system built with **Laravel 9**, **Inertia.js**, and **Vue 3**. This project demonstrates a clean architecture implementing the **Service Pattern** and **Action Pattern** to ensure scalability and maintainability.

---

## 🚀 Key Features

- **🛍️ POS Terminal**: Interactive cart system with real-time calculations.
- **📊 Interactive Dashboard**: Visualized sales statistics and recent activities.
- **📦 Inventory Management**: Manage products, categories, and stock tracking.
- **🔐 Advanced RBAC**: Robust Role-Based Access Control using Spatie Permissions.
- **📄 Report System**: Export sales reports to Excel and PDF.
- **⚙️ Store Settings**: Dynamic configuration for receipt headers, store info, and system preferences.
- **👤 Profile Management**: Secure user profile and password updates.

## 🏗️ Architectural Highlights

- **Service Pattern**: Decoupling business logic from controllers for better testability.
- **Action Pattern**: Encapsulating discrete business tasks into reusable classes.
- **Inertia.js Stack**: Seamless SPA experience without the complexity of a separate API layer.
- **Responsive Design**: Modern UI built with Tailwind CSS and Headless UI.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 9 (PHP 8.x)
- **Frontend**: Vue 3 (Composition API), Inertia.js
- **Styling**: Tailwind CSS, FontAwesome
- **Packages**:
  - `spatie/laravel-permission` (RBAC)
  - `maatwebsite/excel` (Excel Exports)
  - `barryvdh/laravel-dompdf` (PDF Generation)
  - `tightenco/ziggy` (Route integration)

---

## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/Kasir_DevCamp.git
   cd Kasir_DevCamp
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   - Create a database and update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`.

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Compile Assets**
   ```bash
   npm run dev
   ```

7. **Start Server**
   ```bash
   php artisan serve
   ```

## 📄 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
