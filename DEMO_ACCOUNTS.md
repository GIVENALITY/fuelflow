# FuelFlow Demo Accounts

## 🚀 Quick Setup

Run this command to create all demo accounts:

```bash
php artisan db:seed --class=DemoAccountsSeeder
```

---

## 👥 Demo Accounts

### 1. 🔑 **SUPER ADMIN**
- **Email:** `superadmin@fuelflow.com`
- **Password:** `password`
- **Access:** Platform-wide management, business creation, system oversight

**What you can do:**
- View all businesses
- Create new businesses
- Manage system users
- View platform-wide reports
- Approve/suspend businesses

---

### 2. 🏢 **BUSINESS ADMIN** (Petro Africa)
- **Email:** `admin@petroafrica.co.tz`
- **Password:** `password`
- **Business:** Petro Africa
- **Access:** Full business management

**What you can do:**
- Manage clients (approve, reject)
- Add/manage stations
- Register users (treasury, station managers)
- View business reports
- Manage vehicles
- Set credit limits

---

### 3. 💰 **TREASURY**
- **Email:** `treasury@petroafrica.co.tz`
- **Password:** `password`
- **Business:** Petro Africa
- **Access:** Payment verification and financial management

**What you can do:**
- Review pending payments
- Approve/reject payment submissions
- View proof of payments
- Update client balances
- Financial reporting

---

### 4. ⛽ **STATION MANAGER**
- **Email:** `manager@petroafrica.co.tz`
- **Password:** `password`
- **Business:** Petro Africa
- **Station:** Kivukoni
- **Access:** Station operations management

**What you can do:**
- Manage station attendants
- Process fuel requests
- Monitor station inventory
- View station reports

---

### 5. 🚛 **CLIENT 1** - Transport Co Ltd
- **Email:** `client@transportco.co.tz`
- **Password:** `password`
- **Company:** Transport Co Ltd
- **Credit Limit:** TZS 10,000,000
- **Current Debt:** TZS 2,500,000
- **Vehicles:** 3 trucks

**Vehicle Details:**
1. **T123 ABC** - Tractor (Diesel) - HEAD-001
2. **T456 DEF** - Trailer (Diesel) - TRAILER-002
3. **T789 GHI** - Tractor with Trailer (Diesel) - HEAD-003, TRAILER-003

**What you can do:**
- Create fuel orders
- Bulk order upload
- Submit payments (with proof)
- View payment history
- Track vehicle orders

---

### 6. 🚛 **CLIENT 2** - Logistics Plus
- **Email:** `client@logisticsplus.co.tz`
- **Password:** `password`
- **Company:** Logistics Plus Tanzania
- **Credit Limit:** TZS 5,000,000
- **Current Debt:** TZS 1,200,000

**What you can do:**
- Create fuel orders
- Submit payments
- View order history

---

## 🏪 Available Stations

### Petro Africa - Kivukoni
- **Code:** PA-KVK
- **Location:** Kivukoni, Dar es Salaam
- **Manager:** Hassan Juma
- **Status:** Active

### Petro Africa - Ubungo
- **Code:** PA-UBG
- **Location:** Ubungo, Dar es Salaam
- **Manager:** Amina Said
- **Status:** Active

---

## 🎬 Demo Flow

### Scenario 1: Client Orders Fuel
1. Login as **client@transportco.co.tz**
2. Go to "Create New Order"
3. Select driver name, truck (e.g., T123 ABC), station, and litres
4. Submit order
5. View order in "My Orders"

### Scenario 2: Client Submits Payment
1. Login as **client@transportco.co.tz**
2. Go to "Submit Payment"
3. Select bank (e.g., CRDB)
4. Enter amount (e.g., TZS 500,000)
5. Select payment date
6. Upload proof of payment (PDF/JPG)
7. Submit

### Scenario 3: Treasury Approves Payment
1. Login as **treasury@petroafrica.co.tz**
2. Go to Treasury Dashboard
3. Click "Pending Payments"
4. Review payment details
5. View proof of payment
6. Click "Approve Payment"
7. Client's debt is automatically reduced

### Scenario 4: Bulk Order Upload
1. Login as **client@transportco.co.tz**
2. Go to "Bulk Order Upload"
3. Fill in multiple orders (driver, truck, station, fuel type, litres)
4. Click "Add Another Order" to add more rows
5. Submit all orders at once

### Scenario 5: Business Admin Manages Clients
1. Login as **admin@petroafrica.co.tz**
2. Go to "Manage Clients"
3. View all clients
4. Check credit limits and balances
5. Add new vehicles for clients

---

## 💡 Tips for Demo

- All passwords are: `password`
- Client debt will decrease when treasury approves payments
- Orders exceeding credit limit will require special approval
- Bulk upload allows quick creation of multiple orders
- Proof of payment can be PDF, JPG, or PNG (max 5MB)

---

## 🔄 Reset Demo Data

To reset and recreate all demo accounts:

```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=DemoAccountsSeeder
```

---

## 📊 Test Transactions

### Sample Order:
- Driver: John Mwangi
- Truck: T123 ABC
- Station: Petro Africa - Kivukoni
- Fuel Type: Diesel
- Litres: 300
- Estimated Cost: TZS 900,000 (at TZS 3,000/litre)

### Sample Payment:
- Bank: CRDB Bank
- Amount: TZS 500,000
- Payment Date: Today
- Reference: CRDB-2024-001
- Proof: Upload any PDF/image

---

## 🎯 Key Features to Demonstrate

1. ✅ **Multi-tenant** - Multiple businesses on one platform
2. ✅ **Role-based access** - Different dashboards for each role
3. ✅ **Credit management** - Automatic limit checking
4. ✅ **Payment workflow** - Submit → Review → Approve → Balance update
5. ✅ **Bulk operations** - Upload multiple orders at once
6. ✅ **Vehicle registration** - Kadi ya kichwa, kadi ya trailer
7. ✅ **Real-time notifications** - Treasury gets notified of new payments
8. ✅ **Financial tracking** - Debt management and payment history

