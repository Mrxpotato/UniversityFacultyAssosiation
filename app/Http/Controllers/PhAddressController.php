<?php

namespace App\Http\Controllers;

use App\Models\PhCity;
use App\Models\PhProvince;
use Illuminate\Http\JsonResponse;

class PhAddressController extends Controller
{
    public function provinces(): JsonResponse
    {
        $provinces = PhProvince::orderBy('name')->get(['id', 'name']);

        return response()->json($provinces);
    }

    public function cities(PhProvince $province): JsonResponse
    {
        $cities = $province->cities()->orderBy('name')->get(['id', 'name']);

        return response()->json($cities);
    }

    public function barangays(PhCity $city): JsonResponse
    {
        $barangays = $city->barangays()->orderBy('name')->get(['id', 'name']);

        return response()->json($barangays);
    }
}
