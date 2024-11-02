<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function show(Customer $customer)
    {
        return response($customer);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'dni' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'last_name' => 'required|string',
            'id_reg' => 'required|exists:regions,id_reg',
            'id_com' => 'required|exists:communes,id_com',
        ]);

        $validatedData['date_reg'] = now();

        $customer = Customer::create($validatedData);

        return response()->json([
            'success' => true,
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->all());
        return response($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'msg' => 'Customer has been deleted'
        ]);
    }
}
