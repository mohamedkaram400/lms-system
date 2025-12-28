# Mini LMS – Laravel + Livewire + Filament

A realistic **Mini Learning Management System (LMS)** built with modern Laravel tooling.  
This project focuses on **clean architecture, real-world business flows, testing, and admin management**.

It is designed as a portfolio project that demonstrates how a production-ready LMS could be structured.

---

## 🧱 Tech Stack

- Laravel 11 / 12
- Livewire v3
- Alpine.js
- Tailwind CSS
- Filament v3 (Admin Panel)
- Pest (Testing)
- Plyr.js (Video Player)

---

## 🎯 Project Goal

Build a **realistic mini-LMS** that covers:
- Public course browsing
- Authenticated enrollment
- Video-based lessons
- Progress tracking & course completion
- Admin management
- Clean business logic using the **Actions pattern**
- Reliable async email handling
- Strong test coverage

---

## ✨ Core Features

### 1️⃣ Public & Auth
- Home page lists **published courses** (image, title, level)
- Guests can browse courses
- Enrollment requires authentication
- Registration sends a **Welcome Email** (async)
- Handles concurrent actions safely (no duplicate emails or enrollments)

---

### 2️⃣ Courses
- Course page is the **main entry point**
- Shows:
  - Image, title, level, description
  - Enroll / Continue button
  - Ordered lessons list
- **Free preview lessons** are visible to guests
- Course slugs are unique and consistent (even after soft deletes)

---

### 3️⃣ Lessons & Video Player
- Lessons include:
  - Title, order, video URL
  - Optional duration
  - Free preview flag
- Lesson page uses **Plyr.js**
- Next / Previous navigation
- Mark lesson as completed

---

### 4️⃣ Progress & Completion
- Tracks:
  - started_at
  - completed_at
  - watch_seconds
- Course completion is detected automatically
- On completion:
  - Create `course_completions` record
  - Send **Course Completion Email** (once per user)
- Progress remains accurate even if lessons change
- All emails are dispatched **asynchronously**

---

### 5️⃣ Admin Panel (Filament v3)
- Levels CRUD
- Courses CRUD with Lessons relation (reorderable)
- Users listing (read-only)
- Enrollments & Progress views:
  - Course → enrolled users + completion %
  - User → enrolled courses + completion %
- Simple dashboard widget:
  - Total courses
  - Total enrollments
  - Average completion rate

---

## ⚡ Alpine.js Interactions

Implemented **at least 3 interactive UI features** using Alpine.js:
- Collapsible lessons list (accordion)
- Confirmation modal before completing a lesson
- Animated progress bar on completion
- Plyr integration using Alpine lifecycle hooks (`x-data`, `x-init`, `x-ref`)
- *(Bonus)* Global dark mode toggle

---

## 🛠 Architecture

- **Actions Pattern** for all core flows
- Each Action:
  - Has a single responsibility
  - Uses database transactions
  - Handles concurrency safely
- Policies enforce strict user data isolation
- Admin access restricted to admins only

---

## 🧪 Testing (Pest)

Includes tests for:
- Levels
- Courses with Lessons relation manager
- Users (read-only)
- Enrollments & Progress views

Additional tests:
- Database constraints (e.g. unique slugs)
- Transactional consistency between progress & completion
- Ensuring emails are sent **only once**

---

## 🚀 Setup

```bash
git clone git@github.com:mohamedkaram400/lms-system.git
cd mini-lms
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## 👤 Author

### Mohamed Karam
Backend Developer — Laravel & Modern PHP

## 📄 License

This project is open-source and licensed under the MIT License.
