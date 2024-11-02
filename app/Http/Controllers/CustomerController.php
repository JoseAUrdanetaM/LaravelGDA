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

        $customer = Customer::firstOrCreate($request->all());
        return response($customer);
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
