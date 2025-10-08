<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Station;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\FuelPrice;
use Illuminate\Support\Facades\Hash;

class FleetFuelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@fuelflow.co.tz'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create stations (Tanzania locations)
        $station1 = Station::updateOrCreate(
            ['code' => 'DSM001'],
            [
                'name' => 'Kariakoo Fuel Station',
                'address' => 'Msimbazi Street, Kariakoo',
                'city' => 'Dar es Salaam',
                'state' => 'Dar es Salaam',
                'zip_code' => '11101',
                'phone' => '+255 22 218 6000',
                'email' => 'kariakoo@fuelflow.co.tz',
                'status' => Station::STATUS_ACTIVE,
                'capacity_diesel' => 50000,
                'capacity_petrol' => 75000,
                'current_diesel_level' => 35000,
                'current_petrol_level' => 50000,
                'operating_hours' => [
                    'monday' => ['open' => '06:00', 'close' => '22:00'],
                    'tuesday' => ['open' => '06:00', 'close' => '22:00'],
                    'wednesday' => ['open' => '06:00', 'close' => '22:00'],
                    'thursday' => ['open' => '06:00', 'close' => '22:00'],
                    'friday' => ['open' => '06:00', 'close' => '22:00'],
                    'saturday' => ['open' => '06:00', 'close' => '22:00'],
                    'sunday' => ['open' => '07:00', 'close' => '21:00'],
                ],
                'timezone' => 'Africa/Dar_es_Salaam',
            ]
        );

        $station2 = Station::updateOrCreate(
            ['code' => 'DSM002'],
            [
                'name' => 'Morogoro Road Fuel Station',
                'address' => 'Morogoro Road, Ubungo',
                'city' => 'Dar es Salaam',
                'state' => 'Dar es Salaam',
                'zip_code' => '11103',
                'phone' => '+255 22 286 0000',
                'email' => 'morogoro@fuelflow.co.tz',
                'status' => Station::STATUS_ACTIVE,
                'capacity_diesel' => 75000,
                'capacity_petrol' => 100000,
                'current_diesel_level' => 55000,
                'current_petrol_level' => 75000,
                'operating_hours' => [
                    'monday' => ['open' => '00:00', 'close' => '23:59'],
                    'tuesday' => ['open' => '00:00', 'close' => '23:59'],
                    'wednesday' => ['open' => '00:00', 'close' => '23:59'],
                    'thursday' => ['open' => '00:00', 'close' => '23:59'],
                    'friday' => ['open' => '00:00', 'close' => '23:59'],
                    'saturday' => ['open' => '00:00', 'close' => '23:59'],
                    'sunday' => ['open' => '00:00', 'close' => '23:59'],
                ],
                'timezone' => 'Africa/Dar_es_Salaam',
            ]
        );

        // Create station manager
        $stationManager = User::updateOrCreate(
            ['email' => 'manager@fuelflow.co.tz'],
            [
                'name' => 'Juma Mwangi',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STATION_MANAGER,
                'station_id' => $station1->id,
                'phone' => '+255 754 123 456',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Update station with manager
        $station1->update(['manager_id' => $stationManager->id]);

        // Create fuel pumper
        $fuelPumper = User::updateOrCreate(
            ['email' => 'pumper@fuelflow.co.tz'],
            [
                'name' => 'Hassan Bakari',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STATION_ATTENDANT,
                'station_id' => $station1->id,
                'phone' => '+255 765 234 567',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create treasury user
        $treasury = User::updateOrCreate(
            ['email' => 'treasury@fuelflow.co.tz'],
            [
                'name' => 'Amina Mohamed',
                'password' => Hash::make('password'),
                'role' => User::ROLE_TREASURY,
                'phone' => '+255 713 345 678',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create client user
        $clientUser = User::updateOrCreate(
            ['email' => 'client@fuelflow.co.tz'],
            [
                'name' => 'Mohamed Rashid',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CLIENT,
                'phone' => '+255 784 567 890',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create client
        $client = Client::updateOrCreate(
            ['email' => 'info@kilinjtransport.co.tz'],
            [
                'user_id' => $clientUser->id,
                'company_name' => 'Kilimanjaro Transport Ltd',
                'contact_person' => 'Mohamed Rashid',
                'phone' => '+255 784 567 890',
                'address' => 'Plot 45, Nyerere Road, Kinondoni',
                'city' => 'Dar es Salaam',
                'state' => 'Dar es Salaam',
                'zip_code' => '14112',
                'country' => 'Tanzania',
                'credit_limit' => 50000000.00,
                'current_balance' => 0.00,
                'payment_terms' => 30,
                'status' => Client::STATUS_ACTIVE,
                'account_manager_id' => $admin->id,
                'preferred_stations' => [$station1->id, $station2->id],
                'special_instructions' => 'Tafadhali piga simu dakika 30 kabla ya utoaji',
                'tax_id' => 'TIN-123-456-789',
                'business_license' => 'BL-DSM-2024-12345',
                'contract_start_date' => now(),
                'contract_end_date' => now()->addYear(),
            ]
        );

        // Create vehicles for the client (Tanzania plate numbers format: T XXX ABC)
        $vehicle1 = Vehicle::updateOrCreate(
            ['plate_number' => 'T 123 ABC', 'client_id' => $client->id],
            [
                'vehicle_type' => Vehicle::TYPE_TRUCK,
                'make' => 'Isuzu',
                'model' => 'FRR',
                'year' => 2020,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 100.0,
                'current_mileage' => 45000,
                'driver_name' => 'Hamisi Juma',
                'driver_phone' => '+255 762 111 222',
                'status' => Vehicle::STATUS_ACTIVE,
                'registration_expiry' => now()->addMonths(6),
                'insurance_expiry' => now()->addMonths(3),
                'last_service_date' => now()->subMonths(2),
                'next_service_date' => now()->addMonths(1),
            ]
        );

        $vehicle2 = Vehicle::updateOrCreate(
            ['plate_number' => 'T 456 DEF', 'client_id' => $client->id],
            [
                'vehicle_type' => Vehicle::TYPE_VAN,
                'make' => 'Toyota',
                'model' => 'Hiace',
                'year' => 2019,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 70.0,
                'current_mileage' => 65000,
                'driver_name' => 'Grace Mwakasege',
                'driver_phone' => '+255 773 333 444',
                'status' => Vehicle::STATUS_ACTIVE,
                'registration_expiry' => now()->addMonths(8),
                'insurance_expiry' => now()->addMonths(4),
                'last_service_date' => now()->subMonths(1),
                'next_service_date' => now()->addMonths(2),
            ]
        );

        // Create fuel prices (Current Tanzania prices as of October 2025)
        // Petrol: ~2,752 TZS/L | Diesel: ~2,704 TZS/L
        FuelPrice::updateOrCreate(
            ['station_id' => $station1->id, 'fuel_type' => 'petrol', 'effective_date' => now()->toDateString()],
            [
                'price' => 2752.00,
                'is_active' => true,
                'created_by' => $admin->id,
            ]
        );

        FuelPrice::updateOrCreate(
            ['station_id' => $station1->id, 'fuel_type' => 'diesel', 'effective_date' => now()->toDateString()],
            [
                'price' => 2704.00,
                'is_active' => true,
                'created_by' => $admin->id,
            ]
        );

        FuelPrice::updateOrCreate(
            ['station_id' => $station2->id, 'fuel_type' => 'petrol', 'effective_date' => now()->toDateString()],
            [
                'price' => 2765.00,
                'is_active' => true,
                'created_by' => $admin->id,
            ]
        );

        FuelPrice::updateOrCreate(
            ['station_id' => $station2->id, 'fuel_type' => 'diesel', 'effective_date' => now()->toDateString()],
            [
                'price' => 2715.00,
                'is_active' => true,
                'created_by' => $admin->id,
            ]
        );

        $this->command->info('Fleet Fuel Management System seeded successfully!');
        $this->command->info('Admin Login: admin@fuelflow.co.tz / password');
        $this->command->info('Station Manager Login: manager@fuelflow.co.tz / password');
        $this->command->info('Fuel Pumper Login: pumper@fuelflow.co.tz / password');
        $this->command->info('Treasury Login: treasury@fuelflow.co.tz / password');
        $this->command->info('Client Login: client@fuelflow.co.tz / password');
    }
}
