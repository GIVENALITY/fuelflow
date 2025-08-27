<?php

namespace App\Http\Controllers;

use App\Models\FuelPrice;
use App\Models\Station;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BulkPriceUpdateController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $stations = Station::where('status', 'active')->get();
        return view('bulk-price-updates.index', compact('stations'));
    }

    public function downloadTemplate()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('bulk-price-updates.index')->with('error', 'Unauthorized access.');
        }

        // Create template data with town-based pricing format
        $templateData = [
            [
                'sn' => 1,
                'town' => 'Dar es Salaam',
                'cap_prices' => 2843,
                'petrol' => 2777,
                'diesel' => 2952,
                'kerosene' => 2887
            ],
            [
                'sn' => 2,
                'town' => 'Arusha',
                'cap_prices' => 2952,
                'petrol' => 2887,
                'diesel' => 2970,
                'kerosene' => 2905
            ],
            [
                'sn' => 3,
                'town' => 'Arumeru (Usa River)',
                'cap_prices' => 2963,
                'petrol' => 2898,
                'diesel' => 2957,
                'kerosene' => 2892
            ],
            [
                'sn' => 4,
                'town' => 'Karatu',
                'cap_prices' => 2962,
                'petrol' => 2897,
                'diesel' => 3033,
                'kerosene' => 2968
            ],
            [
                'sn' => 5,
                'town' => 'Longido',
                'cap_prices' => 2857,
                'petrol' => 2792,
                'diesel' => 2864,
                'kerosene' => 2799
            ],
            [
                'sn' => 6,
                'town' => 'Monduli',
                'cap_prices' => 2894,
                'petrol' => 2829,
                'diesel' => 2875,
                'kerosene' => 2810
            ],
            [
                'sn' => 7,
                'town' => 'Monduli-Makuyuni',
                'cap_prices' => 2867,
                'petrol' => 2802,
                'diesel' => 2871,
                'kerosene' => 2806
            ],
            [
                'sn' => 8,
                'town' => 'Ngorongoro (Loliondo)',
                'cap_prices' => 2873,
                'petrol' => 2808,
                'diesel' => 2860,
                'kerosene' => 2795
            ],
            [
                'sn' => 9,
                'town' => 'Pwani (Kibaha)',
                'cap_prices' => 2862,
                'petrol' => 2797,
                'diesel' => 2880,
                'kerosene' => 2815
            ],
            [
                'sn' => 10,
                'town' => 'Bagamoyo',
                'cap_prices' => 2921,
                'petrol' => 2856,
                'diesel' => 2929,
                'kerosene' => 2864
            ],
            [
                'sn' => 11,
                'town' => 'Bagamoyo (Miono)',
                'cap_prices' => 2917,
                'petrol' => 2852,
                'diesel' => 2929,
                'kerosene' => 2863
            ],
            [
                'sn' => 12,
                'town' => 'Bagamoyo (Mbwewe)',
                'cap_prices' => 2948,
                'petrol' => 2883,
                'diesel' => 2954,
                'kerosene' => 2889
            ],
            [
                'sn' => 13,
                'town' => 'Chalinze Junction',
                'cap_prices' => 2919,
                'petrol' => 2854,
                'diesel' => 2923,
                'kerosene' => 2857
            ],
            [
                'sn' => 14,
                'town' => 'Chalinze Township (Msata)',
                'cap_prices' => 2935,
                'petrol' => 2869,
                'diesel' => 2940,
                'kerosene' => 2875
            ],
            [
                'sn' => 15,
                'town' => 'Kibiti',
                'cap_prices' => 2928,
                'petrol' => 2863,
                'diesel' => 3043,
                'kerosene' => 2978
            ],
            [
                'sn' => 16,
                'town' => 'Kisarawe',
                'cap_prices' => 3032,
                'petrol' => 2967,
                'diesel' => 3064,
                'kerosene' => 2999
            ],
            [
                'sn' => 17,
                'town' => 'Mkuranga',
                'cap_prices' => 3081,
                'petrol' => 3016,
                'diesel' => 3058,
                'kerosene' => 2993
            ],
            [
                'sn' => 18,
                'town' => 'Rufiji',
                'cap_prices' => 2927,
                'petrol' => 2862,
                'diesel' => 2932,
                'kerosene' => 2867
            ],
            [
                'sn' => 19,
                'town' => 'Dodoma',
                'cap_prices' => 2931,
                'petrol' => 2866,
                'diesel' => 2937,
                'kerosene' => 2872
            ],
            [
                'sn' => 20,
                'town' => 'Bahi',
                'cap_prices' => 2945,
                'petrol' => 2880,
                'diesel' => 2940,
                'kerosene' => 2875
            ],
            [
                'sn' => 21,
                'town' => 'Chamwino',
                'cap_prices' => 2928,
                'petrol' => 2863,
                'diesel' => 3043,
                'kerosene' => 2978
            ],
            [
                'sn' => 22,
                'town' => 'Chamwino (Mlowa)',
                'cap_prices' => 3032,
                'petrol' => 2967,
                'diesel' => 3064,
                'kerosene' => 2999
            ],
            [
                'sn' => 23,
                'town' => 'Chemba',
                'cap_prices' => 3081,
                'petrol' => 3016,
                'diesel' => 3058,
                'kerosene' => 2993
            ],
            [
                'sn' => 24,
                'town' => 'Kondoa',
                'cap_prices' => 2927,
                'petrol' => 2862,
                'diesel' => 2932,
                'kerosene' => 2867
            ],
            [
                'sn' => 25,
                'town' => 'Kongwa',
                'cap_prices' => 2931,
                'petrol' => 2866,
                'diesel' => 2937,
                'kerosene' => 2872
            ],
            [
                'sn' => 26,
                'town' => 'Mpwapwa',
                'cap_prices' => 2945,
                'petrol' => 2880,
                'diesel' => 2940,
                'kerosene' => 2875
            ],
            [
                'sn' => 27,
                'town' => 'Mpwapwa (Chipogoro)',
                'cap_prices' => 2928,
                'petrol' => 2863,
                'diesel' => 3043,
                'kerosene' => 2978
            ],
            [
                'sn' => 28,
                'town' => 'Mtera (Makatopora)',
                'cap_prices' => 3032,
                'petrol' => 2967,
                'diesel' => 3064,
                'kerosene' => 2999
            ],
            [
                'sn' => 29,
                'town' => 'Mvumi',
                'cap_prices' => 3081,
                'petrol' => 3016,
                'diesel' => 3058,
                'kerosene' => 2993
            ],
            [
                'sn' => 30,
                'town' => 'Geita',
                'cap_prices' => 2927,
                'petrol' => 2862,
                'diesel' => 2932,
                'kerosene' => 2867
            ],
            [
                'sn' => 31,
                'town' => 'Bukombe',
                'cap_prices' => 2931,
                'petrol' => 2866,
                'diesel' => 2937,
                'kerosene' => 2872
            ],
            [
                'sn' => 32,
                'town' => 'Chato',
                'cap_prices' => 2945,
                'petrol' => 2880,
                'diesel' => 2940,
                'kerosene' => 2875
            ],
            [
                'sn' => 33,
                'town' => 'Mbogwe',
                'cap_prices' => 2928,
                'petrol' => 2863,
                'diesel' => 3043,
                'kerosene' => 2978
            ],
            [
                'sn' => 34,
                'town' => 'Nyang\'hwale',
                'cap_prices' => 3032,
                'petrol' => 2967,
                'diesel' => 3064,
                'kerosene' => 2999
            ],
            [
                'sn' => 35,
                'town' => 'Iringa',
                'cap_prices' => 3081,
                'petrol' => 3016,
                'diesel' => 3058,
                'kerosene' => 2993
            ],
            [
                'sn' => 36,
                'town' => 'Ismani',
                'cap_prices' => 2927,
                'petrol' => 2862,
                'diesel' => 2932,
                'kerosene' => 2867
            ],
            [
                'sn' => 37,
                'town' => 'Kilolo',
                'cap_prices' => 2931,
                'petrol' => 2866,
                'diesel' => 2937,
                'kerosene' => 2872
            ],
            [
                'sn' => 38,
                'town' => 'Mufindi (Mafinga)',
                'cap_prices' => 2945,
                'petrol' => 2880,
                'diesel' => 2940,
                'kerosene' => 2875
            ],
            [
                'sn' => 39,
                'town' => 'Mufindi (Igowole)',
                'cap_prices' => 2928,
                'petrol' => 2863,
                'diesel' => 3043,
                'kerosene' => 2978
            ]
        ];

        return Excel::download(new \App\Exports\BulkPriceUpdateTemplate($templateData), 'fuel_price_update_template.xlsx');
    }

    public function upload(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('bulk-price-updates.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048',
            'effective_date' => 'required|date|after_or_equal:today',
            'preview_mode' => 'boolean'
        ]);

        try {
            $file = $request->file('excel_file');
            $effectiveDate = Carbon::parse($request->effective_date);
            $previewMode = $request->boolean('preview_mode', true);

            // Import the Excel file
            $import = new \App\Imports\BulkPriceUpdateImport();
            $data = Excel::toArray($import, $file)[0];

            // Remove header row
            $headers = array_shift($data);
            
            $updates = [];
            $errors = [];
            $successCount = 0;

            foreach ($data as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2; // +2 because we removed header and arrays are 0-indexed
                
                try {
                    $rowUpdates = $this->processRow($row, $headers, $effectiveDate, $rowNumber);
                    if ($rowUpdates) {
                        // processRow now returns an array of updates (one per station in the town)
                        foreach ($rowUpdates as $update) {
                            $updates[] = $update;
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }

            if ($previewMode) {
                // Show preview
                return view('bulk-price-updates.preview', compact('updates', 'errors', 'effectiveDate'));
            } else {
                // Apply updates
                return $this->applyUpdates($updates, $errors, $effectiveDate);
            }

        } catch (\Exception $e) {
            return redirect()->route('bulk-price-updates.index')
                ->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    public function apply(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('bulk-price-updates.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'updates' => 'required|array',
            'effective_date' => 'required|date'
        ]);

        $updates = $request->updates;
        $effectiveDate = Carbon::parse($request->effective_date);
        $errors = [];

        return $this->applyUpdates($updates, $errors, $effectiveDate);
    }

    private function processRow($row, $headers, $effectiveDate, $rowNumber)
    {
        // Map headers to array keys
        $data = array_combine($headers, $row);
        
        // Validate required fields
        if (empty($data['town'])) {
            throw new \Exception('Town name is required');
        }

        // Find stations in this town
        $stations = Station::where('status', 'active')
            ->whereHas('location', function($query) use ($data) {
                $query->where('city', 'like', '%' . $data['town'] . '%')
                      ->orWhere('name', 'like', '%' . $data['town'] . '%');
            })
            ->get();

        if ($stations->isEmpty()) {
            // If no stations found by town, try to find by station name
            $stations = Station::where('status', 'active')
                ->where('name', 'like', '%' . $data['town'] . '%')
                ->get();
        }

        if ($stations->isEmpty()) {
            throw new \Exception("No stations found for town '{$data['town']}'");
        }

        $updates = [];

        foreach ($stations as $station) {
            $update = [
                'station_id' => $station->id,
                'station_code' => $station->code,
                'station_name' => $station->name,
                'town' => $data['town'],
                'petrol_update' => null,
                'diesel_update' => null,
                'kerosene_update' => null,
                'cap_prices_update' => null
            ];

            // Process petrol price
            if (!empty($data['petrol']) && is_numeric($data['petrol'])) {
                $currentPetrolPrice = FuelPrice::getCurrentPrice($station->id, 'petrol');
                $newPetrolPrice = (float) $data['petrol'];
                
                if ($newPetrolPrice <= 0) {
                    throw new \Exception("Petrol price for {$data['town']} must be greater than 0");
                }

                if (!$currentPetrolPrice || $currentPetrolPrice->price != $newPetrolPrice) {
                    $update['petrol_update'] = [
                        'current_price' => $currentPetrolPrice ? $currentPetrolPrice->price : 0,
                        'new_price' => $newPetrolPrice,
                        'change' => $newPetrolPrice - ($currentPetrolPrice ? $currentPetrolPrice->price : 0)
                    ];
                }
            }

            // Process diesel price
            if (!empty($data['diesel']) && is_numeric($data['diesel'])) {
                $currentDieselPrice = FuelPrice::getCurrentPrice($station->id, 'diesel');
                $newDieselPrice = (float) $data['diesel'];
                
                if ($newDieselPrice <= 0) {
                    throw new \Exception("Diesel price for {$data['town']} must be greater than 0");
                }

                if (!$currentDieselPrice || $currentDieselPrice->price != $newDieselPrice) {
                    $update['diesel_update'] = [
                        'current_price' => $currentDieselPrice ? $currentDieselPrice->price : 0,
                        'new_price' => $newDieselPrice,
                        'change' => $newDieselPrice - ($currentDieselPrice ? $currentDieselPrice->price : 0)
                    ];
                }
            }

            // Process kerosene price (if supported)
            if (!empty($data['kerosene']) && is_numeric($data['kerosene'])) {
                $currentKerosenePrice = FuelPrice::getCurrentPrice($station->id, 'kerosene');
                $newKerosenePrice = (float) $data['kerosene'];
                
                if ($newKerosenePrice <= 0) {
                    throw new \Exception("Kerosene price for {$data['town']} must be greater than 0");
                }

                if (!$currentKerosenePrice || $currentKerosenePrice->price != $newKerosenePrice) {
                    $update['kerosene_update'] = [
                        'current_price' => $currentKerosenePrice ? $currentKerosenePrice->price : 0,
                        'new_price' => $newKerosenePrice,
                        'change' => $newKerosenePrice - ($currentKerosenePrice ? $currentKerosenePrice->price : 0)
                    ];
                }
            }

            // Only add update if there are actual changes
            if ($update['petrol_update'] || $update['diesel_update'] || $update['kerosene_update']) {
                $updates[] = $update;
            }
        }

        return $updates;
    }

    private function applyUpdates($updates, $errors, $effectiveDate)
    {
        $successCount = 0;
        $appliedUpdates = [];

        DB::beginTransaction();
        
        try {
            foreach ($updates as $update) {
                $station = Station::find($update['station_id']);
                
                // Apply petrol price update
                if ($update['petrol_update']) {
                    $this->applyPriceUpdate($station, 'petrol', $update['petrol_update']['new_price'], $effectiveDate);
                    $successCount++;
                }

                // Apply diesel price update
                if ($update['diesel_update']) {
                    $this->applyPriceUpdate($station, 'diesel', $update['diesel_update']['new_price'], $effectiveDate);
                    $successCount++;
                }

                // Apply kerosene price update
                if ($update['kerosene_update']) {
                    $this->applyPriceUpdate($station, 'kerosene', $update['kerosene_update']['new_price'], $effectiveDate);
                    $successCount++;
                }

                $appliedUpdates[] = $update;
            }

            DB::commit();

            return redirect()->route('bulk-price-updates.index')
                ->with('success', "Successfully updated {$successCount} fuel prices for {$effectiveDate->format('M d, Y')}");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('bulk-price-updates.index')
                ->with('error', 'Error applying updates: ' . $e->getMessage());
        }
    }

    private function applyPriceUpdate($station, $fuelType, $newPrice, $effectiveDate)
    {
        // Deactivate current price
        $currentPrice = FuelPrice::getCurrentPrice($station->id, $fuelType);
        if ($currentPrice) {
            $currentPrice->update([
                'is_active' => false,
                'expiry_date' => $effectiveDate->subDay()
            ]);
        }

        // Create new price record
        FuelPrice::create([
            'station_id' => $station->id,
            'fuel_type' => $fuelType,
            'price' => $newPrice,
            'effective_date' => $effectiveDate,
            'is_active' => true,
            'created_by' => Auth::id(),
            'notes' => 'Bulk price update via Excel upload'
        ]);

        // Create notification for station manager
        if ($station->manager_id) {
            Notification::create([
                'user_id' => $station->manager_id,
                'title' => 'Fuel Price Updated',
                'message' => "Fuel price for {$fuelType} at {$station->name} has been updated to TZS " . number_format($newPrice, 2),
                'type' => 'price_update',
                'read_at' => null
            ]);
        }
    }
}
