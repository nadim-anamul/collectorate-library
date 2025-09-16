# Collectorate Library Management System
*A modern, bilingual library management system built with Laravel 11 & Tailwind CSS*

## 🚀 Features

### ✅ Completed Features
- **Professional Home Page** - Rokomari-inspired design with search & filters
- **Role-Based Access Control** - Admin, Librarian, Member roles via Spatie Permission
- **Complete CRUD Operations** for:
  - 📚 Books (with cover images, PDFs, multilingual support)
  - 👥 Authors (Bengali & English names, bios)
  - 🏢 Publishers (with websites)
  - 🌐 Languages (15+ languages included)
  - 📂 Categories & Tags
  - 👤 Members (with printable ID cards)
  - 📋 Loans (issue/return tracking)
- **Modern Admin Dashboard** - Stats cards, filters, book grid
- **Bilingual Support** - Bengali, English, Banglish transliteration
- **Search & Filtering** - Advanced search by title, author, ISBN, category
- **Dark/Light Theme** - Toggle with localStorage persistence
- **Activity Logging** - Track all system activities
- **Responsive Design** - Works on desktop, tablet, mobile

## 🛠 Tech Stack
- **Backend:** Laravel 11, PHP 8.2+, MySQL
- **Frontend:** Tailwind CSS, Alpine.js, Vite
- **Authentication:** Laravel Breeze
- **Permissions:** Spatie Laravel Permission
- **File Storage:** Laravel Storage (covers, PDFs)

## 📋 Requirements
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+ & NPM
- Composer

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
- **Admin:** `admin@example.com` / `password`

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
