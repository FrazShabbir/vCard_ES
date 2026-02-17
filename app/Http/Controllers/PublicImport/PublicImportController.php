<?php

namespace App\Http\Controllers\PublicImport;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\State;
use App\Models\SubRegion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicImportController extends Controller
{
    /**
     * Show the World Data Import dashboard page.
     */
    public function index(): View
    {
        return view('public-import.index');
    }

    /**
     * Run the world data import (regions, subregions, countries, states, cities).
     */
    public function runImport(Request $request): JsonResponse
    {
        try {
            $stats = ['regions' => 0, 'subregions' => 0, 'countries' => 0, 'states' => 0, 'cities' => 0];

            $file = file_get_contents(public_path('regions.json'));
            $data = json_decode($file);
            if ($data) {
                foreach ($data as $region) {
                    Region::updateOrCreate([
                        'name' => $region->name,
                        'code' => $region->wikiDataId ?? $region->code ?? null,
                        'file_id' => $region->id,
                        'created_by_id' => 1,
                    ]);
                    $stats['regions']++;
                }
            }

            $file = file_get_contents(public_path('subregions.json'));
            $data = json_decode($file);
            if ($data) {
                foreach ($data as $region) {
                    SubRegion::updateOrCreate([
                        'name' => $region->name,
                        'code' => $region->wikiDataId ?? $region->code ?? null,
                        'region_id' => $region->region_id ?? null,
                        'file_id' => $region->id,
                        'created_by_id' => 1,
                    ]);
                    $stats['subregions']++;
                }
            }

            $targetCountries = ['Pakistan', 'United Kingdom', 'India', 'United States'];
            $file = file_get_contents(public_path('data.json'));
            $file = json_decode($file);
            if ($file) {
                foreach ($file as $countries) {
                    if (!in_array($countries->name, $targetCountries, true)) {
                        continue;
                    }

                    $country = Country::firstOrNew(['file_id' => $countries->id]);
                    $country->file_id = $countries->id;
                    $country->name = $countries->name;
                    $country->iso3 = $countries->iso3 ?? null;
                    $country->phone_code = $countries->phone_code ?? null;
                    $country->code = 'CO-' . $countries->id;
                    $country->region_id = $countries->region_id ?? null;
                    $country->sub_region_id = $countries->subregion_id ?? null;
                    $country->iso2 = $countries->iso2 ?? null;
                    $country->currency = $countries->currency ?? null;
                    $country->currency_symbol = $countries->currency_symbol ?? null;
                    $country->numeric_code = $countries->numeric_code ?? null;
                    $country->capital = $countries->capital ?? null;
                    $country->nationality = $countries->nationality ?? null;
                    $country->created_by_id = 1;
                    $country->save();
                    $stats['countries']++;

                    foreach ($countries->states ?? [] as $i) {
                        $state = State::firstOrNew(['file_id' => $i->id]);
                        $state->file_id = $i->id;
                        $state->country_id = $country->id;
                        $state->name = $i->name;
                        $state->code = 'S-' . $country->id . '-' . $i->id;
                        $state->latitude = $i->latitude ?? null;
                        $state->longitude = $i->longitude ?? null;
                        $state->created_by_id = 1;
                        $state->save();
                        $stats['states']++;

                        foreach ($i->cities ?? [] as $j) {
                            $city = City::firstOrNew(['file_id' => $j->id]);
                            $city->file_id = $j->id;
                            $city->country_id = $country->id;
                            $city->state_id = $state->id;
                            $city->name = $j->name;
                            $city->code = 'C-' . $state->id . '-' . $country->id . '-' . $j->id;
                            $city->latitude = $j->latitude ?? null;
                            $city->longitude = $j->longitude ?? null;
                            $city->created_by_id = 1;
                            $city->save();
                            $stats['cities']++;
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'World data imported successfully.',
                'stats' => $stats,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
