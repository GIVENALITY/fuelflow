<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Station;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\FuelRequest;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Location;
use App\Models\FuelPrice;

class EnhancedFleetFuelSeeder extends Seeder
{
    public function run()
    {
        // Create locations
        $locations = [
            ['name' => 'Dar es Salaam', 'type' => 'other', 'latitude' => -6.7924, 'longitude' => 39.2083, 'city' => 'Dar es Salaam', 'state' => 'Dar es Salaam'],
            ['name' => 'Arusha', 'type' => 'other', 'latitude' => -3.3869, 'longitude' => 36.6830, 'city' => 'Arusha', 'state' => 'Arusha'],
            ['name' => 'Mwanza', 'type' => 'other', 'latitude' => -2.5164, 'longitude' => 32.9176, 'city' => 'Mwanza', 'state' => 'Mwanza'],
            ['name' => 'Dodoma', 'type' => 'other', 'latitude' => -6.1630, 'longitude' => 35.7516, 'city' => 'Dodoma', 'state' => 'Dodoma'],
        ];

        foreach ($locations as $locationData) {
            Location::create($locationData);
        }

        // Create SuperAdmin user (using admin role for now)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@fuelflow.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin', // Using admin role until enum is updated
                'phone' => '+255123456789',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create Admin users
        $admin = User::firstOrCreate(
            ['email' => 'admin@fuelflow.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+255123456790',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create Treasury user
        $treasury = User::firstOrCreate(
            ['email' => 'treasury@fuelflow.com'],
            [
                'name' => 'Treasury Manager',
                'password' => Hash::make('password'),
                'role' => 'treasury',
                'phone' => '+255123456791',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create stations
        $stations = [
            [
                'name' => 'Shell Kariakoo',
                'code' => 'SK001',
                'location_id' => 1,
                'phone' => '+255222345678',
                'email' => 'kariakoo@shell.com',
                'capacity_diesel' => 50000,
                'capacity_petrol' => 30000,
                'current_diesel_level' => 35000,
                'current_petrol_level' => 20000,
                'status' => Station::STATUS_ACTIVE,
            ],
            [
                'name' => 'Total Arusha',
                'code' => 'TA001',
                'location_id' => 2,
                'phone' => '+255272345678',
                'email' => 'arusha@total.com',
                'capacity_diesel' => 40000,
                'capacity_petrol' => 25000,
                'current_diesel_level' => 28000,
                'current_petrol_level' => 15000,
                'status' => Station::STATUS_ACTIVE,
            ],
            [
                'name' => 'Engen Mwanza',
                'code' => 'EM001',
                'location_id' => 3,
                'phone' => '+255282345678',
                'email' => 'mwanza@engen.com',
                'capacity_diesel' => 45000,
                'capacity_petrol' => 28000,
                'current_diesel_level' => 32000,
                'current_petrol_level' => 18000,
                'status' => Station::STATUS_ACTIVE,
            ],
        ];

        foreach ($stations as $stationData) {
            Station::firstOrCreate(
                ['name' => $stationData['name']],
                $stationData
            );
        }

        // Create Station Managers
        $stationManagers = [
            [
                'name' => 'John Mwalimu',
                'email' => 'john.mwalimu@fuelflow.com',
                'password' => Hash::make('password'),
                'role' => 'station_manager',
                'station_id' => 1,
                'phone' => '+255123456792',
                'status' => User::STATUS_ACTIVE,
            ],
            [
                'name' => 'Mary Kimaro',
                'email' => 'mary.kimaro@fuelflow.com',
                'password' => Hash::make('password'),
                'role' => 'station_manager',
                'station_id' => 2,
                'phone' => '+255123456793',
                'status' => User::STATUS_ACTIVE,
            ],
            [
                'name' => 'Peter Mwamba',
                'email' => 'peter.mwamba@fuelflow.com',
                'password' => Hash::make('password'),
                'role' => 'station_manager',
                'station_id' => 3,
                'phone' => '+255123456794',
                'status' => User::STATUS_ACTIVE,
            ],
        ];

        foreach ($stationManagers as $managerData) {
            User::firstOrCreate(
                ['email' => $managerData['email']],
                $managerData
            );
        }

        // Create Station Attendants (using station_manager role for now)
        $attendants = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@fuelflow.com',
                'password' => Hash::make('password'),
                'role' => 'station_manager', // Using existing role
                'station_id' => 1,
                'phone' => '+255123456795',
                'status' => User::STATUS_ACTIVE,
            ],
            [
                'name' => 'Grace Mwangi',
                'email' => 'grace.mwangi@fuelflow.com',
                'password' => Hash::make('password'),
                'role' => 'station_manager', // Using existing role
                'station_id' => 1,
                'phone' => '+255123456796',
                'status' => User::STATUS_ACTIVE,
            ],
            [
                'name' => 'Juma Mwalimu',
                'email' => 'juma.mwalimu@fuelflow.com',
                'password' => Hash::make('password'),
                'role' => 'station_manager', // Using existing role
                'station_id' => 2,
                'phone' => '+255123456797',
                'status' => User::STATUS_ACTIVE,
            ],
        ];

        foreach ($attendants as $attendantData) {
            User::firstOrCreate(
                ['email' => $attendantData['email']],
                $attendantData
            );
        }

        // Create Client users and clients
        $clients = [
            [
                'user' => [
                    'name' => 'Transport Company Ltd',
                    'email' => 'info@transportco.co.tz',
                    'password' => Hash::make('password'),
                    'role' => 'client',
                    'phone' => '+255123456798',
                    'status' => User::STATUS_ACTIVE,
                ],
                'client' => [
                    'company_name' => 'Transport Company Ltd',
                    'contact_person' => 'John Doe',
                    'email' => 'info@transportco.co.tz',
                    'phone' => '+255123456798',
                    'address' => 'Kariakoo Street, Dar es Salaam',
                    'city' => 'Dar es Salaam',
                    'state' => 'Dar es Salaam',
                    'zip_code' => '11101',
                    'country' => 'Tanzania',
                    'tax_id' => 'TIN123456789',
                    'credit_limit' => 5000000,
                    'current_balance' => 1500000,
                    'registration_status' => Client::REGISTRATION_STATUS_ACTIVE,
                    'status' => Client::STATUS_ACTIVE,
                    'approved_by' => $admin->id,
                    'approved_at' => now()->subDays(30),
                ],
            ],
            [
                'user' => [
                    'name' => 'Logistics Solutions',
                    'email' => 'admin@logistics.co.tz',
                    'password' => Hash::make('password'),
                    'role' => 'client',
                    'phone' => '+255123456799',
                    'status' => User::STATUS_ACTIVE,
                ],
                'client' => [
                    'company_name' => 'Logistics Solutions',
                    'contact_person' => 'Jane Smith',
                    'email' => 'admin@logistics.co.tz',
                    'phone' => '+255123456799',
                    'address' => 'Arusha Road, Arusha',
                    'city' => 'Arusha',
                    'state' => 'Arusha',
                    'zip_code' => '23101',
                    'country' => 'Tanzania',
                    'tax_id' => 'TIN987654321',
                    'credit_limit' => 3000000,
                    'current_balance' => 800000,
                    'registration_status' => Client::REGISTRATION_STATUS_ACTIVE,
                    'status' => Client::STATUS_ACTIVE,
                    'approved_by' => $admin->id,
                    'approved_at' => now()->subDays(20),
                ],
            ],
            [
                'user' => [
                    'name' => 'Fleet Management Co',
                    'email' => 'contact@fleetmgmt.co.tz',
                    'password' => Hash::make('password'),
                    'role' => 'client',
                    'phone' => '+255123456800',
                    'status' => User::STATUS_ACTIVE,
                ],
                'client' => [
                    'company_name' => 'Fleet Management Co',
                    'contact_person' => 'Michael Johnson',
                    'email' => 'contact@fleetmgmt.co.tz',
                    'phone' => '+255123456800',
                    'address' => 'Mwanza Street, Mwanza',
                    'city' => 'Mwanza',
                    'state' => 'Mwanza',
                    'zip_code' => '33101',
                    'country' => 'Tanzania',
                    'tax_id' => 'TIN456789123',
                    'credit_limit' => 7500000,
                    'current_balance' => 2500000,
                    'registration_status' => Client::REGISTRATION_STATUS_ACTIVE,
                    'status' => Client::STATUS_ACTIVE,
                    'approved_by' => $admin->id,
                    'approved_at' => now()->subDays(15),
                ],
            ],
        ];

        $clientUsers = [];
        foreach ($clients as $clientData) {
            $user = User::firstOrCreate(
                ['email' => $clientData['user']['email']],
                $clientData['user']
            );
            $clientData['client']['user_id'] = $user->id;
            $client = Client::firstOrCreate(
                ['email' => $clientData['client']['email']],
                $clientData['client']
            );
            $clientUsers[] = ['user' => $user, 'client' => $client];
        }

        // Create vehicles for clients
        $vehicles = [
            // Transport Company Ltd vehicles
            [
                'client_id' => 1,
                'plate_number' => 'T123ABC',
                'vehicle_type' => Vehicle::TYPE_TRUCK,
                'make' => 'Isuzu',
                'model' => 'NPR',
                'year' => 2020,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 200,
                'driver_name' => 'Ali Mwalimu',
                'driver_phone' => '+255123456801',
                'status' => Vehicle::STATUS_ACTIVE,
            ],
            [
                'client_id' => 1,
                'plate_number' => 'T456DEF',
                'vehicle_type' => 'truck', // Using existing type
                'make' => 'Volvo',
                'model' => 'FH16',
                'year' => 2019,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 500,
                'driver_name' => 'Hassan Ali',
                'driver_phone' => '+255123456802',
                'status' => Vehicle::STATUS_ACTIVE,
            ],
            // Logistics Solutions vehicles
            [
                'client_id' => 2,
                'plate_number' => 'A789GHI',
                'vehicle_type' => Vehicle::TYPE_VAN,
                'make' => 'Toyota',
                'model' => 'Hiace',
                'year' => 2021,
                'fuel_type' => Vehicle::FUEL_PETROL,
                'tank_capacity' => 70,
                'driver_name' => 'Grace Mwangi',
                'driver_phone' => '+255123456803',
                'status' => Vehicle::STATUS_ACTIVE,
            ],
            [
                'client_id' => 2,
                'plate_number' => 'A012JKL',
                'vehicle_type' => Vehicle::TYPE_TRUCK,
                'make' => 'Mitsubishi',
                'model' => 'Fuso',
                'year' => 2018,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 150,
                'driver_name' => 'Peter Kimaro',
                'driver_phone' => '+255123456804',
                'status' => Vehicle::STATUS_ACTIVE,
            ],
            // Fleet Management Co vehicles
            [
                'client_id' => 3,
                'plate_number' => 'M345MNO',
                'vehicle_type' => Vehicle::TYPE_BUS,
                'make' => 'Scania',
                'model' => 'K360',
                'year' => 2020,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 300,
                'driver_name' => 'Juma Mwamba',
                'driver_phone' => '+255123456805',
                'status' => Vehicle::STATUS_ACTIVE,
            ],
            [
                'client_id' => 3,
                'plate_number' => 'M678PQR',
                'vehicle_type' => Vehicle::TYPE_TRUCK,
                'make' => 'Mercedes',
                'model' => 'Actros',
                'year' => 2019,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 400,
                'driver_name' => 'Salim Hassan',
                'driver_phone' => '+255123456806',
                'status' => Vehicle::STATUS_ACTIVE,
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::firstOrCreate(
                ['plate_number' => $vehicleData['plate_number']],
                $vehicleData
            );
        }

        // Create fuel prices (Current Tanzania prices as of October 2025)
        // Petrol: ~2,752 TZS/L | Diesel: ~2,704 TZS/L
        $fuelPrices = [
            ['station_id' => 1, 'fuel_type' => 'diesel', 'price' => 2704, 'effective_date' => now(), 'created_by' => $admin->id],
            ['station_id' => 1, 'fuel_type' => 'petrol', 'price' => 2752, 'effective_date' => now(), 'created_by' => $admin->id],
            ['station_id' => 2, 'fuel_type' => 'diesel', 'price' => 2715, 'effective_date' => now(), 'created_by' => $admin->id],
            ['station_id' => 2, 'fuel_type' => 'petrol', 'price' => 2765, 'effective_date' => now(), 'created_by' => $admin->id],
            ['station_id' => 3, 'fuel_type' => 'diesel', 'price' => 2695, 'effective_date' => now(), 'created_by' => $admin->id],
            ['station_id' => 3, 'fuel_type' => 'petrol', 'price' => 2740, 'effective_date' => now(), 'created_by' => $admin->id],
        ];

        foreach ($fuelPrices as $priceData) {
            FuelPrice::firstOrCreate(
                [
                    'station_id' => $priceData['station_id'],
                    'fuel_type' => $priceData['fuel_type'],
                    'effective_date' => $priceData['effective_date']
                ],
                $priceData
            );
        }

        // Create sample fuel requests
        $fuelRequests = [
            [
                'client_id' => 1,
                'vehicle_id' => 1,
                'station_id' => 1,
                'fuel_type' => 'diesel',
                'quantity_requested' => 150,
                'quantity_dispensed' => 150,
                'unit_price' => 2500,
                'total_amount' => 375000,
                'request_date' => now()->subDays(5),
                'preferred_date' => now()->subDays(4),
                'due_date' => now()->addDays(3),
                'status' => FuelRequest::STATUS_COMPLETED,
                'urgency_level' => FuelRequest::URGENCY_STANDARD,
                'approved_by' => 4, // Station Manager ID
                'approved_at' => now()->subDays(4),
                'assigned_pumper_id' => 5, // Attendant ID
                'dispensed_by' => 5,
                'dispensed_at' => now()->subDays(4),
            ],
            [
                'client_id' => 2,
                'vehicle_id' => 3,
                'station_id' => 2,
                'fuel_type' => 'petrol',
                'quantity_requested' => 50,
                'quantity_dispensed' => 50,
                'unit_price' => 2850,
                'total_amount' => 142500,
                'request_date' => now()->subDays(3),
                'preferred_date' => now()->subDays(2),
                'due_date' => now()->addDays(4),
                'status' => FuelRequest::STATUS_COMPLETED,
                'urgency_level' => FuelRequest::URGENCY_STANDARD,
                'approved_by' => 5, // Station Manager ID
                'approved_at' => now()->subDays(2),
                'assigned_pumper_id' => 7, // Attendant ID
                'dispensed_by' => 7,
                'dispensed_at' => now()->subDays(2),
            ],
            [
                'client_id' => 3,
                'vehicle_id' => 5,
                'station_id' => 3,
                'fuel_type' => 'diesel',
                'quantity_requested' => 200,
                'quantity_dispensed' => null,
                'unit_price' => 2480,
                'total_amount' => 496000,
                'request_date' => now()->subDays(1),
                'preferred_date' => now(),
                'due_date' => now()->addDays(6),
                'status' => FuelRequest::STATUS_PENDING,
                'urgency_level' => FuelRequest::URGENCY_PRIORITY,
            ],
        ];

        foreach ($fuelRequests as $requestData) {
            FuelRequest::firstOrCreate(
                [
                    'client_id' => $requestData['client_id'],
                    'vehicle_id' => $requestData['vehicle_id'],
                    'request_date' => $requestData['request_date']
                ],
                $requestData
            );
        }

        // Create sample receipts
        $receipts = [
            [
                'fuel_request_id' => 1,
                'client_id' => 1,
                'station_id' => 1,
                'uploaded_by' => 5, // Attendant ID
                'amount' => 375000,
                'quantity' => 150,
                'fuel_type' => 'diesel',
                'receipt_number' => 'RCP-000001',
                'file_path' => 'receipts/sample_receipt_1.pdf',
                'status' => Receipt::STATUS_VERIFIED,
                'verified_by' => $treasury->id,
                'verified_at' => now()->subDays(3),
            ],
            [
                'fuel_request_id' => 2,
                'client_id' => 2,
                'station_id' => 2,
                'uploaded_by' => 7, // Attendant ID
                'amount' => 142500,
                'quantity' => 50,
                'fuel_type' => 'petrol',
                'receipt_number' => 'RCP-000002',
                'file_path' => 'receipts/sample_receipt_2.pdf',
                'status' => Receipt::STATUS_VERIFIED,
                'verified_by' => $treasury->id,
                'verified_at' => now()->subDays(1),
            ],
        ];

        foreach ($receipts as $receiptData) {
            Receipt::firstOrCreate(
                ['receipt_number' => $receiptData['receipt_number']],
                $receiptData
            );
        }

        // Create sample payments
        $payments = [
            [
                'client_id' => 1,
                'receipt_id' => 1,
                'amount' => 375000,
                'payment_method' => 'bank_transfer',
                'bank_name' => 'CRDB Bank',
                'payment_date' => now()->subDays(2),
                'proof_of_payment_path' => 'payments/proof_1.pdf',
                'submitted_by' => 1, // Client user ID
                'status' => 'completed', // Using existing status
                'verified_by' => $treasury->id,
                'verified_at' => now()->subDays(1),
            ],
            [
                'client_id' => 2,
                'receipt_id' => 2,
                'amount' => 142500,
                'payment_method' => 'bank_transfer',
                'bank_name' => 'NMB Bank',
                'payment_date' => now()->subDays(1),
                'proof_of_payment_path' => 'payments/proof_2.pdf',
                'submitted_by' => 2, // Client user ID
                'status' => 'pending',
            ],
        ];

        foreach ($payments as $paymentData) {
            Payment::firstOrCreate(
                [
                    'client_id' => $paymentData['client_id'],
                    'receipt_id' => $paymentData['receipt_id'],
                    'amount' => $paymentData['amount']
                ],
                $paymentData
            );
        }

        $this->command->info('Enhanced Fleet Fuel seeder completed successfully!');
        $this->command->info('Created:');
        $this->command->info('- 1 SuperAdmin user (superadmin@fuelflow.com)');
        $this->command->info('- 1 Admin user (admin@fuelflow.com)');
        $this->command->info('- 1 Treasury user (treasury@fuelflow.com)');
        $this->command->info('- 3 Station Managers');
        $this->command->info('- 3 Station Attendants');
        $this->command->info('- 3 Client companies with users');
        $this->command->info('- 6 Vehicles');
        $this->command->info('- 3 Fuel Stations');
        $this->command->info('- Sample fuel requests, receipts, and payments');
        $this->command->info('Default password for all users: password');
    }
}
