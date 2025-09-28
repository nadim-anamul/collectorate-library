# Collectorate Library Management System
*A modern, bilingual library management system built with Laravel 11 & Tailwind CSS*

[![Production Ready](https://img.shields.io/badge/Production-Ready-green.svg)](https://github.com/your-repo)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-blue.svg)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)](https://php.net)

## 🚀 Features

### ✅ Production-Ready Features
- **Professional Home Page** - Rokomari-inspired design with advanced search & filters
- **Role-Based Access Control** - Admin, Librarian, Member roles via Spatie Permission
- **Complete CRUD Operations** for:
  - 📚 Books (with cover images, PDFs, multilingual support)
  - 👥 Authors (Bengali & English names, bios)
  - 🏢 Publishers (with websites)
  - 🌐 Languages (15+ languages included)
  - 📂 Categories & Tags
  - 👤 Members (with printable ID cards)
  - 📋 Loans (issue/return tracking)
- **Modern Admin Dashboard** - Stats cards, filters, book grid with real-time updates
- **Bilingual Support** - Bengali, English, Banglish transliteration
- **Advanced Search & Filtering** - Search by title, author, ISBN, category with autocomplete
- **Dark/Light Theme** - Toggle with localStorage persistence
- **Activity Logging** - Track all system activities with detailed audit trails
- **Responsive Design** - Optimized for desktop, tablet, mobile with progressive enhancement
- **Performance Optimized** - Cached queries, optimized assets, lazy loading
- **Security Hardened** - CSRF protection, input validation, secure file uploads

## 🛠 Tech Stack
- **Backend:** Laravel 11, PHP 8.2+, MySQL 8.0+
- **Frontend:** Tailwind CSS 3.x, Alpine.js 3.x, Vite 6.x
- **Authentication:** Laravel Breeze with Sanctum
- **Permissions:** Spatie Laravel Permission
- **File Storage:** Laravel Storage with optimized image handling
- **Caching:** Redis for sessions and cache
- **Build Tools:** Vite with production optimizations

## 📋 Requirements
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+ & NPM
- Composer
- Redis (recommended for production)

## 🚀 Production Deployment

### Quick Production Setup
```bash
# 1. Install dependencies
composer install --optimize-autoloader --no-dev
npm ci

# 2. Build production assets
npm run build:production

# 3. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Set permissions
chmod -R 755 storage bootstrap/cache
```

### Performance Optimizations
- **Asset Optimization**: Vite with Terser minification
- **CSS Optimization**: Tailwind CSS with purged unused styles
- **JavaScript Optimization**: Alpine.js with tree shaking
- **Database Optimization**: Query caching and optimized indexes
- **Caching Strategy**: Redis for sessions, views, and config
- **Image Optimization**: Responsive images with lazy loading

### Security Features
- **CSRF Protection**: Built-in Laravel CSRF tokens
- **Input Validation**: Comprehensive form validation
- **File Upload Security**: Type validation and size limits
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Blade template escaping
- **Secure Headers**: Content Security Policy and security headers

For detailed deployment instructions, see [DEPLOYMENT.md](DEPLOYMENT.md).

## ⚡ Quick Setup

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Configuration
Configure your database in `.env`, then run:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 5. Start Development Server
```bash
php artisan serve --port=8080
```

Visit: http://localhost:8080

## 🔐 Default Login
- **Admin:** `admin@library.com` / `password`
Library Admin: admin@library.com / password
Head Librarian: librarian@library.com / password
আহমেদ হাসান (student): ahmed@student.du.ac.bd / password
ফাতেমা খাতুন (teacher): fatema@teacher.du.ac.bd / password
রহিম উদ্দিন (public): rahim@public.com / password
John Smith (public): john@international.com / password
সালমা আক্তার (pending): salma@pending.com / password
করিম মিয়া (rejected): karim@rejected.com / password

## 📊 Sample Data Included
The system comes pre-seeded with:
- 15 Famous Authors (Rabindranath Tagore, Shakespeare, etc.)
- 15 Publishers (Ananda Publishers, Penguin, etc.)
- 15 Languages (Bengali, English, Hindi, etc.)
- 10 Sample Books with proper relationships
- Admin user and demo member

## 🎯 Next Steps to Complete

### Phase 1: Core Features (High Priority)
- [ ] **Member Registration & Authentication**
  - Public member registration form
  - Email verification
  - Member dashboard with borrowed books
  - Profile management

- [ ] **Enhanced Book Management**
  - Bulk book import (CSV/Excel)
  - Book reservations system
  - Availability notifications
  - Book reviews and ratings

- [ ] **Loan Management Improvements**
  - Due date reminders (email/SMS)
  - Fine calculation system
  - Loan history and analytics
  - Renewal requests

### Phase 2: Advanced Features (Medium Priority)
- [ ] **Reporting & Analytics**
  - Popular books report
  - Member activity reports
  - Overdue books report
  - Library statistics dashboard
  - Export reports (PDF/Excel)

- [ ] **Inventory Management**
  - Barcode scanning integration
  - Stock alerts for low inventory
  - Book condition tracking
  - Asset management

- [ ] **Communication System**
  - Email notifications
  - SMS integration (optional)
  - Announcement system
  - Newsletter management

### Phase 3: Enhancement Features (Low Priority)
- [ ] **Digital Library**
  - PDF reader integration
  - E-book management
  - Digital lending limits

- [ ] **Advanced Search**
  - Full-text search in PDFs
  - AI-powered book recommendations
  - Search analytics

- [ ] **Mobile App**
  - React Native or Flutter app
  - QR code scanning
  - Offline book catalog

- [ ] **Integration Features**
  - Library consortium integration
  - External catalog search
  - Social media integration

### Phase 4: System Optimization
- [ ] **Performance**
  - Database indexing optimization
  - Caching implementation (Redis)
  - Image optimization
  - CDN integration

- [ ] **Security**
  - Two-factor authentication
  - API rate limiting
  - Security audit
  - GDPR compliance

- [ ] **DevOps**
  - Docker containerization
  - CI/CD pipeline
  - Automated testing
  - Production deployment guide

## 🗂 Project Structure
```
app/
├── Http/Controllers/
│   ├── HomeController.php          # Public book catalog
│   └── Admin/                      # Admin panel controllers
├── Models/
│   ├── Author.php                  # Author model
│   ├── Publisher.php               # Publisher model
│   ├── Language.php                # Language model
│   └── Models/                     # Legacy models
resources/
├── views/
│   ├── home.blade.php              # Public homepage
│   └── admin/                      # Admin panel views
└── js/                             # Frontend assets
database/
├── migrations/                     # Database schema
└── seeders/                        # Sample data
```

## 🚀 Deployment
1. Set up production environment
2. Configure `.env` for production
3. Run `npm run build`
4. Set up web server (Nginx/Apache)
5. Configure SSL certificate
6. Set up scheduled tasks for maintenance

## 📝 Contributing
1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License
This project is licensed under the MIT License.

## 🆘 Support
For support and questions, please open an issue or contact the development team.
