# FuelFlow - Fleet Fuel Management System

A comprehensive Laravel-based fleet fuel management system designed for energy companies operating multiple fuel stations to efficiently manage their corporate fleet clients.

## 🚀 Features

### Core Modules
- **Dashboard** - Role-based overview with key metrics and recent activities
- **Client Management** - Complete client database with credit limits and account status
- **Fuel Request Workflow** - End-to-end request management from submission to completion
- **Station Operations** - Multi-station management with fuel inventory tracking
- **Financial Management** - Receipt verification, payment processing, and reconciliation
- **Reporting** - Comprehensive analytics and operational insights

### Key Features
- ✅ Multi-role user system (Admin, Station Manager, Fuel Pumper, Treasury, Client)
- ✅ Modern Material Dashboard UI with role-based navigation
- ✅ Real-time credit limit validation and breach prevention
- ✅ Automated workflow management with notifications
- ✅ Mobile-responsive design for field operations
- ✅ Comprehensive reporting and analytics
- ✅ Receipt upload and verification system
- ✅ Payment tracking and reconciliation

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

6. **Run migrations and seed the database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Compile assets**
   ```bash
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🔐 Default Login Credentials

After running the seeder, you can login with these test accounts:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@fuelflow.com | password |
| **Station Manager** | manager@fuelflow.com | password |
| **Fuel Pumper** | pumper@fuelflow.com | password |
| **Treasury** | treasury@fuelflow.com | password |
| **Client** | client@fuelflow.com | password |

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

### User
- Multi-role user management (Admin, Station Manager, Fuel Pumper, Treasury, Client)
- Role-based permissions and access control
- Station assignments for field staff

### Station
- Multi-station management with individual configurations
- Fuel inventory tracking (diesel/petrol levels)
- Operating hours and capacity management
- Staff assignment and management

### Client
- Corporate fleet client management
- Credit limit and balance tracking
- Payment terms and account status management
- Preferred station assignments

### Vehicle
- Fleet vehicle management per client
- Fuel type and tank capacity tracking
- Maintenance and registration monitoring
- Driver assignment and contact information

### FuelRequest
- Complete fuel request workflow
- Credit limit validation
- Multi-level approval process
- Status tracking and notifications

### Receipt
- Digital receipt upload and management
- Verification workflow for treasury
- Payment reconciliation tracking

## 🔧 Configuration

### User Roles & Permissions
- **Admin**: Full system access, user management, station configuration
- **Station Manager**: Station operations, request approvals, receipt uploads
- **Fuel Pumper**: Fuel dispensing, assignment management
- **Treasury**: Financial verification, payment reconciliation
- **Client**: Self-service fuel requests (optional portal)

### Credit Management
- Dynamic credit limits with real-time validation
- Automatic breach prevention and alerts
- Payment terms and overdue monitoring
- Credit history tracking

### Fuel Request Workflow
1. Client submits request
2. System validates credit limit and station capacity
3. Station Manager approves/rejects
4. Fuel Pumper dispenses fuel
5. Receipt uploaded and verified by Treasury
6. Payment processed and reconciled

## 📊 Reports Available

1. **Operational Reports**
   - Daily fuel dispensing summaries
   - Station performance metrics
   - Request status dashboards
   - Staff productivity reports

2. **Financial Reports**
   - Outstanding balance aging
   - Payment reconciliation summaries
   - Revenue analysis by station/client
   - Credit utilization reports

3. **Client Analytics**
   - Usage patterns and consumption trends
   - Fleet performance metrics
   - Payment behavior analysis
   - Station preference reports

## 🔐 Security Features

- Multi-factor authentication support
- Role-based access control (RBAC)
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- Audit trails for financial transactions

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

**FuelFlow** - Streamlining fleet fuel operations with modern management systems.
