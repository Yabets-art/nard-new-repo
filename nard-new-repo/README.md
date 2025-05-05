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