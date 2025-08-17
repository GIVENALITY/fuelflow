# FuelFlow - Fuel Company Billing Management System

A comprehensive Laravel-based billing management system designed specifically for fuel companies. This system provides complete management of customers, fuel deliveries, billing, payments, and reporting.

## 🚀 Features

### Core Modules
- **Dashboard** - Overview with key metrics and recent activities
- **Billing Management** - Create, manage, and track fuel bills
- **Customer Management** - Complete customer database with credit limits
- **Fuel Inventory** - Track fuel types, prices, and stock levels
- **Delivery Management** - Schedule and track fuel deliveries
- **Payment Processing** - Handle payments and track outstanding amounts
- **Reporting** - Comprehensive reports and analytics

### Key Features
- ✅ Modern Material Dashboard UI
- ✅ Responsive design for mobile and desktop
- ✅ Real-time billing calculations
- ✅ Automated overdue tracking
- ✅ Credit limit management
- ✅ Delivery scheduling
- ✅ Payment processing
- ✅ Export functionality
- ✅ Comprehensive reporting

## 🛠️ Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates with Material Dashboard 3
- **Database**: MySQL/PostgreSQL
- **Styling**: Bootstrap 5 + Material Design
- **Icons**: Material Symbols + Font Awesome

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or PostgreSQL 10+
- Node.js & NPM (for asset compilation)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd fuelflow
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fuelflow
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Compile assets**
   ```bash
   npm run dev
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## 📁 Project Structure

```
fuelflow/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   │   ├── layouts/        # Layout templates
│   │   ├── components/     # Reusable components
│   │   └── pages/          # Page-specific views
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── routes/                # Application routes
└── public/               # Public assets
```

## 🎯 Core Models

### Bill
- Manages fuel billing records
- Tracks payment status and due dates
- Calculates late fees automatically
- Links to customers and fuel types

### Customer
- Stores customer information
- Manages credit limits and payment terms
- Tracks outstanding balances
- Links to bills and payments

### Delivery
- Schedules fuel deliveries
- Tracks delivery status
- Automatically creates bills upon completion
- Links to customers and fuel types

### FuelType
- Manages different fuel types
- Tracks current prices
- Links to inventory levels
- Used in billing and deliveries

## 🔧 Configuration

### Billing Settings
- Payment terms (default: 30 days)
- Late fee rates (default: 5% per month)
- Credit limit warnings
- Auto-overdue marking

### Fuel Management
- Fuel type configuration
- Price management
- Inventory tracking
- Low stock alerts

## 📊 Reports Available

1. **Sales Reports**
   - Monthly revenue trends
   - Fuel type sales analysis
   - Customer sales performance

2. **Customer Reports**
   - Outstanding balances
   - Payment history
   - Credit limit utilization

3. **Delivery Reports**
   - Delivery performance
   - Route optimization
   - Driver efficiency

4. **Financial Reports**
   - Cash flow analysis
   - Profitability by fuel type
   - Outstanding receivables

## 🔐 Security Features

- CSRF protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- Secure authentication

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Configure production database
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Set up proper web server configuration

### Recommended Server Requirements
- PHP 8.1+
- MySQL 5.7+ or PostgreSQL 10+
- Redis (for caching)
- SSL certificate

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

## 🔄 Updates

Stay updated with the latest features and bug fixes by regularly pulling from the main branch.

---

**FuelFlow** - Streamlining fuel company operations with modern billing management.
