# Nard Candles E-commerce

## Running the Application on Windows with PowerShell

Since PowerShell doesn't support the `&&` operator in the same way as bash, you'll need to run the backend and frontend servers in separate PowerShell windows.

### Running the Backend Server

```powershell
cd nard-candle-backend
php artisan serve
```

### Running the Frontend Server

```powershell
cd nard-candles-frontend
npm run dev
```

## Database Migration Updates

If you're updating an existing installation, you'll need to run the database migrations to make the necessary changes:

```powershell
cd nard-candle-backend
php artisan migrate
```

This will update the orders table to make `product_id` and `quantity` fields nullable, allowing orders to be created with only the `order_items` JSON field. 


# Nard Candles — Fullstack (Laravel + React)

Professional, production-ready codebase for the Nard Candles e-commerce website. This repository contains the Laravel backend (API + admin) and the React frontend (customer-facing storefront).

**Table of Contents**
- **Overview**: What this project contains
- **Tech Stack**: Libraries and frameworks used
- **Features**: Quick feature list
- **Repository Structure**: Key folders to explore
- **Getting Started**: Local setup for backend and frontend
- **Environment Variables**: Important `.env` keys
- **Running & Development**: Commands to run the app locally
- **API Reference**: Common endpoints and examples
- **Payments**: Chapa integration notes
- **Deployment**: Quick production checklist
- **Contributing**: How to help
- **License**: Project license

**Overview**

This monorepo hosts two primary applications:
- `nard-candle-backend` — Laravel (v8.x) application that provides the API, admin dashboard and payment integration.
- `nard-candles-frontend` — React (Vite) single page application for customers.

The backend is built with Laravel, uses Sanctum for API authentication, and integrates with the Chapa payment gateway. The frontend is a modern React/Vite app using `react-router`, `axios`, and several UI/UX libraries.

**Tech Stack**
- Backend: PHP 7.3+ / PHP 8.0+, Laravel 8.x, Sanctum, MySQL (or other supported DB), Composer
- Frontend: React 18, Vite, Axios, React Router
- Payment: Chapa (checkout SDK + server API)
- Dev tools: npm / node, Laravel Mix (admin assets), Tailwind (admin styles), Vite (frontend)

**Features**
- Product listing, categories, and featured/promotional products
- Shopping cart and checkout
- Orders, order history and admin order management
- Custom orders (customer-submitted customizations)
- YouTube video management (admin)
- Admin dashboard and resources (products, promotions, posts, messages)
- Payment initialization and verification using Chapa
- Email validation helper for payment compatibility

**Repository Structure**
- `nard-candle-backend/` — Laravel app
  - `app/Models/` — domain models (Product, Order, Cart, Promotion, User, etc.)
  - `app/Http/Controllers/` — controllers for API, web, payments, admin
  - `routes/web.php` — admin and web routes
  - `routes/api.php` — API endpoints consumed by the React frontend
  - `.env.example` — example environment variables (copy to `.env`)
- `nard-candles-frontend/` — React frontend
  - `src/pages/` — main pages (Home, Products, Cart, Profile, MyOrders, etc.)
  - `src/components/` — reusable UI components
  - `index.html` — includes Chapa checkout script for client-side flow

**Getting Started (Prerequisites)**
- PHP 8.0+ (or 7.3+ per composer constraints), Composer
- Node.js 18+ and npm
- MySQL or another supported relational DB
- Git

**Backend — Local Setup**
1. Open a terminal and change to the backend folder:

```bash
cd nard-candle-backend
```

2. Copy environment file and configure DB + keys:

```bash
cp .env.example .env
# Edit .env: set DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL, CHAPA_SECRET_KEY, MAIL settings
```

3. Install PHP dependencies and generate app key:

```bash
composer install
php artisan key:generate
```

4. Run database migrations and seeders (if present):

```bash
php artisan migrate --seed
```

5. Install admin assets and run dev build (optional for admin UI):

```bash
npm install
npm run dev    # uses Laravel Mix for admin assets
```

6. Run the application:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

The API will be available at `http://127.0.0.1:8000` by default.

**Frontend — Local Setup**
1. Change to the frontend folder and install dependencies:

```bash
cd ../nard-candles-frontend
npm install
```

2. Start the Vite dev server:

```bash
npm run dev
```

3. Open the app in your browser; Vite will print the dev URL (commonly `http://localhost:5173`).

Tip: set `APP_URL` in backend `.env` and add the frontend origin to `SANCTUM_STATEFUL_DOMAINS` if using Sanctum token authentication.

**Environment Variables (Important)**
In `nard-candle-backend/.env` make sure to configure at least the following:
- `APP_NAME`, `APP_ENV`, `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `CHAPA_SECRET_KEY` — server-side secret for Chapa payment API (keep this private!)
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` — for transactional emails

Do NOT commit `.env` with secrets. Use environment management on your cloud host.

**Running & Development**
- Backend: `cd nard-candle-backend && php artisan serve`
- Backend assets (admin UI): `npm run dev` (inside `nard-candle-backend`)
- Frontend (customer SPA): `cd nard-candles-frontend && npm run dev`

**API Reference (common endpoints)**
- GET `/api/test` — health-check, returns JSON `{"status":"API is working"}`
- GET `/api/products` — list products
- GET `/api/featured_products` — list featured products
- GET `/api/promotions` — active promotions
- POST `/api/custom-orders` — submit a custom order
- POST `/api/messages` — send contact message
- Auth (Sanctum)
  - POST `/api/login` — login
  - POST `/api/register` — register

Example: test endpoint with curl

```bash
curl http://127.0.0.1:8000/api/test
# => {"status":"API is working"}
```

Cart endpoints (authenticated / protected by Sanctum):
- POST `/api/cart/add` — add item
- POST `/api/cart/remove` — remove item
- GET `/api/cart` — get cart items

Order & Payment
- POST `/api/initiate-payment` (authenticated) — starts the Chapa payment flow
- GET `/api/payment-status/{tx_ref}` — check payment status

**Payments — Chapa integration**
- The backend uses Chapa to initialize and verify payments. The server requires `CHAPA_SECRET_KEY` set in `.env`.
- The frontend includes Chapa's checkout scripts (`index.html` references `chapa.js`/`checkout.js`) to support client-side interactions when required.
- Important: Do not expose the secret key in client-side code. Keep the backend as the single source of truth for initialization/verification.

**Admin Panel**
- Admin routes are grouped under `/admin` and protected via the `admin` middleware. Admin CRUD resources include products, promotions, featured products, posts, messages and YouTube videos.

**Deployment Checklist**
- Configure environment variables in your host (DB, CHAPA key, mail)
- Run `composer install --no-dev` and `npm ci --production` for assets
- Run `php artisan migrate --force` during deployment
- Cache config & routes: `php artisan config:cache && php artisan route:cache`
- Use `php artisan queue:work` (or supervisor) if using queued jobs
- Build frontend: `cd nard-candles-frontend && npm run build` and serve static assets with your host

**Troubleshooting**
- If Sanctum authentication fails, check `SANCTUM_STATEFUL_DOMAINS` and CORS config in `config/cors.php`.
- If Chapa payments fail, verify `CHAPA_SECRET_KEY` and check logs in `storage/logs/laravel.log` for payload/response details.
- Database errors: ensure migrations ran and DB credentials match `.env`.

**Contributing**
- Fork the repo, create a feature branch, and open a pull request.
- Keep backend and frontend changes logically separated.
- Run linters/tests before opening a PR. Use `npm run lint` in the frontend to run eslint.

**License**
This project uses the MIT license via the Laravel base. See `LICENSE` or the `composer.json` for license details.

