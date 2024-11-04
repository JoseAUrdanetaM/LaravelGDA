<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    //Mostrar todos los Customers
    public function index()
    {
        try {
            // Mostrar todos los customers activos
            $customers = Customer::where('status', 'A')->get();
            return response()->json([
                'success' => true,
                'customers' => $customers,
            ]);
        } catch (\Exception $e) {
            //Manejo de errores al mostrar clientes
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    //Mostrar Customer acorde a ID
    public function show($id)
    {
        try {
            //Mostrar cliente especifico por ID que se encuentre activo
            $customer = Customer::where('dni', $id)->where('status', 'A')->firstOrFail();

            return response()->json([
                'success' => true,
                'customer' => $customer,
            ]);
        } catch (ModelNotFoundException $e) {
            //Manejo de errores en caso de no encontrar el id
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }
    }

    //Agregar nuevo Customer
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada para crear un nuevo customer
            $validatedData = $request->validate([
                'dni' => 'required|string|unique:customers,dni',
                'email' => 'required|email|unique:customers,email',
                'name' => 'required|string',
                'last_name' => 'required|string',
                'id_reg' => 'required|exists:regions,id_reg',
                'id_com' => 'required|exists:communes,id_com',
            ]);

            // Establecer el estado como activo por defecto y la fecha de registro
            $validatedData['status'] = 'A';
            $validatedData['date_reg'] = now();

            // Crear un nuevo cliente
            $customer = Customer::create($validatedData);

            return response()->json([
                'success' => true,
                'customer' => $customer,
            ]);
        } catch (ValidationException $e) {
            // Manejar errores de validación
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Manejar cualquier otro error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the customer.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    //Eliminar Customer
    public function destroy($id)
    {
        try {
            // Buscar el customer por ID
            $customer = Customer::findOrFail($id);

            // Verificar si el cliente ya está eliminado lógicamente (status: trash)
            if ($customer->status === 'trash') {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no existe.',
                ], 400);
            }

            //Actualiza el estado del customer a Trash
            $customer->update(['status' => 'trash']);

            return response()->json([
                'success' => true,
                'message' => 'Customer has been logically deleted.',
            ]);
        } catch (ModelNotFoundException $e) {
            //Manejo de errores en caso de no conseguir el cliente.
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        } catch (\Exception $e) {
            //Manejo de otros errores
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the customer.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    //Buscar clientes acorde a DNI o correo
    public function search(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'dni' => 'nullable|string|not_in:0', // Evitar que 'dni' sea un valor falso como '0'
                'email' => 'nullable|email',
            ]);

            // Asegurarse de que al menos uno de los campos esté presente
            if (empty($validatedData['dni']) && empty($validatedData['email'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must provide a DNI or an email for the search.',
                ], 400);
            }

            // Realiza la consulta solo de clientes activos
            $query = Customer::where('status', 'A');

            // Filtra por 'dni' si está presente y no es un valor falso
            if (!empty($validatedData['dni'])) {
                $query->where('dni', $validatedData['dni']);
            }

            // Filtra por 'email' si está presente
            if (!empty($validatedData['email'])) {
                $query->where('email', $validatedData['email']);
            }

            // Realiza la consulta y limita los resultados a solo los campos necesarios
            $customers = $query->with(['region', 'commune'])->get()->map(function ($customer) {
                return [
                    'name' => $customer->name,
                    'last_name' => $customer->last_name,
                    'address' => $customer->address ?? null, // Devuelve null si no hay dirección
                    'region' =>  $customer->region->description,
                    'commune' => $customer->commune->description,
                ];
            });

            // Verificar si se encontraron clientes
            if ($customers->isEmpty()) {
                $noResultMessage = 'No customers found';
                if (!empty($validatedData['dni'])) {
                    $noResultMessage .= " with the DNI {$validatedData['dni']}";
                }
                if (!empty($validatedData['email'])) {
                    $noResultMessage .= " with the email {$validatedData['email']}";
                }
                $noResultMessage .= '.';

                return response()->json([
                    'success' => false,
                    'message' => $noResultMessage,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'customers' => $customers,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                //Manejo de erroe en validaciones
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            //Manejo de otros errores
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching for customers.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
