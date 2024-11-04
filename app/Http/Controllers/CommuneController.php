<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommuneController extends Controller
{

    public function index()
    {
        $communes = Commune::all();
        return response()->json([
            'success' => true,
            'Communes' => $communes,
        ]);
    }

    public function show(Commune $commune)
    {
        return response()->json([
            'success' => true,
            'Commune' => $commune,
        ]);
    }

    public function store(Request $request)
    {

        $commune = Commune::firstOrCreate($request->all());
        return response()->json([
            'success' => true,
            'Commune' => $commune,
        ]);
    }

    public function destroy($id)
    {
        try {
            $commune = Commune::findOrFail($id);

            if ($commune->status === 'trash') {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no existe.',
                ], 400);
            }

            $commune->update(['status' => 'trash']);

            return response()->json([
                'success' => true,
                'message' => 'Commune has been logically deleted.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Commune not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the Commune.',
            ], 500);
        }
    }
}
