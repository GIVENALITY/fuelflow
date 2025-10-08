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

        // Create stations
        $station1 = Station::updateOrCreate(
            ['code' => 'DT001'],
            [
                'name' => 'Downtown Fuel Station',
                'address' => '123 Main Street',
                'city' => 'Downtown',
                'state' => 'CA',
                'zip_code' => '90210',
                'phone' => '(555) 123-4567',
                'email' => 'downtown@fuelflow.co.tz',
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
                    'saturday' => ['open' => '07:00', 'close' => '20:00'],
                    'sunday' => ['open' => '08:00', 'close' => '18:00'],
                ],
                'timezone' => 'America/Los_Angeles',
            ]
        );

        $station2 = Station::updateOrCreate(
            ['code' => 'HW001'],
            [
                'name' => 'Highway Fuel Station',
                'address' => '456 Highway Road',
                'city' => 'Highway City',
                'state' => 'CA',
                'zip_code' => '90211',
                'phone' => '(555) 987-6543',
                'email' => 'highway@fuelflow.co.tz',
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
                'timezone' => 'America/Los_Angeles',
            ]
        );

        // Create station manager
        $stationManager = User::updateOrCreate(
            ['email' => 'manager@fuelflow.co.tz'],
            [
                'name' => 'John Station Manager',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STATION_MANAGER,
                'station_id' => $station1->id,
                'phone' => '(555) 111-2222',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Update station with manager
        $station1->update(['manager_id' => $stationManager->id]);

        // Create fuel pumper
        $fuelPumper = User::updateOrCreate(
            ['email' => 'pumper@fuelflow.co.tz'],
            [
                'name' => 'Mike Fuel Pumper',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STATION_ATTENDANT,
                'station_id' => $station1->id,
                'phone' => '(555) 333-4444',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create treasury user
        $treasury = User::updateOrCreate(
            ['email' => 'treasury@fuelflow.co.tz'],
            [
                'name' => 'Sarah Treasury',
                'password' => Hash::make('password'),
                'role' => User::ROLE_TREASURY,
                'phone' => '(555) 555-6666',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create client user
        $clientUser = User::updateOrCreate(
            ['email' => 'client@fuelflow.co.tz'],
            [
                'name' => 'Bob Client',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CLIENT,
                'phone' => '(555) 777-8888',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Create client
        $client = Client::updateOrCreate(
            ['email' => 'bob@abctransport.co.tz'],
            [
                'user_id' => $clientUser->id,
                'company_name' => 'ABC Transport Company',
                'contact_person' => 'Bob Client',
                'phone' => '(555) 777-8888',
                'address' => '789 Business Avenue',
                'city' => 'Business City',
                'state' => 'CA',
                'zip_code' => '90212',
                'country' => 'US',
                'credit_limit' => 50000000.00,
                'current_balance' => 0.00,
                'payment_terms' => 30,
                'status' => Client::STATUS_ACTIVE,
                'account_manager_id' => $admin->id,
                'preferred_stations' => [$station1->id, $station2->id],
                'special_instructions' => 'Please call 30 minutes before delivery',
                'tax_id' => '12-3456789',
                'business_license' => 'BL123456',
                'contract_start_date' => now(),
                'contract_end_date' => now()->addYear(),
            ]
        );

        // Create vehicles for the client
        $vehicle1 = Vehicle::updateOrCreate(
            ['plate_number' => 'ABC123', 'client_id' => $client->id],
            [
                'vehicle_type' => Vehicle::TYPE_TRUCK,
                'make' => 'Ford',
                'model' => 'F-150',
                'year' => 2022,
                'fuel_type' => Vehicle::FUEL_PETROL,
                'tank_capacity' => 80.0,
                'current_mileage' => 15000,
                'driver_name' => 'John Driver',
                'driver_phone' => '(555) 999-0000',
                'status' => Vehicle::STATUS_ACTIVE,
                'registration_expiry' => now()->addMonths(6),
                'insurance_expiry' => now()->addMonths(3),
                'last_service_date' => now()->subMonths(2),
                'next_service_date' => now()->addMonths(1),
            ]
        );

        $vehicle2 = Vehicle::updateOrCreate(
            ['plate_number' => 'XYZ789', 'client_id' => $client->id],
            [
                'vehicle_type' => Vehicle::TYPE_VAN,
                'make' => 'Chevrolet',
                'model' => 'Express',
                'year' => 2021,
                'fuel_type' => Vehicle::FUEL_DIESEL,
                'tank_capacity' => 120.0,
                'current_mileage' => 25000,
                'driver_name' => 'Jane Driver',
                'driver_phone' => '(555) 111-2222',
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
