<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{

    public function index()
    {
        try {
            $customers = Customer::where('status', 'A')->get();
            return response()->json([
                'success' => true,
                'customers' => $customers,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers.',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            return response()->json([
                'success' => true,
                'customer' => $customer,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'dni' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string',
                'last_name' => 'required|string',
                'id_reg' => 'required|exists:regions,id_reg',
                'id_com' => 'required|exists:communes,id_com',
            ]);

            $validatedData['status'] = 'A'; // Establece el estado activo por defecto
            $validatedData['date_reg'] = now();

            $customer = Customer::create($validatedData);

            return response()->json([
                'success' => true,
                'customer' => $customer,
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
                'message' => 'An error occurred while creating the customer.',
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $validatedData = $request->validate([
                'dni' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string',
                'last_name' => 'required|string',
                'id_reg' => 'required|exists:regions,id_reg',
                'id_com' => 'required|exists:communes,id_com',
            ]);

            $customer->update($validatedData);

            return response()->json([
                'success' => true,
                'customer' => $customer,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            if ($customer->status === 'trash') {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no existe.',
                ], 400);
            }

            $customer->update(['status' => 'trash']);

            return response()->json([
                'success' => true,
                'message' => 'Customer has been logically deleted.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the customer.',
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'dni' => 'nullable|string',
                'email' => 'nullable|email',
            ]);

            if (empty($validatedData['dni']) && empty($validatedData['email'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar un DNI o un email para la búsqueda.',
                ], 400);
            }

            $query = Customer::where('status', 'A');

            if (!empty($validatedData['dni'])) {
                $query->where('dni', $validatedData['dni']);
            }

            if (!empty($validatedData['email'])) {
                $query->where('email', $validatedData['email']);
            }

            $customers = $query->with(['region', 'commune'])->get();

            return response()->json([
                'success' => true,
                'Customers:' => $customers,
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
                'message' => 'An error occurred while searching for customers.',
            ], 500);
        }
    }
}
