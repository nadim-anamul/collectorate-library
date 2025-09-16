## Collectorate Library (Bilingual Library Management System)

### Requirements
- PHP 7.4+
- MySQL
- Node.js + NPM

### Setup
1) Install dependencies
```
composer install
npm install
```

2) Copy env and configure DB
```
cp .env.example .env
php artisan key:generate
```

3) Configure database in `.env`, then run
```
php artisan migrate --seed
php artisan storage:link
```

4) Build assets
```
npm run dev
```

### Login
- Admin: email `admin@example.com`, password `password`

### Search (Algolia)
Optional but recommended for instant search.
Set in `.env`:
```
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your_app_id
ALGOLIA_SECRET=your_admin_api_key
```
Then import:
```
php artisan scout:import "App\\Models\\Models\\Book"
```

### Roles
- Admin, Librarian, Member via spatie/permission

### Modules
- Books (Bangla/English/Banglish), Categories, Tags
- Members + printable cards
- Loans: issue/return, late fees
- Reports & Activity Log

### Theme
- Dark/Light toggle in navbar, persists in localStorage
