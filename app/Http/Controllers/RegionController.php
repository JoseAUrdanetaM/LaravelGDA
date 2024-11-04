<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RegionController extends Controller
{

    public function index()
    {
        try {
            $regions = Region::where('status', 'A')->get();
            return response()->json([
                'success' => true,
                'regions' => $regions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve regions.',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $region = Region::where('id_reg', $id)->where('status', 'A')->firstOrFail();
            return  response()->json([
                'success' => true,
                'region' => $region,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Region not found.',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "description" => "required|string",
            ]);

            $validatedData['status'] = 'A';

            $region = Region::create($validatedData);

            return response()->json([
                'success' => true,
                'region' => $region,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the region.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $region = Region::findOrFail($id);

            if ($region->status === 'trash') {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no existe.',
                ], 400);
            }

            $region->update(['status' => 'trash']);

            return response()->json([
                'success' => true,
                'message' => 'Region has been logically deleted.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Region not found.',
                'errors' => $e->errors(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the Region.',
            ], 500);
        }
    }
}
