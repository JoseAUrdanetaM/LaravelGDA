<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    //Mostrar todos los Regions
    public function index()
    {
        try {
            //Mostrar Regions activas (status a)
            $regions = Region::where('status', 'A')->get();
            return response()->json([
                'success' => true,
                'regions' => $regions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve regions.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    //Mostrar 1 Region acorde a id (id_reg)
    public function show($id)
    {
        try {
            // Mostrar Regions activas (status a)
            $region = Region::where('id_reg', $id)->where('status', 'A')->firstOrFail();
            return  response()->json([
                'success' => true,
                'region' => $region,
            ]);
        } catch (ModelNotFoundException $e) {
            //Manejo de errores en caso de no encontrar
            return response()->json([
                'success' => false,
                'message' => 'Region not found.',
            ], 404);
        } catch (\Exception $e) {
            //Manejo de otros errores
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve regions.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    //Crear nueva Region
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                "description" => "required|string",
            ]);

            // Establecer el estado de Region como activa
            $validatedData['status'] = 'A';

            // Crear la nueva Region
            $region = Region::create($validatedData);

            return response()->json([
                'success' => true,
                'region' => $region,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                //Manejo de errores de validación
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                //Manejo de otros errores
                'success' => false,
                'message' => 'An error occurred while creating the region.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    //Borrar Region acorde a ID
    public function destroy($id)
    {
        try {
            //Buscar Region por Id para validar si existe
            $region = Region::findOrFail($id);

            // Comprobar si el estado es 'trash' (borrado)
            if ($region->status === 'trash') {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no existe.',
                ], 400);
            }

            // Actualizar el estado de la región a 'trash'
            $region->update(['status' => 'trash']);

            return response()->json([
                'success' => true,
                'message' => 'Region has been logically deleted.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                //Manejo de errores en caso de no conseguir el ID
                'success' => false,
                'message' => 'Region not found.',
                'errors' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                //Manejo de otros errores
                'success' => false,
                'message' => 'An error occurred while deleting the Region.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
