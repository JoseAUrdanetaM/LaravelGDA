<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommuneController extends Controller
{
    //Mostrar todos los Communes
    public function index()
    {
        try {
            //Muestra todos los Communes activos
            $communes = Commune::where('status', 'A')->get();
            return response()->json([
                'success' => true,
                'regions' => $communes,
            ]);
        } catch (\Exception $e) {
            // Manejo de errores al listar communes
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Commune.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    //Mostrar Commune acorde a ID
    public function show($id)
    {
        try {
            //Muestra 1 Commune especifico según su id(id_com)
            $commune = Commune::where('id_com', $id)->where('status', 'A')->firstOrFail();
            return  response()->json([
                'success' => true,
                'region' => $commune,
            ]);
        } catch (ModelNotFoundException $e) {
            // Manejo de errores si el commune no se encuentra
            return response()->json([
                'success' => false,
                'message' => 'Commune not found.',
            ], 404);
        }
    }

    //Agregar Nuevo Commune
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada para crear un nuevo Commune
            $validatedData = $request->validate([
                "description" => "required|string",
                "id_reg" => "required|exists:regions,id_reg"
            ]);

            // Establecer el estado como activo por defecto
            $validatedData['status'] = 'A';

            //Una vez validado crea nuevo Commune
            $commune = Commune::create($validatedData);

            return response()->json([
                'success' => true,
                'commune' => $commune,
            ]);
        } catch (ValidationException $e) {
            // Manejar errores de validación
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the commune.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    //Borrar Commune
    public function destroy($id)
    {
        try {
            // Buscar commune por ID
            $commune = Commune::findOrFail($id);

            // Verificar si la comuna ya está eliminada lógicamente (status trash)
            if ($commune->status === 'trash') {
                return response()->json([
                    'success' => false,
                    'message' => 'Record does not exist.',
                ], 400);
            }

            // Actualizar el estado del commune a 'trash'
            $commune->update(['status' => 'trash']);

            return response()->json([
                'success' => true,
                'message' => 'Commune has been logically deleted.',
            ]);
        } catch (ModelNotFoundException $e) {
            // Manejo de errores si el commune no se encuentra
            return response()->json([
                'success' => false,
                'message' => 'Commune not found.'
            ], 404);
        } catch (\Exception $e) {
            // Manejar cualquier otro error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the Commune.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
