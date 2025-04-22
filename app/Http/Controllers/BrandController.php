<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Country;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * get all brands for detected country
     */
    public function index(Request $request)
    {
        // Get code of the country firstly
        $codeOfContry = $request->header('CF-IPCountry', 'DEFAULT');
        $allBrands = Brand::whereHas('countries', function ($query) use ($codeOfContry) {
            $query->where('code', $codeOfContry);
        })->with('countries')->get();

        // if we got no result, return the default toplist
        if ($allBrands->isEmpty()) {
            $allBrands = Brand::whereHas('countries',function ($query){
                $query->where('code','DEFAULT');
            })->get();
        }

        return response()->json($allBrands);

    }

    /**
     * Create a new brand.
     */
    public function createBrand(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_image' => 'required|url',
            'rating' => 'required|integer|min:0|max:5',
        ]);

        $brand = Brand::create($validated);

        return response()->json([
            'code' => 'success',
            'brand' => $brand
        ], 201);
    }

    /**
     * Display the specified brand by id.
     */
    public function getBrandDetails($id)
    {
        try {

            $brand = Brand::with('countries')->findOrFail($id);
            return response()->json($brand);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'error',
                'message' => 'Brand not found'
            ], 404);
        }
    }

    /**
     * Edit brand
     */
    public function editBrand(Request $request,$id)
    {
        try {

            $brand = Brand::findOrFail($id);

            $brand->update($request->validate([
                'brand_name' => 'string|max:255',
                'brand_image' => 'string|url',
                'rating' => 'integer|min:0|max:5',
            ]));

            return response()->json([
                'code' => 'success',
                'message' => 'Brand updated successfully',
                'brand' => $brand
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
    }

    /**
     * Delete a brand by id.
     */
    public function deleteBrand($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            return response()->json([
                'code' => 'success',
                'message' => 'Brand deleted successfully'
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
    }

    /**
     * Assign a country to a brand.
     */
    public function assignCountry(Request $request, $brandId)
    {
        $request->validate([
            'countries' => 'required|array',
            'countries.*' => 'exists:countries,code'
        ]);

        try {

            $brand = Brand::findOrFail($brandId);
            $countriesIds = Country::whereIn('code', $request->countries)->pluck('id');

            $brand->countries()->sync($countriesIds);

            return response()->json([
                'code' => 'success',
                'message' => 'Country assigned successfully',
                'brand' => $brand
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
    }

}
