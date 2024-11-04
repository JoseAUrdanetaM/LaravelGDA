<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommuneController extends Controller
{

    public function index()
    {
        try {
            $communes = Commune::where('status', 'A')->get();
            return response()->json([
                'success' => true,
                'regions' => $communes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Commune.',
                'errors' => $e->errors()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $commune = Commune::where('id_com', $id)->where('status', 'A')->firstOrFail();
            return  response()->json([
                'success' => true,
                'region' => $commune,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Commune not found.',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "description" => "required|string",
                "id_reg" => "required|exists:regions,id_reg"
            ]);

            $validatedData['status'] = 'A';

            $commune = Commune::create($validatedData);

            return response()->json([
                'success' => true,
                'commune' => $commune,
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
                'message' => 'An error occurred while creating the commune.',
                'errors' => $e->errors(),
            ], 500);
        }
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
