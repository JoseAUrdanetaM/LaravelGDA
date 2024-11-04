<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RegionController extends Controller
{

    public function index()
    {
        $regions = Region::all();
        return response()->json($regions);
    }

    public function show(Region $region)
    {
        return response($region);
    }

    public function store(Request $request)
    {

        $region = Region::firstOrCreate($request->all());
        return response($region);
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
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the Region.',
            ], 500);
        }
    }
}
