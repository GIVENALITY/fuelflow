<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use App\Models\Client;
use App\Models\Station;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;

class DemoAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. CREATE SUPER ADMIN
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@fuelflow.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );
        echo "✓ Super Admin created: superadmin@fuelflow.com / password\n";

        // 2. CREATE BUSINESS (Petro Africa)
        $business = Business::firstOrCreate(
            ['email' => 'info@petroafrica.co.tz'],
            [
                'name' => 'Petro Africa',
                'business_code' => 'PA001',
                'registration_number' => 'TZ-REG-12345',
                'phone' => '+255 22 123 4567',
                'address' => 'Dar es Salaam, Tanzania',
                'contact_person' => 'John Mwenda',
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $superAdmin->id,
            ]
        );
        echo "✓ Business created: Petro Africa\n";

        // 3. CREATE BUSINESS ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@petroafrica.co.tz'],
            [
                'name' => 'Admin Petro Africa',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'business_id' => $business->id,
                'status' => 'active',
            ]
        );
        echo "✓ Business Admin created: admin@petroafrica.co.tz / password\n";

        // 4. CREATE TREASURY USER
        $treasury = User::firstOrCreate(
            ['email' => 'treasury@petroafrica.co.tz'],
            [
                'name' => 'Grace Mwakyusa - Treasury',
                'password' => Hash::make('password'),
                'role' => 'treasury',
                'business_id' => $business->id,
                'status' => 'active',
            ]
        );
        echo "✓ Treasury User created: treasury@petroafrica.co.tz / password\n";

        // 5. CREATE STATIONS
        $station1 = Station::firstOrCreate(
            ['code' => 'PA-KVK'],
            [
                'name' => 'Petro Africa - Kivukoni',
                'business_id' => $business->id,
                'phone' => '+255 22 211 1111',
                'email' => 'kivukoni@petroafrica.co.tz',
                'status' => 'active',
                'capacity_diesel' => 50000,
                'capacity_petrol' => 30000,
                'current_diesel_level' => 35000,
                'current_petrol_level' => 20000,
            ]
        );

        $station2 = Station::firstOrCreate(
            ['code' => 'PA-UBG'],
            [
                'name' => 'Petro Africa - Ubungo',
                'business_id' => $business->id,
                'phone' => '+255 22 211 2222',
                'email' => 'ubungo@petroafrica.co.tz',
                'status' => 'active',
                'capacity_diesel' => 40000,
                'capacity_petrol' => 25000,
                'current_diesel_level' => 28000,
                'current_petrol_level' => 18000,
            ]
        );
        echo "✓ Stations created: Kivukoni, Ubungo\n";

        // 6. CREATE STATION MANAGER
        $stationManager = User::firstOrCreate(
            ['email' => 'manager@petroafrica.co.tz'],
            [
                'name' => 'Hassan Juma - Station Manager',
                'password' => Hash::make('password'),
                'role' => 'station_manager',
                'business_id' => $business->id,
                'station_id' => $station1->id,
                'status' => 'active',
            ]
        );
        echo "✓ Station Manager created: manager@petroafrica.co.tz / password\n";

        // 7. CREATE CLIENT COMPANY (Transport Co)
        $clientUser = User::firstOrCreate(
            ['email' => 'client@transportco.co.tz'],
            [
                'name' => 'Transport Co Client',
                'password' => Hash::make('password'),
                'role' => 'client',
                'business_id' => $business->id,
                'status' => 'active',
            ]
        );

        $client = Client::firstOrCreate(
            ['email' => 'client@transportco.co.tz'],
            [
                'business_id' => $business->id,
                'user_id' => $clientUser->id,
                'company_name' => 'Transport Co Ltd',
                'contact_person' => 'Mohamed Ali',
                'phone' => '+255 713 456 789',
                'address' => 'Pugu Road',
                'city' => 'Dar es Salaam',
                'state' => 'Dar es Salaam',
                'zip_code' => '14000',
                'country' => 'Tanzania',
                'tax_id' => 'TIN-123456789',
                'credit_limit' => 10000000, // 10 million TZS
                'current_balance' => 2500000, // 2.5 million TZS debt
                'registration_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ]
        );
        echo "✓ Client created: client@transportco.co.tz / password (Transport Co Ltd)\n";

        // 8. CREATE VEHICLES FOR CLIENT
        $vehicles = [
            [
                'plate_number' => 'T123 ABC',
                'truck_type' => 'tractor',
                'fuel_type' => 'diesel',
                'kadi_kichwa' => 'HEAD-001',
                'status' => 'active',
            ],
            [
                'plate_number' => 'T456 DEF',
                'truck_type' => 'trailer',
                'fuel_type' => 'diesel',
                'kadi_trailer' => 'TRAILER-002',
                'status' => 'active',
            ],
            [
                'plate_number' => 'T789 GHI',
                'truck_type' => 'tractor_trailer',
                'fuel_type' => 'diesel',
                'kadi_kichwa' => 'HEAD-003',
                'kadi_trailer' => 'TRAILER-003',
                'status' => 'active',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::firstOrCreate(
                ['plate_number' => $vehicleData['plate_number']],
                array_merge($vehicleData, [
                    'client_id' => $client->id,
                    'make' => 'Scania',
                    'model' => 'R-Series',
                    'year' => 2020,
                    'tank_capacity' => 500,
                ])
            );
        }
        echo "✓ Vehicles created: 3 trucks for Transport Co\n";

        // 9. CREATE ANOTHER CLIENT (Logistics Plus)
        $client2User = User::firstOrCreate(
            ['email' => 'client@logisticsplus.co.tz'],
            [
                'name' => 'Logistics Plus Client',
                'password' => Hash::make('password'),
                'role' => 'client',
                'business_id' => $business->id,
                'status' => 'active',
            ]
        );

        $client2 = Client::firstOrCreate(
            ['email' => 'client@logisticsplus.co.tz'],
            [
                'business_id' => $business->id,
                'user_id' => $client2User->id,
                'company_name' => 'Logistics Plus Tanzania',
                'contact_person' => 'Fatuma Hassan',
                'phone' => '+255 714 789 123',
                'address' => 'Nelson Mandela Road',
                'city' => 'Dar es Salaam',
                'state' => 'Dar es Salaam',
                'zip_code' => '15000',
                'country' => 'Tanzania',
                'tax_id' => 'TIN-987654321',
                'credit_limit' => 5000000, // 5 million TZS
                'current_balance' => 1200000, // 1.2 million TZS debt
                'registration_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ]
        );
        echo "✓ Client created: client@logisticsplus.co.tz / password (Logistics Plus)\n";

        // Summary
        echo "\n";
        echo "====================================\n";
        echo "DEMO ACCOUNTS CREATED SUCCESSFULLY!\n";
        echo "====================================\n\n";
        
        echo "SUPER ADMIN:\n";
        echo "  Email: superadmin@fuelflow.com\n";
        echo "  Password: password\n\n";
        
        echo "BUSINESS ADMIN (Petro Africa):\n";
        echo "  Email: admin@petroafrica.co.tz\n";
        echo "  Password: password\n\n";
        
        echo "TREASURY:\n";
        echo "  Email: treasury@petroafrica.co.tz\n";
        echo "  Password: password\n\n";
        
        echo "STATION MANAGER:\n";
        echo "  Email: manager@petroafrica.co.tz\n";
        echo "  Password: password\n\n";
        
        echo "CLIENTS:\n";
        echo "  1. Transport Co Ltd\n";
        echo "     Email: client@transportco.co.tz\n";
        echo "     Password: password\n";
        echo "     Credit Limit: TZS 10,000,000\n";
        echo "     Current Debt: TZS 2,500,000\n";
        echo "     Vehicles: 3 trucks\n\n";
        
        echo "  2. Logistics Plus\n";
        echo "     Email: client@logisticsplus.co.tz\n";
        echo "     Password: password\n";
        echo "     Credit Limit: TZS 5,000,000\n";
        echo "     Current Debt: TZS 1,200,000\n\n";
        
        echo "STATIONS:\n";
        echo "  - Petro Africa - Kivukoni\n";
        echo "  - Petro Africa - Ubungo\n\n";
    }
}

